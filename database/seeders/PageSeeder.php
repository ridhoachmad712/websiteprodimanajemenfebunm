<?php

namespace Database\Seeders;

use App\Models\Page;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    public function run(): void
    {
        // Halaman Profil dengan 7 section (struktur dari analisa situs lama).
        Page::updateOrCreate(
            ['slug' => 'profil'],
            [
                'title'    => 'Profil Program Studi',
                'content'  => null,
                'sections' => [
                    'sambutan'   => ['judul' => 'Sambutan Ketua Program Studi', 'isi' => ''],
                    'sejarah'    => ['judul' => 'Sejarah Program Studi', 'isi' => ''],
                    'visi'       => ['judul' => 'Visi', 'isi' => ''],
                    'misi'       => ['judul' => 'Misi', 'isi' => ''],
                    'tujuan'     => ['judul' => 'Tujuan', 'isi' => ''],
                    'strategi'   => ['judul' => 'Strategi', 'isi' => ''],
                    'kompetensi' => ['judul' => 'Kompetensi Lulusan', 'isi' => ''],
                ],
            ],
        );

        $pages = [
            'akreditasi'           => 'Akreditasi',
            'fasilitas'            => 'Fasilitas',
            'sop-petaprosesbisnis' => 'Peta Proses Bisnis Fakultas',
            'kalender-akademik'    => 'Kalender Akademik',
            'kurikulum'            => 'Kurikulum',
            'hima'                 => 'HIMA Manajemen',
            'alumni'               => 'Direktori Alumni',
            'icoman2025'           => 'ICOMAN 2025',
            'hubungi-kami'         => 'Hubungi Kami',
        ];

        foreach ($pages as $slug => $title) {
            Page::updateOrCreate(
                ['slug' => $slug],
                ['title' => $title, 'content' => '<p>Konten halaman '.$title.' akan diisi melalui panel admin.</p>'],
            );
        }
    }
}
