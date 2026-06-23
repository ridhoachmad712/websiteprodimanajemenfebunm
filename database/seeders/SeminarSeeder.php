<?php

namespace Database\Seeders;

use App\Models\Seminar;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class SeminarSeeder extends Seeder
{
    public function run(): void
    {
        $items = [
            ['nama' => 'Andi Pratama', 'nim' => '210901001', 'jenis' => 'proposal', 'offsetDays' => 7, 'tempat' => 'Ruang Sidang 1'],
            ['nama' => 'Siti Nurhaliza', 'nim' => '210901002', 'jenis' => 'hasil', 'offsetDays' => 10, 'tempat' => 'Ruang Sidang 2'],
            ['nama' => 'Budi Santoso', 'nim' => '200901010', 'jenis' => 'tutup', 'offsetDays' => -14, 'tempat' => 'Ruang Sidang 1'],
        ];

        foreach ($items as $item) {
            Seminar::firstOrCreate(
                ['nama' => $item['nama'], 'jenis' => $item['jenis']],
                [
                    'nim' => $item['nim'],
                    'judul' => 'Analisis Pengaruh Variabel X terhadap Variabel Y pada Studi Kasus (contoh).',
                    'tanggal' => Carbon::now()->addDays($item['offsetDays'])->setTime(9, 0),
                    'tempat' => $item['tempat'],
                    'pembimbing' => 'Dr. Pembimbing Satu, S.E., M.M. & Pembimbing Dua, S.E., M.Si.',
                    'penguji' => 'Penguji Satu, S.E., M.M.',
                    'status' => 'published',
                ],
            );
        }
    }
}
