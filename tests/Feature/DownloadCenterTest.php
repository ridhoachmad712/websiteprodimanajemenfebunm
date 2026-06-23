<?php

namespace Tests\Feature;

use App\Models\Download;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class DownloadCenterTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_page_groups_published_downloads_and_hides_draft(): void
    {
        Download::create(['judul' => 'RPS Keuangan', 'kategori' => 'RPS', 'url' => 'https://example.com/a', 'status' => 'published']);
        Download::create(['judul' => 'Dokumen Rahasia', 'kategori' => 'Internal', 'url' => 'https://example.com/b', 'status' => 'draft']);

        $this->get('/unduhan')
            ->assertOk()
            ->assertSee('RPS Keuangan')
            ->assertSee('RPS')
            ->assertDontSee('Dokumen Rahasia');
    }

    public function test_guest_cannot_access_admin_downloads(): void
    {
        $this->get('/admin/downloads')->assertRedirect('/login');
    }

    public function test_admin_can_create_a_download_with_an_external_link(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->get('/admin/downloads/create')->assertOk();

        $this->actingAs($admin)->post('/admin/downloads', [
            'judul' => 'Formulir Skripsi',
            'kategori' => 'Formulir',
            'url' => 'https://example.com/form',
            'urutan' => 1,
            'status' => 'published',
        ])->assertRedirect(route('admin.downloads.index'));

        $this->assertDatabaseHas('downloads', ['judul' => 'Formulir Skripsi', 'kategori' => 'Formulir']);
    }

    public function test_url_must_be_valid(): void
    {
        $admin = User::factory()->create();

        $this->actingAs($admin)->post('/admin/downloads', [
            'judul' => 'Bad',
            'kategori' => 'X',
            'url' => 'bukan-url',
            'status' => 'published',
        ])->assertSessionHasErrors('url');
    }
}
