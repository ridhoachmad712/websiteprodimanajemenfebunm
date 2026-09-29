<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Post;
use Illuminate\View\View;
use Illuminate\Http\Request;

class PostController extends Controller
{
    /**
     * Daftar berita terbit (terbaru lebih dulu).
     */
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => 'nullable|string|max:200', 'category' => 'nullable|string|max:200']);
        $posts = Post::published()->with('categories')
            ->when($filters['q'] ?? null, fn ($query, $term) => $query->where('judul', 'like', '%'.$term.'%'))
            ->when($filters['category'] ?? null, fn ($query, $slug) => $query->whereHas('categories', fn ($category) => $category->where('slug', $slug)))
            ->latest('published_at')
            ->paginate(9)->withQueryString();

        return view('frontend.posts.index', [
            'posts' => $posts,
            'categories' => Category::orderBy('nama')->get(),
        ]);
    }

    /**
     * Arsip berita per kategori.
     */
    public function byCategory(Category $category): View
    {
        return view('frontend.posts.category', [
            'category' => $category,
            'posts' => $category->posts()->published()->with('categories')->latest('published_at')->paginate(9),
            'categories' => Category::orderBy('nama')->get(),
        ]);
    }

    /**
     * Detail berita. URL mempertahankan pola WordPress /YYYY/MM/DD/slug;
     * komponen tanggal divalidasi terhadap published_at agar URL kanonis.
     */
    public function show(string $year, string $month, string $day, string $slug): View
    {
        $post = Post::published()->where('slug', $slug)->firstOrFail();

        $tanggal = $post->published_at;
        if ($tanggal->format('Y/m/d') !== "{$year}/{$month}/{$day}") {
            abort(404);
        }

        // Tambah penghitung dilihat tanpa memicu event model (agar tidak masuk log aktivitas)
        // dan tanpa mengubah updated_at.
        Post::whereKey($post->getKey())->toBase()->update(['dilihat' => $post->dilihat + 1]);
        $post->dilihat++;

        return view('frontend.posts.show', [
            'post' => $post->load('categories', 'user'),
            'terkait' => Post::published()
                ->where('id', '!=', $post->id)
                ->latest('published_at')
                ->take(3)
                ->get(),
        ]);
    }
}
