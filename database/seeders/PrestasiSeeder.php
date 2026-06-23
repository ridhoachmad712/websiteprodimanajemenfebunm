<?php

namespace Database\Seeders;

use App\Models\Prestasi;
use Illuminate\Database\Seeder;

class PrestasiSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['judul' => 'Juara 1 Business Plan Competition Nasional', 'kategori' => 'mahasiswa', 'tingkat' => 'nasional', 'peraih' => 'Tim Manajemen FEB UNM', 'penyelenggara' => 'Universitas Indonesia', 'tanggal' => '2026-03-15'],
            ['judul' => 'Best Paper Award ICOMAN 2025', 'kategori' => 'dosen', 'tingkat' => 'internasional', 'peraih' => 'Dr. Contoh Dosen, S.E., M.M.', 'penyelenggara' => 'ICOMAN', 'tanggal' => '2025-11-20'],
            ['judul' => 'Juara 2 Lomba Debat Ekonomi Regional', 'kategori' => 'mahasiswa', 'tingkat' => 'regional', 'peraih' => 'Mahasiswa Angkatan 2023', 'penyelenggara' => 'BEM FEB se-Sulawesi', 'tanggal' => '2026-02-10'],
            ['judul' => 'Akreditasi Unggul Program Studi', 'kategori' => 'prodi', 'tingkat' => 'nasional', 'peraih' => 'Program Studi Manajemen', 'penyelenggara' => 'BAN-PT / LAMEMBA', 'tanggal' => '2025-09-01'],
        ];

        foreach ($items as $item) {
            Prestasi::firstOrCreate(
                ['judul' => $item['judul']],
                array_merge($item, ['status' => 'published', 'deskripsi' => 'Deskripsi prestasi (contoh — silakan ubah melalui panel admin).']),
            );
        }
    }
}
