<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\BeritaEksternal;
use Illuminate\Http\Request;
use Illuminate\View\View;

class BeritaEksternalController extends Controller
{
    public function index(Request $request): View
    {
        $query = BeritaEksternal::published();

        if ($cari = $request->query('cari')) {
            $query->where(fn ($q) => $q->where('judul', 'like', "%{$cari}%")
                ->orWhere('sumber', 'like', "%{$cari}%"));
        }

        return view('frontend.berita-eksternal', [
            'berita' => $query->orderByDesc('tanggal')->orderByDesc('id')->paginate(20)->withQueryString(),
        ]);
    }
}
