<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SeminarController extends Controller
{
    public function index(Request $request): View
    {
        $jenis = $request->query('jenis');
        $filterJenis = array_key_exists((string) $jenis, Seminar::jenisOptions()) ? $jenis : null;

        $base = fn () => Seminar::published()
            ->when($filterJenis, fn ($q) => $q->where('jenis', $filterJenis));

        return view('frontend.seminar', [
            'mendatang' => $base()->mendatang()->orderBy('tanggal')->get(),
            'lalu' => $base()->lalu()->orderByDesc('tanggal')->paginate(10)->withQueryString(),
            'jenisAktif' => $filterJenis,
        ]);
    }
}
