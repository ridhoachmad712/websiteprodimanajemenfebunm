<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class HomeBuilderController extends Controller
{
    /**
     * Tipe blok yang tersedia beserta metadata (label & ikon) untuk UI.
     *
     * @return array<string, array{label: string, icon: string}>
     */
    public static function types(): array
    {
        return [
            'features' => ['label' => 'Fitur / Pilar', 'icon' => 'ti-layout-grid'],
            'about' => ['label' => 'Tentang / Sambutan', 'icon' => 'ti-article'],
            'posts' => ['label' => 'Daftar Postingan', 'icon' => 'ti-news'],
            'pengumuman' => ['label' => 'Pengumuman', 'icon' => 'ti-speakerphone'],
            'dosen' => ['label' => 'Daftar Dosen', 'icon' => 'ti-users'],
            'mitra' => ['label' => 'Mitra (Logo Berjalan)', 'icon' => 'ti-heart-handshake'],
            'cta' => ['label' => 'Ajakan (CTA)', 'icon' => 'ti-speakerphone'],
            'video' => ['label' => 'Video (YouTube)', 'icon' => 'ti-brand-youtube'],
            'richtext' => ['label' => 'Konten Bebas (WYSIWYG)', 'icon' => 'ti-code'],
        ];
    }

    /**
     * Ambil ID video YouTube dari berbagai bentuk URL (atau ID langsung).
     */
    public static function youtubeId(?string $url): ?string
    {
        $url = trim((string) $url);
        if ($url === '') {
            return null;
        }

        if (preg_match('~(?:youtube\.com/(?:watch\?(?:.*&)?v=|embed/|shorts/|live/)|youtu\.be/)([A-Za-z0-9_-]{11})~', $url, $m)) {
            return $m[1];
        }

        // Admin menempel ID langsung (11 karakter).
        if (preg_match('~^[A-Za-z0-9_-]{11}$~', $url)) {
            return $url;
        }

        return null;
    }

    /**
     * Blok bawaan — mencerminkan tampilan beranda default. Dipakai bila admin
     * belum pernah menyimpan konfigurasi.
     *
     * @return array<int, array<string, mixed>>
     */
    public static function defaultBlocks(): array
    {
        return [
            ['type' => 'features', 'enabled' => true, 'data' => [
                'eyebrow' => 'Filosofi Kami', 'title' => 'Build · Manage · Integrate', 'center' => '1',
                'subtitle' => 'Tiga pilar yang menjadi pijakan pembelajaran dan pengembangan diri mahasiswa.',
                'items' => [
                    ['icon' => 'ti-tools', 'title' => 'Build', 'desc' => 'Membangun fondasi keilmuan manajemen yang kuat dan aplikatif.'],
                    ['icon' => 'ti-adjustments', 'title' => 'Manage', 'desc' => 'Mengelola sumber daya secara efektif, efisien, dan beretika.'],
                    ['icon' => 'ti-affiliate', 'title' => 'Integrate', 'desc' => 'Mengintegrasikan teori, praktik, dan teknologi untuk daya saing global.'],
                ],
            ]],
            ['type' => 'about', 'enabled' => true, 'data' => [
                'eyebrow' => 'Tentang Kami', 'title' => 'Pusat pendidikan manajemen berbasis kewirausahaan',
                'body' => 'Berdiri sejak 1999, Program Studi Manajemen FEB UNM berkomitmen menghasilkan sarjana manajemen yang profesional, adaptif, dan berdaya saing — dengan tiga konsentrasi: Keuangan, Pemasaran, dan Sumber Daya Manusia.',
                'image_url' => '', 'button_label' => 'Selengkapnya tentang prodi', 'button_url' => '/profil',
                'items' => [
                    ['text' => 'Akreditasi Baik Sekali (LAMEMBA)'],
                    ['text' => 'Kurikulum berbasis kewirausahaan'],
                    ['text' => 'Dosen kompeten & berpengalaman'],
                    ['text' => 'Jejaring alumni yang luas'],
                ],
            ]],
            ['type' => 'posts', 'enabled' => true, 'data' => [
                'eyebrow' => 'Kabar Terkini', 'title' => 'Berita & Informasi', 'category' => 'berita-informasi',
                'count' => '3', 'link_label' => 'Lihat Semua', 'link_url' => '/berita', 'style' => 'normal',
            ]],
            ['type' => 'dosen', 'enabled' => true, 'data' => [
                'eyebrow' => 'Tenaga Pengajar', 'title' => 'Dosen & Tendik',
                'count' => '6', 'link_label' => 'Semua Dosen', 'link_url' => '/daftar-dosen',
            ]],
            ['type' => 'posts', 'enabled' => true, 'data' => [
                'eyebrow' => 'Capaian', 'title' => 'Prestasi', 'category' => 'prestasi',
                'count' => '3', 'link_label' => 'Lihat Semua', 'link_url' => '/category/prestasi', 'style' => 'normal',
            ]],
            ['type' => 'posts', 'enabled' => true, 'data' => [
                'eyebrow' => 'Wawasan', 'title' => 'Artikel', 'category' => 'artikel',
                'count' => '3', 'link_label' => 'Lihat Semua', 'link_url' => '/category/artikel', 'style' => 'tint',
            ]],
            ['type' => 'pengumuman', 'enabled' => true, 'data' => [
                'eyebrow' => 'Penting', 'title' => 'Pengumuman',
                'count' => '4', 'link_label' => 'Semua Pengumuman', 'link_url' => '/pengumuman',
            ]],
            ['type' => 'mitra', 'enabled' => true, 'data' => [
                'eyebrow' => 'Kolaborasi', 'title' => 'Mitra & Kerjasama', 'style' => 'tint', 'grayscale' => '0',
            ]],
            ['type' => 'cta', 'enabled' => true, 'data' => [
                'title' => 'Tertarik bergabung dengan Prodi Manajemen?',
                'subtitle' => 'Pelajari profil, kurikulum, dan layanan akademik kami lebih lanjut.',
                'btn1_label' => 'Jelajahi Program Studi', 'btn1_url' => '/profil',
                'btn2_label' => 'Hubungi Kami', 'btn2_url' => '/hubungi-kami',
            ]],
        ];
    }

    /**
     * Blok beranda terkini (konfigurasi admin atau bawaan).
     *
     * @return array<int, array<string, mixed>>
     */
    public static function resolveBlocks(): array
    {
        $raw = Setting::get('home.blocks');

        if ($raw) {
            $decoded = json_decode($raw, true);
            if (is_array($decoded)) {
                return $decoded;
            }
        }

        return static::defaultBlocks();
    }

    public function edit(): View
    {
        return view('admin.home.edit', [
            'blocks' => static::resolveBlocks(),
            'types' => static::types(),
        ]);
    }

    public function update(Request $request): RedirectResponse
    {
        $allowed = array_keys(static::types());
        $blocks = [];

        foreach (array_values((array) $request->input('blocks', [])) as $raw) {
            $type = $raw['type'] ?? null;
            if (! in_array($type, $allowed, true)) {
                continue;
            }

            $data = is_array($raw['data'] ?? null) ? $raw['data'] : [];

            // Rapikan repeater "items": buang baris yang seluruhnya kosong.
            if (isset($data['items']) && is_array($data['items'])) {
                $data['items'] = array_values(array_filter($data['items'], function ($it) {
                    return is_array($it) && trim(implode('', array_map(fn ($x) => (string) $x, $it))) !== '';
                }));
            }

            // Sanitasi HTML konten bebas.
            if ($type === 'richtext') {
                $data['html'] = clean($data['html'] ?? '');
            }

            // Normalisasi URL/ID YouTube agar iframe frontend hanya memakai ID valid.
            if ($type === 'video') {
                $data['video_id'] = static::youtubeId($data['video_url'] ?? '');
            }

            $blocks[] = [
                'type' => $type,
                'enabled' => (string) ($raw['enabled'] ?? '1') === '1',
                'data' => $data,
            ];
        }

        Setting::set('home.blocks', json_encode($blocks, JSON_UNESCAPED_UNICODE), 'home');

        return redirect()->route('admin.home.edit')->with('status', 'Beranda berhasil diperbarui.');
    }
}
