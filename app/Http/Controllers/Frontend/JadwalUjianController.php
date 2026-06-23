<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\XlsxSheetReader;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use RuntimeException;

class JadwalUjianController extends Controller
{
    /** Nilai default bila admin belum mengisi pengaturan. */
    private const DEFAULT_FILE_URL = 'https://docs.google.com/file/d/1Zb_mtWSFmmhK79vlvNJStZ6Kt20-bfSa/edit?filetype=msexcel';

    private const DEFAULT_SHEET_NUMBER = 1;

    private const DEFAULT_HEADER_ROW = 4;

    public function index(XlsxSheetReader $reader): View
    {
        $error = null;
        $jadwal = [];

        $fileUrl = trim((string) Setting::get('jadwal_ujian.file_url', self::DEFAULT_FILE_URL));
        $sheetNumber = max(1, (int) Setting::get('jadwal_ujian.sheet_number', self::DEFAULT_SHEET_NUMBER));
        $headerRow = max(1, (int) Setting::get('jadwal_ujian.header_row', self::DEFAULT_HEADER_ROW));

        try {
            if ($fileUrl === '') {
                throw new RuntimeException('URL Google Sheet belum diatur.');
            }

            $cacheKey = 'jadwal-ujian.'.md5($fileUrl.'|'.$sheetNumber.'|'.$headerRow);

            $jadwal = Cache::remember($cacheKey, now()->addMinutes(30), function () use ($reader, $fileUrl, $sheetNumber, $headerRow) {
                $response = Http::timeout(20)
                    ->retry(2, 500)
                    ->get($this->downloadUrl($fileUrl));

                if (! $response->successful()) {
                    throw new RuntimeException('Google Sheet tidak dapat diakses.');
                }

                return $reader->read($response->body(), $sheetNumber, $headerRow);
            });
        } catch (\Throwable) {
            $error = 'Jadwal ujian belum dapat dimuat. Pastikan file Google Sheet dapat diakses publik dan URL pada Pengaturan sudah benar.';
        }

        return view('frontend.jadwal-ujian.index', [
            'jadwal' => $jadwal,
            'error' => $error,
            'columns' => $this->columns($jadwal),
        ]);
    }

    /**
     * Kolom tabel diturunkan dari header sheet yang sebenarnya (gabungan
     * seluruh kunci baris, menjaga urutan kemunculan) agar tetap akurat
     * meski struktur sheet berubah. Kosong bila tak ada data.
     *
     * @param  array<int, array<string, string>>  $jadwal
     * @return array<int, string>
     */
    private function columns(array $jadwal): array
    {
        $columns = [];
        foreach ($jadwal as $row) {
            foreach (array_keys($row) as $label) {
                if (! in_array($label, $columns, true)) {
                    $columns[] = $label;
                }
            }
        }

        return $columns;
    }

    /**
     * Ubah URL Google Sheet/Drive menjadi URL unduh langsung XLSX.
     * Bila URL sudah berupa tautan unduh langsung, dipakai apa adanya.
     */
    private function downloadUrl(string $fileUrl): string
    {
        if (preg_match('~/d/([^/]+)~', $fileUrl, $matches)) {
            return 'https://drive.google.com/uc?export=download&id='.$matches[1];
        }

        if (preg_match('~[?&]id=([^&]+)~', $fileUrl, $matches)) {
            return 'https://drive.google.com/uc?export=download&id='.$matches[1];
        }

        if (str_starts_with($fileUrl, 'http')) {
            return $fileUrl;
        }

        throw new RuntimeException('URL file Google Sheet tidak valid.');
    }
}
