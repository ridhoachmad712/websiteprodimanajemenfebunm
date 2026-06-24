<?php

namespace Tests\Feature;

use App\Models\Pengumuman;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AnnouncementBarTest extends TestCase
{
    use RefreshDatabase;

    public function test_banner_shows_when_a_published_pengumuman_is_flagged(): void
    {
        Pengumuman::create([
            'judul' => 'Banner Penting',
            'slug' => 'banner-penting',
            'konten' => '<p>x</p>',
            'status' => 'published',
            'sorot' => true,
            'published_at' => now()->subDay(),
        ]);

        $this->get('/')
            ->assertOk()
            ->assertSee('announcementBar', false)
            ->assertSee('Banner Penting');
    }

    public function test_no_banner_when_none_flagged(): void
    {
        Pengumuman::create([
            'judul' => 'Biasa Saja',
            'slug' => 'biasa-saja',
            'konten' => '<p>x</p>',
            'status' => 'published',
            'sorot' => false,
            'published_at' => now()->subDay(),
        ]);

        $this->get('/')->assertOk()->assertDontSee('announcementBar', false);
    }

    public function test_draft_flagged_pengumuman_does_not_show_as_banner(): void
    {
        Pengumuman::create([
            'judul' => 'Draf Banner',
            'slug' => 'draf-banner',
            'konten' => '<p>x</p>',
            'status' => 'draft',
            'sorot' => true,
            'published_at' => null,
        ]);

        $this->get('/')->assertOk()->assertDontSee('Draf Banner');
    }
}
