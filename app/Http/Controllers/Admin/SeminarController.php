<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Seminar;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\View\View;

class SeminarController extends Controller
{
    public function index(Request $request): View
    {
        $query = Seminar::query();

        if ($cari = $request->query('cari')) {
            $query->where(fn ($q) => $q->where('nama', 'like', "%{$cari}%")
                ->orWhere('judul', 'like', "%{$cari}%")
                ->orWhere('nim', 'like', "%{$cari}%"));
        }

        return view('admin.seminar.index', [
            'seminar' => $query->orderByDesc('tanggal')->paginate(15)->withQueryString(),
        ]);
    }

    public function create(): View
    {
        return view('admin.seminar.create');
    }

    public function store(Request $request): RedirectResponse
    {
        Seminar::create($this->validateData($request));

        return redirect()->route('admin.seminar.index')->with('status', 'Seminar berhasil ditambahkan.');
    }

    public function edit(Seminar $seminar): View
    {
        return view('admin.seminar.edit', ['seminar' => $seminar]);
    }

    public function update(Request $request, Seminar $seminar): RedirectResponse
    {
        $seminar->update($this->validateData($request));

        return redirect()->route('admin.seminar.index')->with('status', 'Seminar berhasil diperbarui.');
    }

    public function destroy(Seminar $seminar): RedirectResponse
    {
        $seminar->delete();

        return redirect()->route('admin.seminar.index')->with('status', 'Seminar berhasil dihapus.');
    }

    /**
     * @return array<string, mixed>
     */
    private function validateData(Request $request): array
    {
        return $request->validate([
            'nama' => ['required', 'string', 'max:255'],
            'nim' => ['nullable', 'string', 'max:50'],
            'judul' => ['required', 'string', 'max:1000'],
            'jenis' => ['required', Rule::in(array_keys(Seminar::jenisOptions()))],
            'tanggal' => ['required', 'date'],
            'tempat' => ['nullable', 'string', 'max:255'],
            'pembimbing' => ['nullable', 'string', 'max:255'],
            'penguji' => ['nullable', 'string', 'max:255'],
            'status' => ['required', Rule::in(['published', 'draft'])],
        ], [], ['nama' => 'nama mahasiswa']);
    }
}
