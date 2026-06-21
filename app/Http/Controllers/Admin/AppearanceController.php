<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class AppearanceController extends Controller
{
    /** Key bertipe toggle (checkbox → '1'/'0'). */
    private array $toggles = [
        'navbar.sticky', 'navbar.show_topbar', 'navbar.menu_center',
        'hero.show', 'hero.stats_show',
    ];

    /** Key bertipe gambar (upload). */
    private array $images = ['navbar.logo', 'hero.bg_image'];

    /** Key bertipe teks biasa. */
    private array $texts = [
        'navbar.brand_text', 'navbar.brand_subtext', 'navbar.brand_mark',
        'hero.eyebrow', 'hero.title', 'hero.title_accent', 'hero.subtitle',
        'hero.bg_style', 'hero.bg_color', 'hero.overlay', 'hero.height',
        'hero.btn1_label', 'hero.btn1_url', 'hero.btn2_label', 'hero.btn2_url',
        'hero.accreditation',
        'statistik.mahasiswa', 'statistik.dosen', 'statistik.konsentrasi',
        'theme.primary', 'theme.dark',
        'footer.about', 'footer.copyright', 'footer.tagline',
    ];

    /**
     * Nilai default yang ditampilkan bila setting belum diisi — sekaligus
     * jadi acuan tampilan default di frontend (lihat navbar/home blade).
     *
     * @return array<string, string>
     */
    public static function defaults(): array
    {
        return [
            'navbar.brand_text' => 'Manajemen',
            'navbar.brand_subtext' => 'FEB UNM',
            'navbar.brand_mark' => 'M',
            'navbar.sticky' => '1',
            'navbar.show_topbar' => '1',
            'navbar.menu_center' => '1',
            'navbar.cta_label' => 'Hubungi Kami',
            'navbar.cta_url' => '/hubungi-kami',

            'hero.show' => '1',
            'hero.eyebrow' => 'Forever in Brotherhood',
            'hero.title' => 'Build, Manage,',
            'hero.title_accent' => 'Integrate',
            'hero.subtitle' => 'Program Studi Manajemen, Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar — mencetak lulusan unggul, berdaya saing global, dan berjiwa kewirausahaan.',
            'hero.bg_style' => 'gradient',
            'hero.bg_color' => '#1b3a5b',
            'hero.overlay' => '55',
            'hero.height' => '0',
            'hero.btn1_label' => 'Profil Program Studi',
            'hero.btn1_url' => '/profil',
            'hero.btn2_label' => 'Informasi Terbaru',
            'hero.btn2_url' => '/berita',
            'hero.stats_show' => '1',
            'hero.accreditation' => 'Terakreditasi Baik Sekali — LAMEMBA',

            'statistik.mahasiswa' => '0',
            'statistik.dosen' => '0',
            'statistik.konsentrasi' => '3',

            'theme.primary' => '#1b3a5b',
            'theme.dark' => '#0e2238',

            'footer.about' => '',
            'footer.copyright' => '© {year} Program Studi Manajemen FEB UNM. Hak cipta dilindungi.',
            'footer.tagline' => 'Forever in Brotherhood · Build — Manage — Integrate',
        ];
    }

    /**
     * Tombol header bawaan (dipakai bila admin belum menyimpan).
     *
     * @return array<int, array{label: string, url: string, color: string}>
     */
    public static function defaultNavbarButtons(): array
    {
        return [
            ['label' => 'Hubungi Kami', 'url' => '/hubungi-kami', 'color' => 'primary'],
        ];
    }

    /**
     * Tombol header terkini (konfigurasi admin atau bawaan).
     *
     * @return array<int, array<string, mixed>>
     */
    public static function navbarButtons(): array
    {
        $raw = Setting::get('navbar.buttons');

        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return static::defaultNavbarButtons();
    }

    /**
     * Kolom tautan footer bawaan (dipakai bila admin belum menyimpan).
     *
     * @return array<int, array{title: string, links: array<int, array{label: string, url: string}>}>
     */
    public static function defaultFooterColumns(): array
    {
        return [
            ['title' => 'Info Kemahasiswaan', 'links' => [
                ['label' => 'Pusat Prestasi Nasional', 'url' => '#'],
                ['label' => 'IISMA', 'url' => '#'],
                ['label' => 'Kampus Mengajar', 'url' => '#'],
                ['label' => 'Wirausaha Merdeka', 'url' => '#'],
            ]],
            ['title' => 'Sumber Belajar', 'links' => [
                ['label' => 'Pustaka UNM', 'url' => '#'],
                ['label' => 'OJS UNM', 'url' => '#'],
                ['label' => 'Repositori (eprints)', 'url' => '#'],
                ['label' => 'OER UNM', 'url' => '#'],
            ]],
            ['title' => 'Tautan Penting', 'links' => [
                ['label' => 'PDDIKTI', 'url' => '#'],
                ['label' => 'UNM', 'url' => '#'],
                ['label' => 'Beasiswa Pendidikan Indonesia', 'url' => '#'],
            ]],
        ];
    }

    /**
     * Kolom footer terkini (konfigurasi admin atau bawaan).
     *
     * @return array<int, array<string, mixed>>
     */
    public static function footerColumns(): array
    {
        $raw = Setting::get('footer.columns');

        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return static::defaultFooterColumns();
    }

    public function edit(): View
    {
        $defaults = static::defaults();
        $keys = array_merge($this->toggles, $this->images, $this->texts);

        $v = collect($keys)
            ->mapWithKeys(fn ($k) => [$k => Setting::get($k, $defaults[$k] ?? '')])
            ->all();

        return view('admin.appearance.edit', [
            'v' => $v,
            'navbarButtons' => static::navbarButtons(),
            'footerColumns' => static::footerColumns(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $request->validate([
            'navbar_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,svg', 'max:2048'],
            'hero_bg_image' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp', 'max:4096'],
            'hero_bg_color' => ['nullable', 'string', 'max:20'],
            'hero_overlay' => ['nullable', 'integer', 'min:0', 'max:90'],
            'hero_height' => ['nullable', 'integer', 'min:0', 'max:1200'],
            'theme_primary' => ['nullable', 'string', 'max:20'],
            'theme_dark' => ['nullable', 'string', 'max:20'],
        ]);

        foreach ($this->toggles as $key) {
            Setting::set($key, $request->boolean($this->name($key)) ? '1' : '0', $this->group($key));
        }

        foreach ($this->texts as $key) {
            Setting::set($key, $request->input($this->name($key)), $this->group($key));
        }

        foreach ($this->images as $key) {
            $name = $this->name($key);

            if ($request->boolean($name.'_remove')) {
                $this->deleteImage($key);
                Setting::set($key, null, $this->group($key));
            } elseif ($request->hasFile($name)) {
                $this->deleteImage($key);
                Setting::set($key, $request->file($name)->store('tampilan', 'public'), $this->group($key));
            }
        }

        // Tombol header (label, url, warna).
        $buttons = [];
        foreach (array_values((array) $request->input('navbar_buttons', [])) as $b) {
            $label = trim($b['label'] ?? '');
            if ($label !== '') {
                $buttons[] = [
                    'label' => $label,
                    'url' => trim($b['url'] ?? '') ?: '#',
                    'color' => $b['color'] ?? 'primary',
                ];
            }
        }
        Setting::set('navbar.buttons', json_encode($buttons, JSON_UNESCAPED_UNICODE), 'navbar');

        // Kolom tautan footer (nested: kolom → tautan).
        $columns = [];
        foreach (array_values((array) $request->input('footer_columns', [])) as $col) {
            $title = trim($col['title'] ?? '');
            $links = [];
            foreach (array_values((array) ($col['links'] ?? [])) as $l) {
                $label = trim($l['label'] ?? '');
                if ($label !== '') {
                    $links[] = ['label' => $label, 'url' => trim($l['url'] ?? '') ?: '#'];
                }
            }
            if ($title !== '' || $links) {
                $columns[] = ['title' => $title, 'links' => $links];
            }
        }
        Setting::set('footer.columns', json_encode($columns, JSON_UNESCAPED_UNICODE), 'footer');

        return redirect()->route('admin.appearance.edit')->with('status', 'Tampilan berhasil diperbarui.');
    }

    private function deleteImage(string $key): void
    {
        if ($old = Setting::get($key)) {
            Storage::disk('public')->delete($old);
        }
    }

    /** Nama input form untuk sebuah key (titik → underscore). */
    private function name(string $key): string
    {
        return str_replace('.', '_', $key);
    }

    /** Grup penyimpanan = segmen pertama key. */
    private function group(string $key): string
    {
        return explode('.', $key)[0];
    }
}
