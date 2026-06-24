<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Dosen;
use App\Models\Page;
use App\Models\Pengumuman;
use App\Models\Post;
use App\Models\Prestasi;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

class SearchController extends Controller
{
    /** Maksimal hasil per jenis konten. */
    private const PER_TYPE = 10;

    public function index(Request $request): View
    {
        $q = trim((string) $request->query('q', ''));

        $groups = [];
        $total = 0;

        if (mb_strlen($q) >= 2) {
            $like = '%'.$q.'%';

            $berita = Post::published()
                ->where(fn ($w) => $w->where('judul', 'like', $like)
                    ->orWhere('excerpt', 'like', $like)
                    ->orWhere('konten', 'like', $like))
                ->orderByDesc('published_at')
                ->limit(self::PER_TYPE)
                ->get()
                ->map(fn (Post $p) => [
                    'title' => $p->judul,
                    'snippet' => $p->ringkasan(140),
                    'url' => $p->url(),
                    'meta' => $p->published_at?->translatedFormat('d M Y'),
                ]);

            $dosen = Dosen::where(fn ($w) => $w->where('nama', 'like', $like)
                ->orWhere('konsentrasi', 'like', $like)
                ->orWhere('kepakaran', 'like', $like))
                ->orderBy('nama')
                ->limit(self::PER_TYPE)
                ->get()
                ->map(fn (Dosen $d) => [
                    'title' => $d->nama,
                    'snippet' => $d->konsentrasi ?: $d->kepakaran,
                    'url' => route('dosen.show', $d),
                    'meta' => Dosen::KATEGORI[$d->kategori] ?? null,
                ]);

            $pengumuman = Pengumuman::published()
                ->where(fn ($w) => $w->where('judul', 'like', $like)
                    ->orWhere('konten', 'like', $like))
                ->latest('published_at')
                ->limit(self::PER_TYPE)
                ->get()
                ->map(fn (Pengumuman $p) => [
                    'title' => $p->judul,
                    'snippet' => $p->ringkasan(140),
                    'url' => $p->url(),
                    'meta' => $p->published_at?->translatedFormat('d M Y'),
                ]);

            $halaman = Page::where('status', 'published')
                ->where(fn ($w) => $w->where('title', 'like', $like)
                    ->orWhere('content', 'like', $like))
                ->orderBy('title')
                ->limit(self::PER_TYPE)
                ->get()
                ->map(fn (Page $p) => [
                    'title' => $p->title,
                    'snippet' => Str::limit(strip_tags((string) $p->content), 140),
                    'url' => $p->publicUrl(),
                    'meta' => null,
                ]);

            $prestasi = Prestasi::published()
                ->where(fn ($w) => $w->where('judul', 'like', $like)
                    ->orWhere('peraih', 'like', $like))
                ->orderByDesc('tanggal')
                ->limit(self::PER_TYPE)
                ->get()
                ->map(fn (Prestasi $p) => [
                    'title' => $p->judul,
                    'snippet' => $p->peraih,
                    'url' => route('prestasi.index'),
                    'meta' => $p->tanggal?->translatedFormat('d M Y'),
                ]);

            $groups = array_filter([
                'Berita' => $berita,
                'Pengumuman' => $pengumuman,
                'Dosen' => $dosen,
                'Halaman' => $halaman,
                'Prestasi' => $prestasi,
            ], fn ($items) => $items->isNotEmpty());

            $total = array_sum(array_map(fn ($items) => $items->count(), $groups));
        }

        return view('frontend.search', [
            'q' => $q,
            'groups' => $groups,
            'total' => $total,
        ]);
    }
}
