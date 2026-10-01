<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Setting;
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
        $groupByConcentration = Setting::get('dosen.group_by_concentration', '1') === '1';

        $urutanKonsentrasi = array_flip([...Dosen::KONSENTRASI_MANAJEMEN, ...Dosen::KONSENTRASI_MKDU]);

        // Kelompokkan & urutkan grup mengikuti urutan konstanta KATEGORI.
        $grup = collect(Dosen::KATEGORI)->mapWithKeys(function ($label, $key) use ($semua, $subKonsentrasi, $urutanKonsentrasi, $groupByConcentration) {
            $items = $semua->where('kategori', $key)->values();

            $subgroups = null;
            if ($groupByConcentration && in_array($key, $subKonsentrasi, true)) {
                $byK = $items->groupBy(fn ($d) => $d->konsentrasi ?: 'Lainnya');
                $subgroups = $byK->sortKeysUsing(fn ($a, $b) =>
                    ($urutanKonsentrasi[$a] ?? PHP_INT_MAX) <=> ($urutanKonsentrasi[$b] ?? PHP_INT_MAX)
                    ?: strcmp($a, $b)
                )
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
