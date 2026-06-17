<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Gallery;
use App\Models\Page;
use App\Models\Post;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard ringkasan panel admin.
     */
    public function index(): View
    {
        $stats = [
            'berita'  => Post::count(),
            'dosen'   => Dosen::count(),
            'halaman' => Page::count(),
            'galeri'  => Gallery::count(),
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
