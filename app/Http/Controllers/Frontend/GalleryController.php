<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\View\View;

class GalleryController extends Controller
{
    public function index(): View
    {
        // Kelompokkan per kategori; item tanpa kategori masuk grup "Lainnya".
        $grup = Gallery::orderBy('urutan')->latest()->get()
            ->groupBy(fn ($g) => $g->kategori ?: 'Lainnya');

        return view('frontend.gallery.index', compact('grup'));
    }
}
