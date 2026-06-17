<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard ringkasan panel admin.
     */
    public function index(): View
    {
        // Statistik masih placeholder; akan diisi data nyata setelah
        // modul Berita & Dosen tersedia (Tugas berikutnya).
        $stats = [
            'berita' => 0,
            'dosen'  => 0,
            'halaman' => 0,
            'galeri' => 0,
        ];

        return view('admin.dashboard', compact('stats'));
    }
}
