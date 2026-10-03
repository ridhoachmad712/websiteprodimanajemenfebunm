<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
use Illuminate\View\View;

class DosenWritingController extends Controller
{
    public function index(Request $request): View
    {
        return view('admin.writings.index', [
            'posts' => Post::where('user_id', $request->user()->id)
                ->whereIn('jenis', ['artikel', 'opini'])
                ->latest()->paginate(15),
        ]);
    }

    public function create(): View
    {
        return view('admin.writings.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validated($request);
        $data['slug'] = Post::generateSlug($data['judul']);
        $data['user_id'] = $request->user()->id;
        $data['status'] = 'draft';
        $data['konten'] = clean($data['konten']);
        $this->ensureReadableContent($data['konten']);

        if ($request->hasFile('featured_image')) {
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
        }

        Post::create($data);

        return redirect()->route('admin.writings.index')->with('status', 'Draft tulisan disimpan.');
    }

    public function edit(Request $request, Post $post): View
    {
        $this->authorizeDraft($request, $post);

        return view('admin.writings.edit', ['post' => $post]);
    }

    public function update(Request $request, Post $post): RedirectResponse
    {
        $this->authorizeDraft($request, $post);
        $data = $this->validated($request);
        $data['konten'] = clean($data['konten']);
        $this->ensureReadableContent($data['konten']);

        if ($data['judul'] !== $post->judul) {
            $data['slug'] = Post::generateSlug($data['judul'], $post->id);
        }

        if ($request->hasFile('featured_image')) {
            $oldImage = $post->featured_image;
            $data['featured_image'] = $request->file('featured_image')->store('posts', 'public');
            if ($oldImage) {
                Storage::disk('public')->delete($oldImage);
            }
        }

        $post->update($data);

        return redirect()->route('admin.writings.index')->with('status', 'Draft tulisan diperbarui.');
    }

    public function submit(Request $request, Post $post): RedirectResponse
    {
        $this->authorizeDraft($request, $post);
        $post->update(['submitted_at' => now()]);

        return redirect()->route('admin.writings.index')->with('status', 'Tulisan dikirim untuk ditinjau editor.');
    }

    public function withdraw(Request $request, Post $post): RedirectResponse
    {
        abort_unless($post->user_id === $request->user()->id
            && in_array($post->jenis, ['artikel', 'opini'], true)
            && $post->isSubmitted(), 403);
        $post->update(['submitted_at' => null]);

        return redirect()->route('admin.writings.index')->with('status', 'Pengajuan ditarik. Tulisan kembali menjadi draft.');
    }

    public function destroy(Request $request, Post $post): RedirectResponse
    {
        $this->authorizeDraft($request, $post);

        if ($post->featured_image) {
            Storage::disk('public')->delete($post->featured_image);
        }
        $post->delete();

        return redirect()->route('admin.writings.index')->with('status', 'Draft tulisan dihapus.');
    }

    private function authorizeDraft(Request $request, Post $post): void
    {
        abort_unless($post->user_id === $request->user()->id
            && in_array($post->jenis, ['artikel', 'opini'], true)
            && $post->status === 'draft'
            && $post->submitted_at === null, 403);
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'jenis' => ['required', Rule::in(['artikel', 'opini'])],
            'excerpt' => ['nullable', 'string', 'max:200'],
            'konten' => ['required', 'string'],
            'featured_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:2048'],
        ]);
    }

    private function ensureReadableContent(string $content): void
    {
        if (trim(strip_tags($content)) === '') {
            throw ValidationException::withMessages(['konten' => 'Isi tulisan tidak boleh kosong.']);
        }
    }
}
