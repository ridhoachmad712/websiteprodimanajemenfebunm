<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\UpdatePageRequest;
use App\Models\Page;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PageController extends Controller
{
    public function index(): View
    {
        return view('admin.pages.index', [
            'pages' => Page::orderBy('title')->get(),
        ]);
    }

    public function edit(Page $page): View
    {
        // Halaman ber-section (mis. Profil) memakai editor section khusus.
        $view = $page->sections ? 'admin.pages.edit-sections' : 'admin.pages.edit';

        return view($view, compact('page'));
    }

    public function update(UpdatePageRequest $request, Page $page): RedirectResponse
    {
        $data = ['title' => $request->validated('title')];

        if ($page->sections) {
            // Gabungkan input section ke struktur tersimpan (pertahankan urutan kunci).
            $sections = $page->sections;
            foreach ($request->input('sections', []) as $key => $val) {
                if (isset($sections[$key])) {
                    $sections[$key]['judul'] = $val['judul'] ?? $sections[$key]['judul'] ?? '';
                    $sections[$key]['isi']   = clean($val['isi'] ?? ''); // sanitasi HTML
                }
            }
            $data['sections'] = $sections;
        } else {
            $data['content'] = clean($request->validated('content') ?? ''); // sanitasi HTML
        }

        $page->update($data);

        return redirect()->route('admin.pages.index')
            ->with('status', 'Halaman "'.$page->title.'" berhasil diperbarui.');
    }
}
