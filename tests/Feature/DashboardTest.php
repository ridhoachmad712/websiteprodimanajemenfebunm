<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DashboardTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_dashboard_shows_popular_and_activity_sections(): void
    {
        $admin = User::factory()->create();
        Post::create([
            'judul' => 'Berita Populer', 'slug' => 'berita-populer', 'konten' => '<p>x</p>',
            'user_id' => $admin->id, 'status' => 'published', 'published_at' => now()->subDay(), 'dilihat' => 99,
        ]);

        $this->actingAs($admin)->get('/admin')
            ->assertOk()
            ->assertSee('Berita Terpopuler')
            ->assertSee('Berita Populer')
            ->assertSee('Aktivitas Terbaru')
            ->assertSee('Ringkasan Konten')
            ->assertSee('Pengaturan'); // aksi cepat admin-only
    }

    public function test_editor_dashboard_hides_admin_only_sections(): void
    {
        $editor = User::factory()->editor()->create();

        $this->actingAs($editor)->get('/admin')
            ->assertOk()
            ->assertSee('Berita Terpopuler')
            ->assertDontSee('Aktivitas Terbaru')
            ->assertDontSee('Susun Beranda');
    }
}
