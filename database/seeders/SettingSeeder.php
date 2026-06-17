<?php

namespace Database\Seeders;

use App\Models\Setting;
use Illuminate\Database\Seeder;

class SettingSeeder extends Seeder
{
    public function run(): void
    {
        $settings = [
            // Identitas & tagline
            ['key' => 'site.nama',          'value' => 'Program Studi Manajemen FEB UNM', 'group' => 'umum'],
            ['key' => 'site.tagline',       'value' => 'Forever in Brotherhood',          'group' => 'umum'],
            ['key' => 'site.tagline_brand', 'value' => 'Build – Manage – Integrate',       'group' => 'umum'],

            // Kontak
            ['key' => 'kontak.alamat',  'value' => 'Kampus Gunung Sari, Jl. A.P Pettarani – Jl. Pendidikan, Makassar', 'group' => 'kontak'],
            ['key' => 'kontak.telepon', 'value' => '082 293 000 192',        'group' => 'kontak'],
            ['key' => 'kontak.email',   'value' => 'manajemen_fe@unm.ac.id', 'group' => 'kontak'],

            // Media sosial
            ['key' => 'sosmed.instagram', 'value' => 'https://www.instagram.com/manajemen.febunm/', 'group' => 'sosmed'],
            ['key' => 'sosmed.tiktok',    'value' => 'https://www.tiktok.com/@manajemenfebunm',     'group' => 'sosmed'],
            ['key' => 'sosmed.facebook',  'value' => '',                                            'group' => 'sosmed'],

            // Statistik beranda
            ['key' => 'statistik.mahasiswa', 'value' => '2000', 'group' => 'statistik'],
            ['key' => 'statistik.dosen',     'value' => '60',   'group' => 'statistik'],
        ];

        foreach ($settings as $row) {
            Setting::updateOrCreate(['key' => $row['key']], $row);
        }
    }
}
