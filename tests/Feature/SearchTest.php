<?php

namespace Tests\Feature;

use App\Models\Dosen;
use App\Models\Post;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class SearchTest extends TestCase
{
    use RefreshDatabase;

    public function test_search_finds_posts_and_dosen(): void
    {
        Post::create([
            'judul' => 'Kuliah Umum Manajemen Strategi',
            'slug' => 'kuliah-umum-manajemen-strategi',
            'konten' => '<p>Isi berita tentang strategi.</p>',
            'user_id' => User::factory()->create()->id,
            'status' => 'published',
            'published_at' => now()->subDay(),
        ]);
        Dosen::create(['nama' => 'Dr. Strategi Hebat', 'slug' => 'dr-strategi-hebat', 'kategori' => 'tetap_prodi', 'konsentrasi' => 'Manajemen SDM']);

        $this->get('/cari?q=strategi')
            ->assertOk()
            ->assertSee('Kuliah Umum Manajemen Strategi')
            ->assertSee('Dr. Strategi Hebat');
    }

    public function test_search_hides_unpublished_posts(): void
    {
        Post::create([
            'judul' => 'Draf Rahasia Strategi',
            'slug' => 'draf-rahasia',
            'konten' => 'x',
            'user_id' => User::factory()->create()->id,
            'status' => 'draft',
            'published_at' => now()->subDay(),
        ]);

        $this->get('/cari?q=rahasia')
            ->assertOk()
            ->assertSee('Tidak ada hasil')
            ->assertDontSee('Draf Rahasia Strategi');
    }

    public function test_short_query_prompts_for_more_characters(): void
    {
        $this->get('/cari?q=a')
            ->assertOk()
            ->assertSee('minimal 2 karakter');
    }

    public function test_empty_query_renders_without_error(): void
    {
        $this->get('/cari')->assertOk();
    }

    public function test_search_shows_true_total_and_paginated_results(): void
    {
        $user = User::factory()->create();
        foreach (range(1, 12) as $number) {
            Post::create([
                'judul' => "Riset Khusus {$number}",
                'slug' => "riset-khusus-{$number}",
                'konten' => 'Riset di bidang manajemen.',
                'user_id' => $user->id,
                'status' => 'published',
                'published_at' => now()->subDays($number),
            ]);
        }

        $this->get('/cari?q=khusus')->assertOk()
            ->assertSee('Ditemukan <strong>12</strong> hasil', false)
            ->assertSee('Riset Khusus 1')
            ->assertDontSee('Riset Khusus 12');

        $this->get('/cari?q=khusus&berita_page=2')->assertOk()
            ->assertSee('Riset Khusus 12')
            ->assertDontSee('>Riset Khusus 1<', false);
    }
}
