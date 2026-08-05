<?php

namespace Tests\Feature;

use App\Models\HeroSlide;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class HeroSliderTest extends TestCase
{
    use RefreshDatabase;

    public function test_home_shows_carousel_when_active_slides_exist(): void
    {
        HeroSlide::create(['gambar' => 'hero/a.jpg', 'judul' => 'Selamat Datang', 'aktif' => true, 'urutan' => 1]);

        $this->get('/')
            ->assertOk()
            ->assertSee('heroCarousel', false)
            ->assertSee('Selamat Datang');
    }

    public function test_home_hides_carousel_when_no_slides(): void
    {
        $this->get('/')->assertOk()->assertDontSee('heroCarousel', false);
    }

    public function test_inactive_slides_are_not_shown(): void
    {
        HeroSlide::create(['gambar' => 'hero/b.jpg', 'judul' => 'Slide Nonaktif', 'aktif' => false, 'urutan' => 1]);

        $this->get('/')->assertOk()->assertDontSee('Slide Nonaktif')->assertDontSee('heroCarousel', false);
    }

    public function test_admin_can_create_slide_editor_forbidden(): void
    {
        Storage::fake('public');

        $this->actingAs(User::factory()->create())->post('/admin/hero-slides', [
            'gambar' => UploadedFile::fake()->image('slide.jpg', 1200, 500),
            'judul' => 'Slide A',
            'aktif' => '1',
            'urutan' => 1,
        ])->assertRedirect(route('admin.hero-slides.index'));

        $this->assertDatabaseHas('hero_slides', ['judul' => 'Slide A', 'aktif' => true]);

        $this->actingAs(User::factory()->editor()->create())->get('/admin/hero-slides')->assertForbidden();
    }

    public function test_guest_cannot_manage_slides(): void
    {
        $this->get('/admin/hero-slides')->assertRedirect('/login');
    }
}
