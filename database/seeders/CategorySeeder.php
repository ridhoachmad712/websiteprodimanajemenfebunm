<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $kategori = [
            'Berita & Informasi',
            'Artikel',
            'Prestasi',
            'Pengumuman',
        ];

        foreach ($kategori as $nama) {
            Category::updateOrCreate(
                ['slug' => Str::slug($nama)],
                ['nama' => $nama],
            );
        }
    }
}
