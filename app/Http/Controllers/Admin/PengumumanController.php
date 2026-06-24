<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

/**
 * Pengelolaan Pengumuman — modul mandiri (tabel `pengumuman`), terpisah dari Berita.
 */
class PengumumanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Pengumuman::latest('published_at');

        if ($status = $request->query('status')) {
            $query->where('status', $status);
        }
        if ($cari = $request->query('cari')) {
            $query->where('judul', 'like', "%{$cari}%");
        }

        return view('admin.pengumuman.index', [
            'posts' => $query->paginate(15)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.pengumuman.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['slug'] = Pengumuman::generateSlug($data['judul']);

        Pengumuman::create($data);

        return redirect()->route('admin.pengumuman.index')
            ->with('status', 'Pengumuman berhasil ditambahkan.');
    }

    public function edit(Pengumuman $pengumuman): View
    {
        return view('admin.pengumuman.edit', ['post' => $pengumuman]);
    }

    public function update(Request $request, Pengumuman $pengumuman): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($data['judul'] !== $pengumuman->judul) {
            $data['slug'] = Pengumuman::generateSlug($data['judul'], $pengumuman->id);
        }

        $pengumuman->update($data);

        return redirect()->route('admin.pengumuman.index')
            ->with('status', 'Pengumuman berhasil diperbarui.');
    }

    public function destroy(Pengumuman $pengumuman): RedirectResponse
    {
        $pengumuman->delete();

        return redirect()->route('admin.pengumuman.index')
            ->with('status', 'Pengumuman berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'konten' => ['required', 'string'],
            'status' => ['required', Rule::in(['draft', 'published'])],
            'published_at' => ['nullable', 'date'],
        ], [], [
            'judul' => 'judul',
            'konten' => 'isi pengumuman',
            'published_at' => 'tanggal terbit',
        ]);

        $data['konten'] = clean($data['konten']);

        if ($data['status'] === 'published' && empty($data['published_at'])) {
            $data['published_at'] = now();
        }

        return $data;
    }
}
