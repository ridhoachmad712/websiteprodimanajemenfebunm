<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StatBandTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_renders_animated_stats_band_from_defaults(): void
    {
        $this->get('/')
            ->assertOk()
            ->assertSee('stat-band', false)
            ->assertSee('Mahasiswa Aktif')
            ->assertSee('data-count="1200"', false) // angka → dianimasikan
            ->assertSee('Unggul');                  // teks → tampil apa adanya
    }

    public function test_admin_can_save_a_statistik_block(): void
    {
        Setting::set('home.blocks', json_encode([]), 'home'); // mulai kosong

        $this->actingAs(User::factory()->create())->put(route('admin.home.update'), [
            'blocks' => [
                ['type' => 'statistik', 'enabled' => '1', 'data' => [
                    'title' => 'Capaian Kami', 'style' => 'dark',
                    'items' => [
                        ['icon' => 'ti-users', 'value' => '900', 'suffix' => '+', 'label' => 'Mahasiswa'],
                    ],
                ]],
            ],
        ])->assertRedirect();

        $this->get('/')->assertOk()->assertSee('Capaian Kami')->assertSee('data-count="900"', false);
    }
}
