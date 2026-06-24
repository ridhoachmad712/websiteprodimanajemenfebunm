<?php

namespace Database\Seeders;

use App\Models\Mitra;
use Illuminate\Database\Seeder;

class MitraSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            'Bank Indonesia', 'Otoritas Jasa Keuangan', 'Bursa Efek Indonesia',
            'Kadin Sulsel', 'PT Semen Tonasa', 'Bank Sulselbar',
        ];

        foreach ($items as $i => $nama) {
            Mitra::firstOrCreate(
                ['nama' => $nama],
                ['logo' => null, 'url' => null, 'urutan' => $i + 1, 'aktif' => true],
            );
        }
    }
}
