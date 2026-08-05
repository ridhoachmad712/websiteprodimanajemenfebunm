<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\BeritaEksternal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class BeritaEksternalController extends Controller
{
    public function index(Request $request): View
    {
        $query = BeritaEksternal::query();

        if ($cari = $request->query('cari')) {
            $query->where(fn ($q) => $q->where('judul', 'like', "%{$cari}%")
                ->orWhere('sumber', 'like', "%{$cari}%"));
        }

        return view('admin.berita-eksternal.index', [
            'berita' => $query->orderByDesc('tanggal')->paginate(15)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.berita-eksternal.create');
    }

    public function store(Request $request): RedirectResponse
    {
        BeritaEksternal::create($this->validateData($request));

        return redirect()->route('admin.berita-eksternal.index')->with('status', 'Berita eksternal berhasil ditambahkan.');
    }

    public function edit(BeritaEksternal $berita_eksternal): View
    {
        return view('admin.berita-eksternal.edit', ['berita' => $berita_eksternal]);
    }

    public function update(Request $request, BeritaEksternal $berita_eksternal): RedirectResponse
    {
        $berita_eksternal->update($this->validateData($request));

        return redirect()->route('admin.berita-eksternal.index')->with('status', 'Berita eksternal berhasil diperbarui.');
    }

    public function destroy(BeritaEksternal $berita_eksternal): RedirectResponse
    {
        $berita_eksternal->delete();

        return redirect()->route('admin.berita-eksternal.index')->with('status', 'Berita eksternal berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'sumber' => ['required', 'string', 'max:255'],
            'url' => ['required', 'url', 'max:2000'],
            'tanggal' => ['nullable', 'date'],
            'ringkasan' => ['nullable', 'string', 'max:2000'],
            'status' => ['required', Rule::in(['published', 'draft'])],
        ], [], [
            'judul' => 'judul berita',
            'sumber' => 'nama media',
            'url' => 'tautan',
        ]);
    }
}
