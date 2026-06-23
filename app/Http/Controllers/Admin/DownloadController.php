<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Download;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class DownloadController extends Controller
{
    public function index(Request $request): View
    {
        $query = Download::query();

        if ($cari = $request->query('cari')) {
            $query->where('judul', 'like', "%{$cari}%");
        }

        return view('admin.downloads.index', [
            'downloads' => $query->orderBy('kategori')->orderBy('urutan')->paginate(20)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.downloads.create');
    }

    public function store(Request $request): RedirectResponse
    {
        $data = $this->validateData($request);
        $data['file'] = $request->file('file')?->store('downloads', 'public');

        Download::create($data);

        return redirect()->route('admin.downloads.index')->with('status', 'Dokumen berhasil ditambahkan.');
    }

    public function edit(Download $download): View
    {
        return view('admin.downloads.edit', ['download' => $download]);
    }

    public function update(Request $request, Download $download): RedirectResponse
    {
        $data = $this->validateData($request);

        if ($request->hasFile('file')) {
            if ($download->file) {
                Storage::disk('public')->delete($download->file);
            }
            $data['file'] = $request->file('file')->store('downloads', 'public');
        }

        $download->update($data);

        return redirect()->route('admin.downloads.index')->with('status', 'Dokumen berhasil diperbarui.');
    }

    public function destroy(Download $download): RedirectResponse
    {
        if ($download->file) {
            Storage::disk('public')->delete($download->file);
        }
        $download->delete();

        return redirect()->route('admin.downloads.index')->with('status', 'Dokumen berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'judul' => ['required', 'string', 'max:255'],
            'kategori' => ['required', 'string', 'max:100'],
            'deskripsi' => ['nullable', 'string', 'max:2000'],
            'file' => ['nullable', 'file', 'mimes:pdf,doc,docx,xls,xlsx,ppt,pptx,zip,jpg,jpeg,png', 'max:20480'],
            'url' => ['nullable', 'url', 'max:2000'],
            'urutan' => ['nullable', 'integer', 'min:0', 'max:9999'],
            'status' => ['required', Rule::in(['published', 'draft'])],
        ], [], ['url' => 'tautan']);
    }
}
