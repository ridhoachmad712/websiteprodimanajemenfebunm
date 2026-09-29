<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Pengumuman;
use Illuminate\View\View;
use Illuminate\Http\Request;

class PengumumanController extends Controller
{
    public function index(Request $request): View
    {
        $filters = $request->validate(['q' => 'nullable|string|max:200']);
        return view('frontend.pengumuman.index', [
            'pengumuman' => Pengumuman::published()
                ->when($filters['q'] ?? null, fn ($query, $term) => $query->where('judul', 'like', '%'.$term.'%'))
                ->latest('published_at')->paginate(12)->withQueryString(),
        ]);
    }

    public function show(Pengumuman $pengumuman): View
    {
        abort_unless($pengumuman->isPublished() || (request()->boolean('preview') && auth()->check()), 404);

        return view('frontend.pengumuman.show', [
            'pengumuman' => $pengumuman,
            'lainnya' => Pengumuman::published()
                ->where('id', '!=', $pengumuman->id)
                ->latest('published_at')
                ->take(5)
                ->get(),
        ]);
    }
}
