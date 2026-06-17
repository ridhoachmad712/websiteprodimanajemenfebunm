<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dosen;
use App\Models\Page;
use App\Models\Post;
use Illuminate\Http\Response;

class SitemapController extends Controller
{
    public function index(): Response
    {
        $urls = [];

        // Beranda + daftar utama
        $urls[] = ['loc' => url('/'), 'priority' => '1.0'];
        $urls[] = ['loc' => route('post.index'), 'priority' => '0.8'];
        $urls[] = ['loc' => route('dosen.index'), 'priority' => '0.7'];
        $urls[] = ['loc' => route('gallery.index'), 'priority' => '0.5'];

        // Halaman statis
        foreach (Page::all() as $page) {
            $loc = $page->slug === 'profil' ? route('page.profil') : url('/'.$page->slug);
            $urls[] = ['loc' => $loc, 'lastmod' => $page->updated_at?->toAtomString(), 'priority' => '0.6'];
        }

        // Kategori berita
        foreach (Category::all() as $cat) {
            $urls[] = ['loc' => route('post.category', $cat), 'priority' => '0.5'];
        }

        // Dosen
        foreach (Dosen::all() as $d) {
            $urls[] = ['loc' => route('dosen.show', $d), 'priority' => '0.5'];
        }

        // Berita terbit
        foreach (Post::published()->get() as $post) {
            $urls[] = ['loc' => $post->url(), 'lastmod' => $post->updated_at?->toAtomString(), 'priority' => '0.7'];
        }

        return response()
            ->view('sitemap', compact('urls'))
            ->header('Content-Type', 'application/xml');
    }
}
