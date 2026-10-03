<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Dosen;
use App\Models\Post;
use App\Models\Setting;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        return view('frontend.home', [
            'stats' => [
                'mahasiswa' => Setting::get('statistik.mahasiswa', '0'),
                'dosen'     => Setting::get('statistik.dosen', '0'),
            ],
            'tagline'      => Setting::get('site.tagline', 'Forever in Brotherhood'),
            'taglineBrand' => Setting::get('site.tagline_brand', 'Build – Manage – Integrate'),

            'berita'     => $this->postsByCategory('berita-informasi', 3),
            'prestasi'   => $this->postsByCategory('prestasi', 3),
            'artikel'    => Post::published()->where(fn ($query) => $query
                ->whereIn('jenis', ['artikel', 'opini'])
                ->orWhereHas('categories', fn ($category) => $category->where('slug', 'artikel')))
                ->with('categories', 'user')->latest('published_at')->take(3)->get(),
            'pengumuman' => $this->postsByCategory('pengumuman', 4),

            'dosenPreview' => Dosen::orderBy('urutan')->take(6)->get(),
        ]);
    }

    /**
     * Ambil post terbit pada kategori tertentu; fallback ke post terbaru
     * bila kategori belum punya konten (agar beranda tidak kosong).
     */
    private function postsByCategory(string $slug, int $limit)
    {
        $category = Category::where('slug', $slug)->first();

        $query = $category
            ? $category->posts()->published()
            : Post::published();

        return $query->with('categories')->latest('published_at')->take($limit)->get();
    }
}
