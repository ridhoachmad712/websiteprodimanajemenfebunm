<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use Illuminate\View\View;

class DosenController extends Controller
{
    /**
     * Daftar dosen, dikelompokkan per kategori sesuai urutan KATEGORI.
     */
    public function index(): View
    {
        $semua = Dosen::orderBy('urutan')->orderBy('nama')->get();

        // Kategori ini ditampilkan bertingkat lagi menurut konsentrasi.
        $subKonsentrasi = ['tetap_prodi', 'luar_biasa'];

        // Kelompokkan & urutkan grup mengikuti urutan konstanta KATEGORI.
        $grup = collect(Dosen::KATEGORI)->mapWithKeys(function ($label, $key) use ($semua, $subKonsentrasi) {
            $items = $semua->where('kategori', $key)->values();

            $subgroups = null;
            if (in_array($key, $subKonsentrasi, true)) {
                // Sub-kelompok per konsentrasi; urutan konsentrasi mengikuti konsentrasiMap,
                // item dalam tiap konsentrasi tetap mengikuti 'urutan' (drag-and-drop).
                $order = Dosen::konsentrasiMap()[$key] ?? [];
                $byK = $items->groupBy(fn ($d) => $d->konsentrasi ?: 'Lainnya');

                // Urutkan kunci konsentrasi: sesuai map dulu, sisanya (mis. 'Lainnya') di akhir.
                $keys = collect($order)
                    ->merge($byK->keys()->reject(fn ($k) => in_array($k, $order, true)))
                    ->unique();

                $subgroups = $keys
                    ->mapWithKeys(fn ($k) => [$k => $byK->get($k, collect())])
                    ->filter(fn ($c) => $c->isNotEmpty());
            }

            return [$key => ['label' => $label, 'items' => $items, 'subgroups' => $subgroups]];
        })->filter(fn ($g) => $g['items']->isNotEmpty());

        return view('frontend.dosen.index', [
            'grup' => $grup,
            'total' => $semua->count(),
        ]);
    }

    public function show(Dosen $dosen): View
    {
        return view('frontend.dosen.show', compact('dosen'));
    }
}
