<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\HeroSlide;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class HeroSlideController extends Controller
{
    public function index(): View
    {
        return view('admin.hero-slides.index', [
            'slides' => HeroSlide::orderBy('urutan')->orderBy('id')->get(),
        ]);
    }

    public function create(): View
    {
        return view('admin.hero-slides.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request, true);
        $data['gambar'] = $request->file('gambar')->store('hero', 'public');
        $data['aktif'] = $request->boolean('aktif');

        HeroSlide::create($data);

        return redirect()->route('admin.hero-slides.index')->with('status', 'Slide berhasil ditambahkan.');
    }

    public function edit(HeroSlide $hero_slide): View
    {
        return view('admin.hero-slides.edit', ['slide' => $hero_slide]);
    }

    public function update(Request $request, HeroSlide $hero_slide): RedirectResponse
    {
        $data = $this->validateData($request, false);
        $data['aktif'] = $request->boolean('aktif');

        if ($request->hasFile('gambar')) {
            if ($hero_slide->gambar) {
                Storage::disk('public')->delete($hero_slide->gambar);
            }
            $data['gambar'] = $request->file('gambar')->store('hero', 'public');
        }

        $hero_slide->update($data);

        return redirect()->route('admin.hero-slides.index')->with('status', 'Slide berhasil diperbarui.');
    }

    public function destroy(HeroSlide $hero_slide): RedirectResponse
    {
        if ($hero_slide->gambar) {
            Storage::disk('public')->delete($hero_slide->gambar);
        }
        $hero_slide->delete();

        return redirect()->route('admin.hero-slides.index')->with('status', 'Slide berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request, bool $imageRequired): array
    {
        return $request->validate([
            'gambar' => [$imageRequired ? 'required' : 'nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'judul' => ['nullable', 'string', 'max:255'],
            'subjudul' => ['nullable', 'string', 'max:500'],
            'btn_label' => ['nullable', 'required_with:btn_url', 'string', 'max:60'],
            'btn_url' => ['nullable', 'required_with:btn_label', 'not_in:#', 'string', 'max:2000'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [], ['gambar' => 'gambar slide']);
    }
}
