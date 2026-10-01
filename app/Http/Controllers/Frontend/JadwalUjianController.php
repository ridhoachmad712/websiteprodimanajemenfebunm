<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Support\XlsxSheetReader;
use Carbon\CarbonImmutable;
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

    private const PUBLIC_COLUMNS = [
        'hari' => 'Hari',
        'tanggal' => 'Tanggal',
        'waktu' => 'Waktu',
        'nama' => 'Nama',
        'ujian' => 'Ujian',
        'moderator/sekertaris' => 'Moderator/Sekertaris',
        'pembimbing 1' => 'Pembimbing 1',
        'pembimbing 2' => 'Pembimbing 2',
        'penguji 1' => 'Penguji 1',
        'penguji 2' => 'Penguji 2',
    ];

    private const PERSON_COLUMNS = ['Nama', 'Moderator/Sekertaris', 'Pembimbing 1', 'Pembimbing 2', 'Penguji 1', 'Penguji 2'];

    private const WEEKDAYS = [
        'senin' => 'Senin', 'selasa' => 'Selasa', 'rabu' => 'Rabu',
        'kamis' => 'Kamis', 'jumat' => 'Jumat', "jum'at" => 'Jumat', 'sabtu' => 'Sabtu', 'minggu' => 'Minggu',
    ];

    private const CREDENTIALS = [
        'm.si' => 'M.Si', 's.pd' => 'S.Pd', 's.pd.i' => 'S.Pd.I',
        'm.pd' => 'M.Pd', 'm.ak' => 'M.Ak', 'm.sc' => 'M.Sc',
        'ph.d' => 'Ph.D', 'phd' => 'Ph.D', 'mhrmgt' => 'MHRMgt',
    ];

    private const ACRONYMS = ['UNM', 'FEB', 'MBKM', 'KKN', 'SDM'];

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

            $today = CarbonImmutable::now('Asia/Makassar')->startOfDay();
            $upcoming = [];
            foreach ($jadwal as $sourceRow) {
                $row = [];
                foreach ($sourceRow as $column => $value) {
                    $label = self::PUBLIC_COLUMNS[mb_strtolower(trim($column))] ?? null;
                    if ($label !== null) {
                        $row[$label] = $value;
                    }
                }

                $date = $this->scheduleDate($row['Tanggal'] ?? '');
                if ($date === null || $date->lessThan($today)) {
                    continue;
                }

                $row['Tanggal'] = $date->locale('id')->translatedFormat('d M Y');
                foreach ($row as $column => $value) {
                    if ($column !== 'Tanggal') {
                        $row[$column] = $this->displayValue($column, (string) $value);
                    }
                }
                $upcoming[] = ['date' => $date, 'row' => $row];
            }

            usort($upcoming, fn (array $a, array $b): int => $a['date']->getTimestamp() <=> $b['date']->getTimestamp());
            $jadwal = array_column($upcoming, 'row');
        } catch (\Throwable) {
            $error = 'Jadwal ujian belum dapat dimuat. Pastikan file Google Sheet dapat diakses publik dan URL pada Pengaturan sudah benar.';
        }

        return view('frontend.jadwal-ujian.index', [
            'jadwal' => $jadwal,
            'error' => $error,
            'columns' => $this->columns($jadwal),
        ]);
    }

    private function displayValue(string $column, string $value): string
    {
        $value = preg_replace('/\s+/u', ' ', trim($value)) ?? trim($value);

        if ($column === 'Hari') {
            return self::WEEKDAYS[mb_strtolower($value, 'UTF-8')] ?? $value;
        }

        if ($column === 'Ujian' && $this->isUppercase($value)) {
            $title = mb_convert_case($value, MB_CASE_TITLE, 'UTF-8');

            return preg_replace_callback('/\b(?:'.implode('|', self::ACRONYMS).')\b/iu',
                fn (array $match): string => mb_strtoupper($match[0], 'UTF-8'), $title) ?? $title;
        }

        if (in_array($column, self::PERSON_COLUMNS, true)) {
            $parts = explode(',', $value, 2);
            $name = trim($parts[0]);
            $name = $this->isUppercase($name)
                ? mb_convert_case($name, MB_CASE_TITLE, 'UTF-8')
                : $name;

            if (count($parts) === 1) {
                return $name;
            }

            $credentials = preg_replace_callback('/[\p{L}]+(?:\.[\p{L}]+)*/u', function (array $match): string {
                return self::CREDENTIALS[mb_strtolower($match[0], 'UTF-8')] ?? $match[0];
            }, trim($parts[1]));

            return $name.', '.($credentials ?? trim($parts[1]));
        }

        return $value;
    }

    private function isUppercase(string $value): bool
    {
        return preg_match('/\p{L}/u', $value) === 1
            && mb_strtoupper($value, 'UTF-8') === $value;
    }

    private function scheduleDate(string $value): ?CarbonImmutable
    {
        $value = trim($value);
        if (preg_match('/^\d+(?:\.\d+)?$/', $value)) {
            $serial = (int) floor((float) $value);
            if ($serial < 1 || $serial > 2958465) {
                return null;
            }

            $days = $serial < 60 ? $serial + 1 : $serial;

            return CarbonImmutable::create(1899, 12, 30, 0, 0, 0, 'Asia/Makassar')->addDays($days);
        }

        foreach (['Y-m-d', 'd/m/Y', 'd-m-Y', 'd.m.Y'] as $format) {
            try {
                $date = CarbonImmutable::createFromFormat('!'.$format, $value, 'Asia/Makassar');
                if ($date !== null && $date->format($format) === $value) {
                    return $date;
                }
            } catch (\Throwable) {
                continue;
            }
        }

        foreach (['d M Y', 'd F Y'] as $format) {
            try {
                $date = CarbonImmutable::createFromLocaleFormat('!'.$format, 'id', $value, 'Asia/Makassar');
                if ($date !== null) {
                    return $date;
                }
            } catch (\Throwable) {
                continue;
            }
        }

        return null;
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
