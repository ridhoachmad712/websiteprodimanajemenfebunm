<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PrestasiController extends Controller
{
    public function index(Request $request): View
    {
        $query = Prestasi::published();

        $kategori = $request->query('kategori');
        if ($kategori && array_key_exists($kategori, Prestasi::kategoriOptions())) {
            $query->where('kategori', $kategori);
        }

        $tingkat = $request->query('tingkat');
        if ($tingkat && array_key_exists($tingkat, Prestasi::tingkatOptions())) {
            $query->where('tingkat', $tingkat);
        }

        return view('frontend.prestasi', [
            'prestasi' => $query->orderByDesc('tanggal')->paginate(12)->withQueryString(),
            'kategoriAktif' => $kategori,
            'tingkatAktif' => $tingkat,
        ]);
    }
}
