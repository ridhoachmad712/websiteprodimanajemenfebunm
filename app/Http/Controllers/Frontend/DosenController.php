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

        // Kelompokkan & urutkan grup mengikuti urutan konstanta KATEGORI.
        $grup = collect(Dosen::KATEGORI)->mapWithKeys(function ($label, $key) use ($semua) {
            return [$key => [
                'label' => $label,
                'items' => $semua->where('kategori', $key)->values(),
            ]];
        })->filter(fn ($g) => $g['items']->isNotEmpty());

        return view('frontend.dosen.index', [
            'grup'  => $grup,
            'total' => $semua->count(),
        ]);
    }

    public function show(Dosen $dosen): View
    {
        return view('frontend.dosen.show', compact('dosen'));
    }
}
