<?php

namespace Tests\Feature;

use App\Models\Setting;
use App\Models\Dosen;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class HomepageLayoutTest extends TestCase
{
    use RefreshDatabase;

    public function test_lecturer_carousel_contains_every_lecturer_in_one_track(): void
    {
        Setting::set('home.blocks', json_encode([
            ['type' => 'dosen', 'enabled' => true, 'data' => ['title' => 'Dosen', 'count' => '2']],
        ]), 'home');
        foreach (range(1, 5) as $number) {
            Dosen::create([
                'nama' => 'Dosen '.$number,
                'slug' => 'dosen-'.$number,
                'kategori' => 'tetap_prodi',
                'jabatan' => $number === 1 ? 'Ketua Program Studi' : null,
                'urutan' => $number,
            ]);
        }

        $response = $this->get('/')->assertOk()
            ->assertSee('dosen-marquee', false)
            ->assertSee('dosen-track', false)
            ->assertSee('dosen-role-overlay', false)
            ->assertDontSee('data-dosen-next', false);
        foreach (range(1, 5) as $number) {
            $response->assertSee('Dosen '.$number);
        }
    }

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
