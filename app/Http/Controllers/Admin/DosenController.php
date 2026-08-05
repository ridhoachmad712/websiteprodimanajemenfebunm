<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDosenRequest;
use App\Http\Requests\UpdateDosenRequest;
use App\Models\Dosen;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DosenController extends Controller
{
    public function index(): View
    {
        // Semua dosen (urut sesuai 'urutan'), dikelompokkan per kategori seperti
        // di halaman depan — supaya bisa disusun ulang via drag-and-drop.
        $semua = Dosen::orderBy('urutan')->orderBy('nama')->get();

        $grup = collect(Dosen::KATEGORI)->mapWithKeys(fn ($label, $key) => [
            $key => ['label' => $label, 'items' => $semua->where('kategori', $key)->values()],
        ])->filter(fn ($g) => $g['items']->isNotEmpty());

        return view('admin.dosen.index', [
            'grup' => $grup,
            'kategori' => Dosen::KATEGORI,
            'total' => $semua->count(),
        ]);
    }

    /**
     * Simpan urutan baru hasil drag-and-drop (daftar ID sesuai urutan tampil).
     */
    public function reorder(Request $request): JsonResponse
    {
        $ids = collect($request->input('ids', []))
            ->filter(fn ($v) => is_numeric($v))
            ->map(fn ($v) => (int) $v)
            ->values();

        // toBase(): update langsung tanpa memicu event model / mengubah updated_at.
        foreach ($ids as $i => $id) {
            Dosen::whereKey($id)->toBase()->update(['urutan' => $i + 1]);
        }

        return response()->json(['ok' => true, 'count' => $ids->count()]);
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
        $data['biografi'] = filled($data['biografi'] ?? null) ? clean($data['biografi']) : null;
        $data['tautan'] = $this->cleanTautan($request->input('tautan', []));

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
            'dosen' => $dosen,
            'kategori' => Dosen::KATEGORI,
        ]);
    }

    public function update(UpdateDosenRequest $request, Dosen $dosen): RedirectResponse
    {
        $data = $request->validated();
        $data['biografi'] = filled($data['biografi'] ?? null) ? clean($data['biografi']) : null;
        $data['tautan'] = $this->cleanTautan($request->input('tautan', []));

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
     * Rapikan daftar tautan kustom: buang baris tanpa URL/label, normalisasi.
     *
     * @param  array<int, mixed>  $tautan
     * @return array<int, array<string, string>>
     */
    private function cleanTautan(array $tautan): array
    {
        return collect($tautan)
            ->filter(fn ($t) => is_array($t) && filled($t['label'] ?? null) && filled($t['url'] ?? null))
            ->map(fn ($t) => [
                'label' => trim($t['label']),
                'url' => trim($t['url']),
                'icon' => trim($t['icon'] ?? '') ?: 'ti-link',
                'color' => $t['color'] ?? 'primary',
            ])
            ->values()
            ->all();
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
