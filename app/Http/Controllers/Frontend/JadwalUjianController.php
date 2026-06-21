<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Support\XlsxSheetReader;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\View\View;
use RuntimeException;

class JadwalUjianController extends Controller
{
    private const FILE_URL = 'https://docs.google.com/file/d/1Zb_mtWSFmmhK79vlvNJStZ6Kt20-bfSa/edit?filetype=msexcel';
    private const SHEET_NUMBER = 1;
    private const HEADER_ROW = 4;

    public function index(XlsxSheetReader $reader): View
    {
        $error = null;
        $jadwal = [];

        try {
            $jadwal = Cache::remember('jadwal-ujian.google-sheet', now()->addMinutes(30), function () use ($reader) {
                $response = Http::timeout(20)
                    ->retry(2, 500)
                    ->get($this->downloadUrl());

                if (! $response->successful()) {
                    throw new RuntimeException('Google Sheet tidak dapat diakses.');
                }

                return $reader->read($response->body(), self::SHEET_NUMBER, self::HEADER_ROW);
            });
        } catch (\Throwable) {
            $error = 'Jadwal ujian belum dapat dimuat. Pastikan file Google Sheet dapat diakses publik.';
        }

        return view('frontend.jadwal-ujian.index', [
            'jadwal' => $jadwal,
            'error' => $error,
            'columns' => [
                'Hari',
                'Tanggal',
                'Waktu',
                'Nama',
                'Ujian',
                'Moderator/Sekertaris',
                'Pembimbing 1',
                'Pembimbing 2',
                'Penguji 1',
                'Penguji 2',
            ],
        ]);
    }

    private function downloadUrl(): string
    {
        preg_match('~/d/([^/]+)~', self::FILE_URL, $matches);
        $fileId = $matches[1] ?? null;

        if (! $fileId) {
            throw new RuntimeException('ID file Google Sheet tidak valid.');
        }

        return 'https://drive.google.com/uc?export=download&id='.$fileId;
    }
}
