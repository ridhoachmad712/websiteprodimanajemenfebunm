<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreGalleryRequest;
use App\Http\Requests\UpdateGalleryRequest;
use App\Models\Gallery;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        return view('admin.gallery.index', [
            'items' => Gallery::orderBy('urutan')->latest()->paginate(24),
        ]);
    }

    public function create(): View
    {
        return view('admin.gallery.create');
    }

    public function store(StoreGalleryRequest $request): RedirectResponse
    {
        $judul    = $request->input('judul');
        $kategori = $request->input('kategori');

        foreach ($request->file('gambar') as $i => $file) {
            Gallery::create([
                'judul'    => $judul ?: pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME),
                'gambar'   => $file->store('gallery', 'public'),
                'kategori' => $kategori,
                'urutan'   => $i,
            ]);
        }

        $jumlah = count($request->file('gambar'));

        return redirect()->route('admin.gallery.index')
            ->with('status', "{$jumlah} gambar berhasil diunggah.");
    }

    public function edit(Gallery $gallery): View
    {
        return view('admin.gallery.edit', compact('gallery'));
    }

    public function update(UpdateGalleryRequest $request, Gallery $gallery): RedirectResponse
    {
        $data = $request->safe()->except('gambar');

        if ($request->hasFile('gambar')) {
            Storage::disk('public')->delete($gallery->gambar);
            $data['gambar'] = $request->file('gambar')->store('gallery', 'public');
        }

        $gallery->update($data);

        return redirect()->route('admin.gallery.index')->with('status', 'Item galeri diperbarui.');
    }

    public function destroy(Gallery $gallery): RedirectResponse
    {
        Storage::disk('public')->delete($gallery->gambar);
        $gallery->delete();

        return redirect()->route('admin.gallery.index')->with('status', 'Item galeri dihapus.');
    }
}
