<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Mitra;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class MitraController extends Controller
{
    public function index(): View
    {
        return view('admin.mitra.index', [
            'mitra' => Mitra::orderBy('urutan')->orderBy('nama')->paginate(20),
        ]);
    }

    public function create(): View
    {
        return view('admin.mitra.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['logo'] = $request->file('logo')?->store('mitra', 'public');
        $data['aktif'] = $request->boolean('aktif');

        Mitra::create($data);

        return redirect()->route('admin.mitra.index')->with('status', 'Mitra berhasil ditambahkan.');
    }

    public function edit(Mitra $mitra): View
    {
        return view('admin.mitra.edit', ['mitra' => $mitra]);
    }

    public function update(Request $request, Mitra $mitra): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['aktif'] = $request->boolean('aktif');

        if ($request->hasFile('logo')) {
            if ($mitra->logo) {
                Storage::disk('public')->delete($mitra->logo);
            }
            $data['logo'] = $request->file('logo')->store('mitra', 'public');
        }

        $mitra->update($data);

        return redirect()->route('admin.mitra.index')->with('status', 'Mitra berhasil diperbarui.');
    }

    public function destroy(Mitra $mitra): RedirectResponse
    {
        if ($mitra->logo) {
            Storage::disk('public')->delete($mitra->logo);
        }
        $mitra->delete();

        return redirect()->route('admin.mitra.index')->with('status', 'Mitra berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'url' => ['nullable', 'url', 'max:2000'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
        ], [], ['url' => 'tautan']);
    }
}
