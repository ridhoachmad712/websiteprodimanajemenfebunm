<?php

namespace Tests\Feature;

use App\Models\BeritaEksternal;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BeritaEksternalTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_table_lists_published_and_hides_draft(): void
    {
        BeritaEksternal::create(['judul' => 'Liputan Terbit', 'sumber' => 'Tribun Timur', 'url' => 'https://example.com/a', 'tanggal' => '2026-01-10', 'status' => 'published']);
        BeritaEksternal::create(['judul' => 'Liputan Draft', 'sumber' => 'Detik', 'url' => 'https://example.com/b', 'tanggal' => '2026-01-11', 'status' => 'draft']);

        $this->get('/berita-eksternal')
            ->assertOk()
            ->assertSee('Liputan Terbit')
            ->assertSee('Tribun Timur')
            ->assertSee('https://example.com/a', false)
            ->assertDontSee('Liputan Draft');
    }

    public function test_search_filters_by_media_source(): void
    {
        BeritaEksternal::create(['judul' => 'A', 'sumber' => 'Kompas', 'url' => 'https://example.com/a', 'status' => 'published']);
        BeritaEksternal::create(['judul' => 'B', 'sumber' => 'Antara', 'url' => 'https://example.com/b', 'status' => 'published']);

        $this->get('/berita-eksternal?cari=Kompas')
            ->assertOk()
            ->assertSee('Kompas')
            ->assertDontSee('Antara');
    }

    public function test_guest_cannot_access_admin(): void
    {
        $this->get('/admin/berita-eksternal')->assertRedirect('/login');
    }

    public function test_admin_can_create_and_url_is_required_valid(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/berita-eksternal/create')->assertOk();

        // URL wajib & harus valid
        $this->actingAs($admin)->post('/admin/berita-eksternal', [
            'judul' => 'Tanpa URL', 'sumber' => 'X', 'url' => 'bukan-url', 'status' => 'published',
        ])->assertSessionHasErrors('url');

        $this->actingAs($admin)->post('/admin/berita-eksternal', [
            'judul' => 'Liputan Baru', 'sumber' => 'Tribun Timur',
            'url' => 'https://tribun.com/berita', 'tanggal' => '2026-05-01', 'status' => 'published',
        ])->assertRedirect(route('admin.berita-eksternal.index'));

        $this->assertDatabaseHas('berita_eksternal', ['judul' => 'Liputan Baru', 'sumber' => 'Tribun Timur']);
    }
}
