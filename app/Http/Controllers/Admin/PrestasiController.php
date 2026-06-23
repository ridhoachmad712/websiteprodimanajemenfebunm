<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class PrestasiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Prestasi::query();

        if ($cari = $request->query('cari')) {
            $query->where(fn ($q) => $q->where('judul', 'like', "%{$cari}%")
                ->orWhere('peraih', 'like', "%{$cari}%"));
        }

        return view('admin.prestasi.index', [
            'prestasi' => $query->orderByDesc('tanggal')->paginate(15)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.prestasi.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['gambar'] = $request->file('gambar')?->store('prestasi', 'public');

        Prestasi::create($data);

        return redirect()->route('admin.prestasi.index')->with('status', 'Prestasi berhasil ditambahkan.');
    }

    public function edit(Prestasi $prestasi): View
    {
        return view('admin.prestasi.edit', ['prestasi' => $prestasi]);
    }

    public function update(Request $request, Prestasi $prestasi): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($request->hasFile('gambar')) {
            if ($prestasi->gambar) {
                Storage::disk('public')->delete($prestasi->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('prestasi', 'public');
        }

        $prestasi->update($data);

        return redirect()->route('admin.prestasi.index')->with('status', 'Prestasi berhasil diperbarui.');
    }

    public function destroy(Prestasi $prestasi): RedirectResponse
    {
        if ($prestasi->gambar) {
            Storage::disk('public')->delete($prestasi->gambar);
        }
        $prestasi->delete();

        return redirect()->route('admin.prestasi.index')->with('status', 'Prestasi berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', Rule::in(array_keys(Prestasi::kategoriOptions()))],
            'tingkat' => ['nullable', Rule::in(array_keys(Prestasi::tingkatOptions()))],
            'peraih' => ['nullable', 'string', 'max:255'],
            'penyelenggara' => ['nullable', 'string', 'max:255'],
            'tanggal' => ['required', 'date'],
            'gambar' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'status' => ['required', Rule::in(['published', 'draft'])],
        ]);
    }
}
