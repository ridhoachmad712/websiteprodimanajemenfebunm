<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Pengumuman;
use App\Models\Post;
use App\Models\Setting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class FrontendModernTest extends TestCase
{
    use RefreshDatabase;

    public function test_public_theme_keeps_admin_content_and_accessibility(): void
    {
        Setting::set('hero.title', 'Judul Dari Admin', 'hero');
        $this->get('/')->assertOk()->assertSee('Judul Dari Admin')
            ->assertSee('frontend-modern.css')->assertSee('frontend-modern.js')
            ->assertSee('accessibilityToggle')->assertSee('siteSearchPanel');
        $this->actingAs(User::factory()->create())->get('/admin')
            ->assertOk()->assertDontSee('frontend-modern.css');
    }

    public function test_news_search_category_and_pagination_preserve_publication_rules(): void
    {
        $user = User::factory()->create();
        $category = Category::create(['nama' => 'Kegiatan', 'slug' => 'kegiatan']);
        foreach (range(1, 12) as $index) {
            $post = Post::create(['judul' => 'Riset '.$index, 'slug' => 'riset-'.$index,
                'konten' => '<p>Isi artikel</p>', 'status' => 'published',
                'published_at' => now()->subDays($index), 'user_id' => $user->id]);
            $post->categories()->attach($category);
        }
        Post::create(['judul' => 'Riset Rahasia', 'slug' => 'rahasia', 'konten' => 'Draft',
            'status' => 'draft', 'published_at' => now()->subDay(), 'user_id' => $user->id]);
        Post::create(['judul' => 'Riset Mendatang', 'slug' => 'mendatang', 'konten' => 'Terjadwal',
            'status' => 'published', 'published_at' => now()->addDay(), 'user_id' => $user->id]);

        $this->get('/berita?q=Riset&category=kegiatan')->assertOk()
            ->assertSee('12 berita')->assertDontSee('Riset Rahasia')->assertDontSee('Riset Mendatang')
            ->assertSee('category=kegiatan', false)->assertSee('page=2', false);
        $this->get('/berita?q=Riset&category=kegiatan&page=2')->assertOk()->assertSee('Riset 12');
        $this->get('/berita?q=TidakAda')->assertOk()->assertSee('Tidak ada berita');
        $this->get('/berita?q[]=bad')->assertSessionHasErrors('q');
    }

    public function test_news_detail_uses_compact_breadcrumb_and_aligned_article_layout(): void
    {
        $post = Post::create([
            'judul' => 'Ketua Prodi Menyampaikan Sambutan dalam Konferensi Internasional',
            'slug' => 'sambutan-konferensi-internasional',
            'konten' => '<p>Isi artikel</p>',
            'status' => 'published',
            'published_at' => now()->subDay(),
            'user_id' => User::factory()->create()->id,
        ]);

        $this->get($post->url())->assertOk()
            ->assertSee('page-hero--article', false)
            ->assertSee('aria-current="page">Artikel</li>', false)
            ->assertSee('class="article-shell"', false)
            ->assertSee('class="article-meta text-secondary"', false);
    }

    public function test_announcement_search_hides_drafts(): void
    {
        foreach (['published', 'draft'] as $status) {
            Pengumuman::create(['judul' => 'Ujian '.$status, 'slug' => 'ujian-'.$status,
                'konten' => '<p>Informasi ujian</p>', 'status' => $status, 'published_at' => now()->subDay()]);
        }
        $this->get('/pengumuman?q=Ujian')->assertOk()->assertSee('Ujian published')->assertDontSee('Ujian draft');
        $this->get('/pengumuman?q=TidakAda')->assertOk()->assertSee('Tidak ada pengumuman');
    }

    public function test_academic_hub_links_to_live_routes(): void
    {
        $this->get('/akademik')->assertOk()->assertSee(route('page.jadwal-ujian'))
            ->assertSee(route('unduhan.index'))->assertSee(route('page.kurikulum'));
    }
}
