<?php

namespace Database\Seeders;

use App\Models\Menu;
use Illuminate\Database\Seeder;

class MenuSeeder extends Seeder
{
    public function run(): void
    {
        // Struktur menu utama situs (mengikuti analisa situs lama).
        // Format: [title, url, target, children[]]
        $tree = [
            ['Beranda', '/', '_self', []],
            ['Berita', '/berita', '_self', []],
            ['Profil', null, '_self', [
                ['Profil Program Studi', '/profil', '_self', []],
                ['Daftar Dosen', '/daftar-dosen', '_self', []],
                ['Peta Proses Bisnis', '/sop-petaprosesbisnis', '_self', []],
                ['Akreditasi', '/akreditasi', '_self', []],
                ['Fasilitas', '/fasilitas', '_self', []],
                ['Galeri', '/gallery', '_self', []],
            ]],
            ['Akademik', null, '_self', [
                ['Kurikulum', '/kurikulum', '_self', []],
                ['Kalender Akademik', '/kalender-akademik', '_self', []],
                ['Jadwal Ujian', '/jadwal-ujian', '_self', []],
                ['Daftar Seminar', '/daftar-seminar', '_self', []],
                ['Prestasi', '/prestasi', '_self', []],
            ]],
            ['Kemahasiswaan', null, '_self', [
                ['HIMA Manajemen', '/hima', '_self', []],
                ['KMM Asy Asyaamil', '#', '_self', []],
            ]],
            ['Alumni', null, '_self', [
                ['Direktori Alumni', '/alumni', '_self', []],
                ['Tracer Study Universitas', 'https://tracerstudy.unm.ac.id', '_blank', []],
            ]],
            ['Jurnal', null, '_self', [
                ['ICOMAN 2025', '/icoman2025', '_self', []],
                ['Jurnal (OJS)', 'https://ojs.unm.ac.id/manajemen', '_blank', []],
            ]],
            ['Download', null, '_self', [
                ['SK Mengajar', '#', '_self', []],
                ['RPS', '#', '_self', []],
            ]],
            ['Hubungi Kami', '/hubungi-kami', '_self', []],
        ];

        // Reset agar idempoten saat seed ulang.
        Menu::query()->delete();

        foreach ($tree as $i => [$title, $url, $target, $children]) {
            $this->createNode($title, $url, $target, $i + 1, null, $children);
        }
    }

    private function createNode(string $title, ?string $url, string $target, int $urutan, ?int $parentId, array $children): void
    {
        $menu = Menu::create([
            'parent_id' => $parentId,
            'title' => $title,
            'url' => $url,
            'target' => $target,
            'urutan' => $urutan,
            'aktif' => true,
        ]);

        foreach ($children as $j => [$cTitle, $cUrl, $cTarget, $cChildren]) {
            $this->createNode($cTitle, $cUrl, $cTarget, $j + 1, $menu->id, $cChildren);
        }
    }
}
