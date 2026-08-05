<?php

namespace Database\Seeders;

use App\Models\BeritaEksternal;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class BeritaEksternalSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['judul' => 'Prodi Manajemen FEB UNM Gelar Kuliah Umum Kewirausahaan', 'sumber' => 'Tribun Timur', 'offset' => -20],
            ['judul' => 'Mahasiswa Manajemen UNM Raih Juara Kompetisi Bisnis Nasional', 'sumber' => 'Detik', 'offset' => -45],
            ['judul' => 'Dosen Manajemen UNM Jadi Pembicara Konferensi Internasional', 'sumber' => 'Kompas', 'offset' => -70],
        ];

        foreach ($items as $item) {
            BeritaEksternal::firstOrCreate(
                ['judul' => $item['judul']],
                [
                    'sumber' => $item['sumber'],
                    'url' => 'https://example.com/berita-contoh',
                    'tanggal' => Carbon::now()->addDays($item['offset']),
                    'ringkasan' => 'Ringkasan singkat berita (contoh — ganti melalui panel admin).',
                    'status' => 'published',
                ],
            );
        }
    }
}
