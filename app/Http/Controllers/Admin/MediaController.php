<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MediaController extends Controller
{
    private const IMAGE_EXT = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'svg', 'avif'];

    private const PER_PAGE = 24;

    public function index(Request $request): View
    {
        $disk = Storage::disk('public');

        $all = collect($disk->allFiles())
            ->reject(fn ($p) => str_contains($p, '/.') || str_starts_with(basename($p), '.'))
            ->map(function ($p) use ($disk) {
                $ext = strtolower(pathinfo($p, PATHINFO_EXTENSION));

                return [
                    'path' => $p,
                    'name' => basename($p),
                    'folder' => str_contains($p, '/') ? explode('/', $p)[0] : '(root)',
                    'url' => $disk->url($p),
                    'ext' => $ext,
                    'is_image' => in_array($ext, self::IMAGE_EXT, true),
                    'size' => $this->humanSize($disk->size($p)),
                    'modified' => $disk->lastModified($p),
                ];
            });

        $folders = $all->pluck('folder')->unique()->sort()->values();

        $filtered = $all
            ->when($request->query('folder'), fn ($c, $f) => $c->where('folder', $f))
            ->when($request->query('cari'), fn ($c, $q) => $c->filter(fn ($i) => str_contains(strtolower($i['name']), strtolower($q))))
            ->sortByDesc('modified')
            ->values();

        $page = max(1, (int) $request->query('page', 1));
        $items = new LengthAwarePaginator(
            $filtered->forPage($page, self::PER_PAGE)->values(),
            $filtered->count(),
            self::PER_PAGE,
            $page,
            ['path' => $request->url(), 'query' => $request->query()],
        );

        return view('admin.media.index', [
            'items' => $items,
            'folders' => $folders,
            'total' => $all->count(),
        ]);
    }

    public function destroy(Request $request): RedirectResponse
    {
        $path = (string) $request->input('path');
        $disk = Storage::disk('public');

        // Cegah path traversal & pastikan berkas ada di disk publik.
        if ($path === '' || str_contains($path, '..') || ! $disk->exists($path)) {
            return back()->with('error', 'Berkas tidak valid atau tidak ditemukan.');
        }

        $disk->delete($path);

        return back()->with('status', 'Berkas "'.basename($path).'" berhasil dihapus.');
    }

    private function humanSize(int $bytes): string
    {
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 1).' MB';
        }
        if ($bytes >= 1024) {
            return round($bytes / 1024).' KB';
        }

        return $bytes.' B';
    }
}
