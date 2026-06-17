<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDosenRequest;
use App\Http\Requests\UpdateDosenRequest;
use App\Models\Dosen;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DosenController extends Controller
{
    public function index(Request $request): View
    {
        $query = Dosen::query();

        if ($kategori = $request->query('kategori')) {
            $query->where('kategori', $kategori);
        }

        if ($cari = $request->query('cari')) {
            $query->where('nama', 'like', "%{$cari}%");
        }

        $dosen = $query->orderBy('kategori')->orderBy('urutan')->paginate(15)->withQueryString();

        return view('admin.dosen.index', [
            'dosen'    => $dosen,
            'kategori' => Dosen::KATEGORI,
        ]);
    }

    public function create(): View
    {
        return view('admin.dosen.create', [
            'kategori' => Dosen::KATEGORI,
        ]);
    }

    public function store(StoreDosenRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = $this->uniqueSlug($data['nama']);

        if ($request->hasFile('foto')) {
            $data['foto'] = $request->file('foto')->store('dosen', 'public');
        }

        Dosen::create($data);

        return redirect()->route('admin.dosen.index')
            ->with('status', 'Data dosen berhasil ditambahkan.');
    }

    public function edit(Dosen $dosen): View
    {
        return view('admin.dosen.edit', [
            'dosen'    => $dosen,
            'kategori' => Dosen::KATEGORI,
        ]);
    }

    public function update(UpdateDosenRequest $request, Dosen $dosen): RedirectResponse
    {
        $data = $request->validated();

        // Perbarui slug hanya bila nama berubah.
        if ($data['nama'] !== $dosen->nama) {
            $data['slug'] = $this->uniqueSlug($data['nama'], $dosen->id);
        }

        if ($request->hasFile('foto')) {
            if ($dosen->foto) {
                Storage::disk('public')->delete($dosen->foto);
            }
            $data['foto'] = $request->file('foto')->store('dosen', 'public');
        } else {
            unset($data['foto']); // jangan timpa foto lama dengan null
        }

        $dosen->update($data);

        return redirect()->route('admin.dosen.index')
            ->with('status', 'Data dosen berhasil diperbarui.');
    }

    public function destroy(Dosen $dosen): RedirectResponse
    {
        if ($dosen->foto) {
            Storage::disk('public')->delete($dosen->foto);
        }

        $dosen->delete();

        return redirect()->route('admin.dosen.index')
            ->with('status', 'Data dosen berhasil dihapus.');
    }

    /**
     * Hasilkan slug unik dari nama (mengabaikan record tertentu saat update).
     */
    private function uniqueSlug(string $nama, ?int $ignoreId = null): string
    {
        $base = Str::slug($nama);
        $slug = $base;
        $i = 1;

        while (Dosen::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = "{$base}-{$i}";
            $i++;
        }

        return $slug;
    }
}
