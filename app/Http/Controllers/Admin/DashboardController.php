<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use App\Models\ContactMessage;
use App\Models\Dosen;
use App\Models\Download;
use App\Models\Gallery;
use App\Models\Kegiatan;
use App\Models\Mitra;
use App\Models\Page;
use App\Models\Pengumuman;
use App\Models\Post;
use App\Models\Prestasi;
use App\Models\Seminar;
use Illuminate\View\View;

class DashboardController extends Controller
{
    /**
     * Tampilkan dashboard ringkasan panel admin.
     */
    public function index(): View
    {
        $stats = [
            'berita' => Post::count(),
            'pengumuman' => Pengumuman::count(),
            'dosen' => Dosen::count(),
            'prestasi' => Prestasi::count(),
            'seminar' => Seminar::count(),
            'dokumen' => Download::count(),
            'mitra' => Mitra::count(),
            'kegiatan' => Kegiatan::count(),
            'galeri' => Gallery::count(),
            'halaman' => Page::count(),
        ];

        $unread = ContactMessage::unread()->count();
        $recentMessages = ContactMessage::latest()->take(5)->get();

        // Berita paling banyak dilihat.
        $popular = Post::published()->where('dilihat', '>', 0)
            ->orderByDesc('dilihat')->take(5)->get();

        // Aktivitas terbaru (ditampilkan untuk admin).
        $recentActivity = ActivityLog::with('user')->latest()->take(6)->get();

        return view('admin.dashboard', compact('stats', 'unread', 'recentMessages', 'popular', 'recentActivity'));
    }
}
