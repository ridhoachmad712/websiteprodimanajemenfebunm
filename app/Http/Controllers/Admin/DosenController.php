<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreDosenRequest;
use App\Http\Requests\UpdateDosenRequest;
use App\Models\Dosen;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\View\View;

class DosenController extends Controller
{
    public function index(Request $request): View
    {
        $konsentrasi = Dosen::query()->whereNotNull('konsentrasi')->where('konsentrasi', '!=', '')
            ->distinct()->orderBy('konsentrasi')->pluck('konsentrasi')->all();
        $rawQ = $request->query('q');
        $q = is_string($rawQ) ? Str::substr(trim($rawQ), 0, 100) : '';
        $selectedKategori = $request->query('kategori');
        $selectedKategori = is_string($selectedKategori) && array_key_exists($selectedKategori, Dosen::KATEGORI) ? $selectedKategori : '';
        $selectedKonsentrasi = $request->query('konsentrasi');
        $selectedKonsentrasi = is_string($selectedKonsentrasi) && in_array($selectedKonsentrasi, $konsentrasi, true) ? $selectedKonsentrasi : '';
        $isFiltered = $q !== '' || $selectedKategori !== '' || $selectedKonsentrasi !== '';

        $semua = Dosen::query()
            ->when($q !== '', fn ($query) => $query->where(fn ($match) => $match
                ->where('nama', 'like', "%{$q}%")
                ->orWhere('nip', 'like', "%{$q}%")
                ->orWhere('jabatan', 'like', "%{$q}%")
                ->orWhere('konsentrasi', 'like', "%{$q}%")))
            ->when($selectedKategori !== '', fn ($query) => $query->where('kategori', $selectedKategori))
            ->when($selectedKonsentrasi !== '', fn ($query) => $query->where('konsentrasi', $selectedKonsentrasi))
            ->orderBy('urutan')->orderBy('nama')->get();

        $grup = collect(Dosen::KATEGORI)->mapWithKeys(fn ($label, $key) => [
            $key => ['label' => $label, 'items' => $semua->where('kategori', $key)->values()],
        ])->filter(fn ($g) => $g['items']->isNotEmpty());

        return view('admin.dosen.index', [
            'grup' => $grup,
            'kategori' => Dosen::KATEGORI,
            'total' => $semua->count(),
            'totalAll' => Dosen::count(),
            'konsentrasiOptions' => $konsentrasi,
            'filters' => ['q' => $q, 'kategori' => $selectedKategori, 'konsentrasi' => $selectedKonsentrasi],
            'isFiltered' => $isFiltered,
            'groupByConcentration' => Setting::get('dosen.group_by_concentration', '1') === '1',
        ]);
    }

    public function updateGrouping(Request $request): RedirectResponse
    {
        $data = $request->validate(['enabled' => ['required', 'boolean']]);
        Setting::set('dosen.group_by_concentration', $data['enabled'] ? '1' : '0', 'dosen');

        return redirect()->route('admin.dosen.index')
            ->with('status', 'Pengelompokan konsentrasi pada halaman publik berhasil diperbarui.');
    }

    /**
     * Simpan urutan baru hasil drag-and-drop (daftar ID sesuai urutan tampil).
     */
    public function reorder(Request $request): JsonResponse
    {
        $data = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['required', 'integer', 'min:1']]);
        $ids = collect($data['ids'])->map(fn ($id) => (int) $id);
        $savedIds = Dosen::query()->pluck('id');

        if ($ids->count() !== $savedIds->count() || $ids->sort()->values()->all() !== $savedIds->sort()->values()->all()) {
            return response()->json(['message' => 'Daftar dosen berubah. Muat ulang halaman sebelum menyusun urutan.'], 422);
        }

        DB::transaction(function () use ($ids) {
            foreach ($ids->values() as $i => $id) {
                Dosen::whereKey($id)->toBase()->update(['urutan' => $i + 1]);
            }
        });

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
        // Dosen baru diletakkan di urutan paling bawah (nilai 'urutan' tertinggi),
        // sehingga tampil terakhir di kategorinya pada halaman depan.
        $data['urutan'] = (Dosen::max('urutan') ?? 0) + 1;

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
