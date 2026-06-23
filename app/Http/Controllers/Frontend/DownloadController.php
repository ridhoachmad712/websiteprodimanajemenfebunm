<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Illuminate\View\View;

class DownloadController extends Controller
{
    public function index(): View
    {
        // Kelompokkan dokumen terbit per kategori (urut kategori, lalu urutan/judul).
        $grup = Download::published()
            ->orderBy('kategori')
            ->orderBy('urutan')
            ->orderBy('judul')
            ->get()
            ->groupBy('kategori');

        return view('frontend.unduhan', ['grup' => $grup]);
    }
}
