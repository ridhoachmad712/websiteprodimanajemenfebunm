<?php

namespace Database\Seeders;

use App\Models\Dosen;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DosenSeeder extends Seeder
{
    public function run(): void
    {
        // Contoh data lintas 4 kategori (akan dilengkapi/diimpor dari situs lama).
        $data = [
            ['nama' => 'Dr. Anwar, S.E., M.Si.',            'kategori' => 'tetap_prodi', 'konsentrasi' => 'Manajemen SDM',       'nip' => '197001012000031001'],
            ['nama' => 'Prof. Dr. Muhammad Idris, M.Si.',   'kategori' => 'guru_besar',  'konsentrasi' => 'Manajemen Pemasaran'],
            ['nama' => 'Prof. Dr. Salmiah Said, S.E., M.Si.','kategori' => 'guru_besar', 'konsentrasi' => 'Manajemen Keuangan'],
            ['nama' => 'Romansyah Sahabuddin, S.E., M.Si.', 'kategori' => 'tetap_prodi', 'konsentrasi' => 'Manajemen Pemasaran'],
            ['nama' => 'Nurman, S.E., M.Si.',               'kategori' => 'tetap_prodi', 'konsentrasi' => 'Manajemen Keuangan'],
            ['nama' => 'Drs. H. Ahmad, M.Pd.',              'kategori' => 'mkdu',        'konsentrasi' => 'Pendidikan Agama Islam'],
            ['nama' => 'Dra. Hj. Rahmawati, M.Hum.',        'kategori' => 'mkdu',        'konsentrasi' => 'Bahasa Indonesia'],
            ['nama' => 'Andi Tenri, S.E., M.M.',            'kategori' => 'luar_biasa',  'konsentrasi' => 'Manajemen SDM'],
        ];

        foreach ($data as $i => $row) {
            Dosen::updateOrCreate(
                ['slug' => Str::slug($row['nama'])],
                array_merge($row, ['urutan' => $i + 1]),
            );
        }
    }
}
