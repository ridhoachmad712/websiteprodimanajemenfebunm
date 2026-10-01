<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\Setting;
use App\Models\User;
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

    public function test_konsentrasi_subgroup_order_is_consistent_across_refreshes(): void
    {
        foreach (['Manajemen Keuangan', 'Manajemen SDM', 'Manajemen Pemasaran'] as $i => $k) {
            Dosen::create(['nama' => "Dosen {$i}", 'slug' => "dosen-{$i}", 'kategori' => 'tetap_prodi', 'konsentrasi' => $k, 'urutan' => $i + 1]);
        }

        foreach (range(1, 3) as $refresh) {
            $this->get('/daftar-dosen')->assertOk()
                ->assertSeeInOrder(['Manajemen SDM', 'Manajemen Pemasaran', 'Manajemen Keuangan']);
        }
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

    public function test_admin_can_toggle_public_concentration_grouping(): void
    {
        Dosen::create(['nama' => 'Dosen SDM', 'slug' => 'sdm', 'kategori' => 'tetap_prodi', 'konsentrasi' => 'Manajemen SDM', 'urutan' => 1]);
        $admin = User::factory()->create();

        $this->actingAs($admin)->put(route('admin.dosen.grouping'), ['enabled' => '0'])
            ->assertRedirect(route('admin.dosen.index'));
        $this->assertSame('0', Setting::get('dosen.group_by_concentration'));
        $this->get('/daftar-dosen')->assertOk()->assertSee('Dosen SDM')
            ->assertDontSee('dosen-subgroup-title');

        $this->put(route('admin.dosen.grouping'), ['enabled' => '1'])
            ->assertRedirect(route('admin.dosen.index'));
        $this->get('/daftar-dosen')->assertOk()->assertSee('dosen-subgroup-title');
    }

    public function test_guest_cannot_change_concentration_grouping(): void
    {
        $this->put(route('admin.dosen.grouping'), ['enabled' => '0'])->assertRedirect('/login');
    }
}
