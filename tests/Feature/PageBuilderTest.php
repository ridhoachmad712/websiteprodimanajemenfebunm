<?php

namespace Tests\Feature;

use App\Models\Menu;
use App\Models\Page;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PageBuilderTest extends TestCase
{
    use RefreshDatabase;

    private function admin(): User
    {
        return User::factory()->create();
    }

    public function test_guest_cannot_open_the_page_builder(): void
    {
        $this->get('/admin/pages/create')->assertRedirect('/login');
    }

    public function test_admin_can_open_the_builder_and_edit_screens(): void
    {
        $admin = $this->admin();

        // Form "Tambah Halaman" (merender pemilih template + semua panel).
        $this->actingAs($admin)->get('/admin/pages/create')
            ->assertOk()
            ->assertSee('Landing Page')
            ->assertSee('FAQ');

        // Editor konten tunggal.
        $content = Page::create(['title' => 'A', 'slug' => 'a', 'content' => '<p>x</p>', 'status' => 'published']);
        $this->actingAs($admin)->get(route('admin.pages.edit', $content))->assertOk();

        // Editor berbasis template (merender admin-page-edit-template + fields).
        $faq = Page::create([
            'title' => 'B', 'slug' => 'b', 'status' => 'published',
            'sections' => ['_template' => 'faq', '_data' => ['items' => [['question' => 'Q', 'answer' => 'A']]]],
        ]);
        $this->actingAs($admin)->get(route('admin.pages.edit', $faq))->assertOk();
    }

    public function test_admin_can_create_a_content_page_that_renders_publicly(): void
    {
        $response = $this->actingAs($this->admin())->post('/admin/pages', [
            'title' => 'Tentang Layanan',
            'slug' => 'tentang-layanan',
            'template' => 'content',
            'status' => 'published',
            'content' => '<p>Halaman konten bebas.</p>',
        ]);

        $page = Page::where('slug', 'tentang-layanan')->first();
        $this->assertNotNull($page);
        $response->assertRedirect(route('admin.pages.edit', $page));

        $this->get('/halaman/tentang-layanan')
            ->assertOk()
            ->assertSee('Halaman konten bebas.', false);
    }

    public function test_admin_can_create_a_faq_template_page(): void
    {
        $this->actingAs($this->admin())->post('/admin/pages', [
            'title' => 'FAQ Akademik',
            'slug' => 'faq-akademik',
            'template' => 'faq',
            'status' => 'published',
            'template_data' => [
                'intro' => '<p>Pertanyaan umum.</p>',
                'items' => [
                    ['question' => 'Bagaimana cara mendaftar ujian?', 'answer' => '<p>Melalui portal.</p>'],
                    ['question' => '', 'answer' => ''], // baris kosong harus diabaikan
                ],
            ],
        ])->assertRedirect();

        $page = Page::where('slug', 'faq-akademik')->firstOrFail();
        $this->assertSame('faq', $page->template());
        $this->assertCount(1, $page->sections['_data']['items']);

        $this->get('/halaman/faq-akademik')
            ->assertOk()
            ->assertSee('Bagaimana cara mendaftar ujian?');
    }

    public function test_draft_pages_are_hidden_from_the_public(): void
    {
        Page::create([
            'title' => 'Rahasia',
            'slug' => 'rahasia',
            'content' => '<p>belum terbit</p>',
            'status' => 'draft',
        ]);

        $this->get('/halaman/rahasia')->assertNotFound();
    }

    public function test_add_to_menu_creates_a_navigation_entry(): void
    {
        $this->actingAs($this->admin())->post('/admin/pages', [
            'title' => 'Beasiswa',
            'slug' => 'beasiswa',
            'template' => 'content',
            'status' => 'published',
            'content' => '<p>Info beasiswa.</p>',
            'add_to_menu' => '1',
        ])->assertRedirect();

        $this->assertTrue(Menu::where('url', '/halaman/beasiswa')->exists());
    }

    public function test_core_pages_cannot_be_deleted(): void
    {
        $page = Page::create(['title' => 'Profil', 'slug' => 'profil', 'content' => 'x', 'status' => 'published']);

        $this->actingAs($this->admin())
            ->delete(route('admin.pages.destroy', $page))
            ->assertSessionHas('error');

        $this->assertDatabaseHas('pages', ['slug' => 'profil']);
    }
}
