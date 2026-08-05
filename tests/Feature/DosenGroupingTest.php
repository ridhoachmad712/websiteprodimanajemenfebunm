<?php

namespace Tests\Feature;

use App\Models\Dosen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DosenGroupingTest extends TestCase
{
    use RefreshDatabase;

    public function test_tetap_prodi_is_subgrouped_by_konsentrasi_in_map_order(): void
    {
        // Dibuat urut Keuangan dulu, lalu SDM — tapi tampilan harus mengikuti
        // urutan konsentrasiMap: SDM sebelum Keuangan.
        Dosen::create(['nama' => 'Dosen Keuangan', 'slug' => 'dk', 'kategori' => 'tetap_prodi', 'konsentrasi' => 'Manajemen Keuangan', 'urutan' => 1]);
        Dosen::create(['nama' => 'Dosen Sdm', 'slug' => 'ds', 'kategori' => 'tetap_prodi', 'konsentrasi' => 'Manajemen SDM', 'urutan' => 2]);

        $html = $this->get('/daftar-dosen')->assertOk()->getContent();

        $this->assertStringContainsString('Manajemen SDM', $html);
        $this->assertStringContainsString('Manajemen Keuangan', $html);
        $this->assertStringContainsString('Dosen Sdm', $html);
        $this->assertStringContainsString('Dosen Keuangan', $html);

        // Sub-kelompok SDM tampil sebelum Keuangan (mengikuti konsentrasiMap).
        $this->assertLessThan(
            strpos($html, 'Manajemen Keuangan'),
            strpos($html, 'Manajemen SDM'),
            'Sub-kelompok SDM harus tampil sebelum Keuangan'
        );
    }

    public function test_luar_biasa_is_subgrouped_by_konsentrasi(): void
    {
        Dosen::create(['nama' => 'LB Pemasaran', 'slug' => 'lbp', 'kategori' => 'luar_biasa', 'konsentrasi' => 'Manajemen Pemasaran', 'urutan' => 1]);

        $this->get('/daftar-dosen')
            ->assertOk()
            ->assertSee('Dosen Luar Biasa')
            ->assertSee('Manajemen Pemasaran')
            ->assertSee('LB Pemasaran');
    }
}
