<?php

namespace Tests\Feature;

use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PostExtrasTest extends TestCase
{
    use RefreshDatabase;

    private function buatPost(): Post
    {
        return Post::create([
            'judul' => 'Berita Uji',
            'slug' => 'berita-uji',
            'konten' => '<p>Isi berita.</p>',
            'user_id' => User::factory()->create()->id,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
    }

    public function test_visiting_a_post_increments_view_count(): void
    {
        $post = $this->buatPost();
        $this->assertSame(0, (int) $post->dilihat);

        $this->get($post->url())->assertOk();

        $this->assertSame(1, (int) $post->fresh()->dilihat);
    }

    public function test_modern_post_card_renders(): void
    {
        // Perlu >1 post: yang pertama jadi "featured", sisanya render sebagai kartu.
        $uid = User::factory()->create()->id;
        foreach (range(1, 3) as $i) {
            Post::create([
                'judul' => "Berita {$i}", 'slug' => "berita-{$i}", 'konten' => '<p>x</p>',
                'user_id' => $uid, 'status' => 'published', 'published_at' => now()->subDays($i),
            ]);
        }

        $this->get('/berita')
            ->assertOk()
            ->assertSee('post-thumb', false); // markup kartu berita baru
    }

    public function test_post_page_shows_view_count_and_share_buttons(): void
    {
        $post = $this->buatPost();

        $this->get($post->url())
            ->assertOk()
            ->assertSee('dilihat')
            ->assertSee('data-share', false)
            ->assertSee('wa.me', false);
    }
}
