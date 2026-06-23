<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Kegiatan;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class KegiatanController extends Controller
{
    public function index(Request $request): View
    {
        $query = Kegiatan::query();

        if ($cari = $request->query('cari')) {
            $query->where('judul', 'like', "%{$cari}%");
        }

        return view('admin.kegiatan.index', [
            'kegiatan' => $query->orderByDesc('mulai')->paginate(15)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.kegiatan.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Kegiatan::create($this->validateData($request));

        return redirect()->route('admin.kegiatan.index')->with('status', 'Kegiatan berhasil ditambahkan.');
    }

    public function edit(Kegiatan $kegiatan): View
    {
        return view('admin.kegiatan.edit', ['kegiatan' => $kegiatan]);
    }

    public function update(Request $request, Kegiatan $kegiatan): RedirectResponse
    {
        $kegiatan->update($this->validateData($request));

        return redirect()->route('admin.kegiatan.index')->with('status', 'Kegiatan berhasil diperbarui.');
    }

    public function destroy(Kegiatan $kegiatan): RedirectResponse
    {
        $kegiatan->delete();

        return redirect()->route('admin.kegiatan.index')->with('status', 'Kegiatan berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request): array
    {
        $data = $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'mulai' => ['required', 'date'],
            'selesai' => ['nullable', 'date', 'after_or_equal:mulai'],
            'seharian' => ['nullable', 'boolean'],
            'lokasi' => ['nullable', 'string', 'max:255'],
            'deskripsi' => ['nullable', 'string', 'max:5000'],
            'warna' => ['nullable', 'string', 'max:20'],
        ], [], [
            'mulai' => 'tanggal mulai',
            'selesai' => 'tanggal selesai',
        ]);

        $data['seharian'] = $request->boolean('seharian');
        $data['warna'] = $data['warna'] ?? '';

        return $data;
    }
}
