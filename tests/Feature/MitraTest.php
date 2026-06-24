<?php

namespace Tests\Feature;

use App\Models\Mitra;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class MitraTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cannot_access_admin_mitra(): void
    {
        $this->get('/admin/mitra')->assertRedirect('/login');
    }

    public function test_admin_can_create_mitra(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/mitra')->assertOk();
        $this->actingAs($admin)->get('/admin/mitra/create')->assertOk();

        $this->actingAs($admin)->post('/admin/mitra', [
            'nama' => 'Bank Indonesia',
            'url' => 'https://bi.go.id',
            'urutan' => 1,
            'aktif' => '1',
        ])->assertRedirect(route('admin.mitra.index'));

        $this->assertDatabaseHas('mitra', ['nama' => 'Bank Indonesia', 'aktif' => true]);
    }

    public function test_homepage_mitra_block_shows_active_partners_only(): void
    {
        Mitra::create(['nama' => 'Mitra Aktif', 'aktif' => true, 'urutan' => 1]);
        Mitra::create(['nama' => 'Mitra Nonaktif', 'aktif' => false, 'urutan' => 2]);

        Setting::set('home.blocks', json_encode([
            ['type' => 'mitra', 'enabled' => true, 'data' => ['title' => 'Mitra & Kerjasama']],
        ]), 'home');

        $this->get('/')
            ->assertOk()
            ->assertSee('Mitra Aktif')
            ->assertDontSee('Mitra Nonaktif');
    }
}
