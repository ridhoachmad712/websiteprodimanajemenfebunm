<?php

namespace Database\Seeders;

use App\Models\Download;
use Illuminate\Database\Seeder;

class DownloadSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['judul' => 'RPS Manajemen Keuangan', 'kategori' => 'RPS', 'urutan' => 1],
            ['judul' => 'RPS Manajemen Pemasaran', 'kategori' => 'RPS', 'urutan' => 2],
            ['judul' => 'SK Mengajar Semester Ganjil', 'kategori' => 'SK Mengajar', 'urutan' => 1],
            ['judul' => 'Formulir Pengajuan Judul Skripsi', 'kategori' => 'Formulir', 'urutan' => 1],
            ['judul' => 'Panduan Penulisan Skripsi', 'kategori' => 'Panduan', 'urutan' => 1],
        ];

        foreach ($items as $item) {
            Download::firstOrCreate(
                ['judul' => $item['judul']],
                array_merge($item, [
                    'status' => 'published',
                    'url' => 'https://example.com/dokumen-contoh',
                    'deskripsi' => 'Dokumen contoh — unggah file asli melalui panel admin.',
                ]),
            );
        }
    }
}
