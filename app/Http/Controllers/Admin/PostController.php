<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StorePostRequest;
use App\Http\Requests\UpdatePostRequest;
use App\Models\Category;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class PostController extends Controller
{
    public function index(Request $request): View
    {
        $query = Post::with('categories', 'user')->latest();

        if (in_array($request->query('status'), ['draft', 'published'], true)) {
            $query->where('status', $request->query('status'));
        }

        if ($cari = $request->query('cari')) {
            $query->where('judul', 'like', "%{$cari}%");
        }

        if (in_array($request->query('jenis'), array_keys(Post::JENIS), true)) {
            $query->where('jenis', $request->query('jenis'));
        }

        if ($request->query('status') === 'menunggu') {
            $query->where('status', 'draft')->whereNotNull('submitted_at');
        }

        return view('admin.posts.index', [
            'posts' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.posts.create', [
            'categories' => Category::orderBy('nama')->get(),
        ]);
    }

    public function store(StorePostRequest $request): RedirectResponse
    {
        $data = $this->prepareData($request);
        $data['slug'] = Post::generateSlug($data['judul']);
        $data['user_id'] = $request->user()->id;

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $post = Post::create($data);
        $post->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.posts.index')
            ->with('status', 'Berita berhasil ditambahkan.');
    }

    public function edit(Post $post): View
    {
        return view('admin.posts.edit', [
            'post' => $post->load('categories', 'user'),
            'categories' => Category::orderBy('nama')->get(),
            'selected' => $post->categories->pluck('id')->all(),
        ]);
    }

    public function update(UpdatePostRequest $request, Post $post): RedirectResponse
    {
        $data = $this->prepareData($request);

        if ($data['judul'] !== $post->judul) {
            $data['slug'] = Post::generateSlug($data['judul'], $post->id);
        }

        if ($request->hasFile('featured_image')) {
            if ($post->featured_image) {
                Storage::disk('public')->delete($post->featured_image);
            }
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        $post->update($data);
        $post->categories()->sync($request->input('categories', []));

        return redirect()->route('admin.posts.index')
            ->with('status', 'Berita berhasil diperbarui.');
    }

    public function destroy(Post $post): RedirectResponse
    {
        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }

        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('status', 'Berita berhasil dihapus.');
    }

    public function returnToDraft(Post $post): RedirectResponse
    {
        abort_unless($post->isSubmitted(), 404);
        $post->update(['submitted_at' => null]);

        return redirect()->route('admin.posts.index', ['status' => 'menunggu'])
            ->with('status', 'Tulisan dikembalikan ke draft penulis.');
    }

    /**
     * Normalisasi data: set published_at otomatis jika status published & belum diisi.
     *
     * @return array<string, mixed>
     */
    private function prepareData(StorePostRequest $request): array
    {
        $data = $request->safe()->except(['categories', 'featured_image']);
        $data['jenis'] = $data['jenis'] ?? 'berita';

        // Sanitasi HTML dari WYSIWYG untuk mencegah XSS.
        $data['konten'] = clean($data['konten']);

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        if ($data['status'] === 'published') {
            $data['submitted_at'] = null;
        }

        if ($data['status'] === 'draft') {
            $data['published_at'] = $data['published_at'] ?? null;
        }

        return $data;
    }
}
