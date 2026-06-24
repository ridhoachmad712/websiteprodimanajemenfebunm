<?php

namespace Database\Seeders;

use App\Models\Pengumuman;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class PengumumanSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['judul' => 'Pelaksanaan Semester Pendek 2026', 'offsetDays' => -2],
            ['judul' => 'Jadwal Pendaftaran Ujian Proposal Periode Genap', 'offsetDays' => -5],
            ['judul' => 'Pengumuman Libur Hari Raya', 'offsetDays' => -10],
        ];

        foreach ($items as $item) {
            Pengumuman::firstOrCreate(
                ['slug' => Pengumuman::generateSlug($item['judul'])],
                [
                    'judul' => $item['judul'],
                    'konten' => '<p>Isi pengumuman (contoh — silakan ubah melalui panel admin).</p>',
                    'status' => 'published',
                    'published_at' => Carbon::now()->addDays($item['offsetDays']),
                ],
            );
        }
    }
}
