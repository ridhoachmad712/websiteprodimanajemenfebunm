<?php

namespace Tests\Feature;

use App\Models\Pengumuman;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PengumumanTest extends TestCase
{
    use RefreshDatabase;

    private function buat(array $attr = []): Pengumuman
    {
        return Pengumuman::create(array_merge([
            'judul' => 'Pengumuman Uji',
            'slug' => 'pengumuman-uji',
            'konten' => '<p>Isi pengumuman.</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
        ], $attr));
    }

    public function test_public_index_lists_published_and_hides_draft(): void
    {
        $this->buat(['judul' => 'Terbit', 'slug' => 'terbit']);
        $this->buat(['judul' => 'Draf', 'slug' => 'draf', 'status' => 'draft', 'published_at' => null]);

        $this->get('/pengumuman')
            ->assertOk()
            ->assertSee('Terbit')
            ->assertDontSee('Draf');
    }

    public function test_detail_page_renders_published(): void
    {
        $p = $this->buat(['judul' => 'Detail Pengumuman', 'slug' => 'detail-pengumuman']);

        $this->get('/pengumuman/detail-pengumuman')
            ->assertOk()
            ->assertSee('Detail Pengumuman')
            ->assertSee('Isi pengumuman.', false);
    }

    public function test_draft_detail_returns_404_for_guests(): void
    {
        $this->buat(['slug' => 'draf-detail', 'status' => 'draft', 'published_at' => null]);

        $this->get('/pengumuman/draf-detail')->assertNotFound();
    }

    public function test_guest_cannot_access_admin_pengumuman(): void
    {
        $this->get('/admin/pengumuman')->assertRedirect('/login');
    }

    public function test_admin_can_create_pengumuman_and_it_is_not_a_post(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/pengumuman/create')->assertOk();

        $this->actingAs($admin)->post('/admin/pengumuman', [
            'judul' => 'Pengumuman Baru',
            'konten' => '<p>Konten pengumuman baru.</p>',
            'status' => 'published',
        ])->assertRedirect(route('admin.pengumuman.index'));

        $this->assertDatabaseHas('pengumuman', ['judul' => 'Pengumuman Baru']);
        // Pastikan TIDAK masuk ke tabel posts (benar-benar terpisah dari Berita).
        $this->assertDatabaseMissing('posts', ['judul' => 'Pengumuman Baru']);
    }
}
