<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SettingController extends Controller
{
    /**
     * Definisi field pengaturan yang dapat diedit, dikelompokkan untuk form.
     *
     * @var array<string, array<string, array{label: string, type?: string}>>
     */
    private array $fields = [
        'umum' => [
            'site.nama' => ['label' => 'Nama Situs'],
            'site.tagline' => ['label' => 'Tagline'],
            'site.tagline_brand' => ['label' => 'Tagline Brand'],
        ],
        'kontak' => [
            'kontak.alamat' => ['label' => 'Alamat', 'type' => 'textarea'],
            'kontak.telepon' => ['label' => 'Telepon'],
            'kontak.email' => ['label' => 'Email'],
        ],
        'sosmed' => [
            'sosmed.instagram' => ['label' => 'Instagram (URL)'],
            'sosmed.tiktok' => ['label' => 'TikTok (URL)'],
            'sosmed.facebook' => ['label' => 'Facebook (URL)'],
        ],
        'statistik' => [
            'statistik.mahasiswa' => ['label' => 'Jumlah Mahasiswa'],
            'statistik.dosen' => ['label' => 'Jumlah Dosen'],
        ],
        'jadwal' => [
            'jadwal_ujian.file_url' => ['label' => 'URL Google Sheet Jadwal Ujian', 'hint' => 'Tautan berbagi file (pastikan dapat diakses publik / "siapa saja dengan link").'],
            'jadwal_ujian.sheet_number' => ['label' => 'Nomor Sheet', 'hint' => 'Sheet ke-berapa di dalam file (default 1).'],
            'jadwal_ujian.header_row' => ['label' => 'Baris Header', 'hint' => 'Baris yang berisi judul kolom (default 4).'],
        ],
    ];

    public function edit(): View
    {
        $values = Setting::pluck('value', 'key')->all();

        return view('admin.settings.edit', [
            'groups' => $this->fields,
            'values' => $values,
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        // Kumpulkan semua key valid dari definisi field.
        $keys = collect($this->fields)->flatMap(fn ($g) => array_keys($g))->all();

        $request->validate(
            collect($keys)->mapWithKeys(fn ($k) => [$this->inputName($k) => ['nullable', 'string', 'max:1000']])->all()
        );

        foreach ($this->fields as $group => $items) {
            foreach ($items as $key => $meta) {
                Setting::set($key, $request->input($this->inputName($key)), $group);
            }
        }

        return redirect()->route('admin.settings.edit')->with('status', 'Pengaturan berhasil disimpan.');
    }

    /**
     * Nama input form untuk sebuah key (titik → underscore).
     */
    private function inputName(string $key): string
    {
        return str_replace('.', '_', $key);
    }
}
