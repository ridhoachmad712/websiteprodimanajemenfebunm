<?php

namespace Tests\Feature;

use App\Models\Setting;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_homepage_composition_uses_admin_content_and_links(): void
    {
        Setting::set('home.blocks', json_encode([
            ['type' => 'features', 'enabled' => true, 'data' => [
                'title' => 'Pilar Belajar',
                'items' => [['icon' => 'ti-tools', 'title' => 'Praktik', 'desc' => 'Belajar melalui studi kasus.']],
            ]],
            ['type' => 'about', 'enabled' => true, 'data' => [
                'title' => 'Tentang Program',
                'body' => 'Kegiatan akademik dan pengabdian.',
                'button_label' => 'Baca Profil',
                'button_url' => '/profil',
            ]],
            ['type' => 'cta', 'enabled' => true, 'data' => [
                'title' => 'Kenali Program Studi',
                'btn1_label' => 'Lihat Profil',
                'btn1_url' => '/profil',
            ]],
        ]), 'home');

        $this->get('/')
            ->assertOk()
            ->assertSee('home-pillars-layout', false)
            ->assertSee('Pilar Belajar')
            ->assertSee('Belajar melalui studi kasus.')
            ->assertSee('home-about-details', false)
            ->assertSee('Kegiatan akademik dan pengabdian.')
            ->assertSee('href="/profil"', false)
            ->assertSee('home-cta-layout', false)
            ->assertSee('Kenali Program Studi');
    }
}
