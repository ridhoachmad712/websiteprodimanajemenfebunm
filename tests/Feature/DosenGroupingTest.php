<?php

namespace Tests\Feature;

use App\Models\Dosen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DosenGroupingTest extends TestCase
{
    use RefreshDatabase;

    public function test_tetap_prodi_is_subgrouped_by_konsentrasi(): void
    {
        Dosen::create(['nama' => 'Dosen Keuangan', 'slug' => 'dk', 'kategori' => 'tetap_prodi', 'konsentrasi' => 'Manajemen Keuangan', 'urutan' => 1]);
        Dosen::create(['nama' => 'Dosen Sdm', 'slug' => 'ds', 'kategori' => 'tetap_prodi', 'konsentrasi' => 'Manajemen SDM', 'urutan' => 2]);

        $this->get('/daftar-dosen')
            ->assertOk()
            ->assertSee('Manajemen SDM')
            ->assertSee('Manajemen Keuangan')
            ->assertSee('Dosen Sdm')
            ->assertSee('Dosen Keuangan');
    }

    public function test_konsentrasi_subgroup_order_is_randomized_across_refreshes(): void
    {
        // Banyak konsentrasi supaya peluang urutan berbeda antar-muat tinggi.
        foreach (['Manajemen SDM', 'Manajemen Pemasaran', 'Manajemen Keuangan'] as $i => $k) {
            Dosen::create(['nama' => "Dosen {$i}", 'slug' => "dosen-{$i}", 'kategori' => 'tetap_prodi', 'konsentrasi' => $k, 'urutan' => $i + 1]);
        }

        // Ambil urutan kemunculan sub-judul konsentrasi pada beberapa kali muat.
        $urutan = collect(range(1, 12))->map(function () {
            $html = $this->get('/daftar-dosen')->getContent();
            $pos = collect(['Manajemen SDM', 'Manajemen Pemasaran', 'Manajemen Keuangan'])
                ->mapWithKeys(fn ($k) => [$k => strpos($html, $k)])
                ->sort()->keys()->implode('|');

            return $pos;
        })->unique();

        // Diacak → mustahil (praktis) semua 12 muat menghasilkan urutan yang sama.
        $this->assertGreaterThan(1, $urutan->count(), 'Urutan konsentrasi seharusnya berubah-ubah antar refresh');
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
