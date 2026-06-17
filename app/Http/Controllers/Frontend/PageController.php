<?php

namespace App\Http\Controllers\Frontend;

use App\Http\Controllers\Controller;
use App\Models\Page;
use Illuminate\View\View;

class PageController extends Controller
{
    /**
     * Halaman Profil dengan 7 section + anchor navigation.
     */
    public function profil(): View
    {
        $page = Page::where('slug', 'profil')->firstOrFail();

        return view('frontend.pages.profil', compact('page'));
    }

    /**
     * Halaman statis generik berbasis slug.
     * Slug di-inject dari definisi rute (->defaults('slug', ...)).
     */
    public function show(string $slug): View
    {
        $page = Page::where('slug', $slug)->firstOrFail();

        return view('frontend.pages.show', compact('page'));
    }
}
