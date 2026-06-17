# Perencanaan Pembangunan Website Prodi Manajemen FEB UNM

> **Proyek:** Migrasi & pembangunan ulang website manajemenunm.com
> **Stack:** Laravel + Tabler.io (Bootstrap 5)
> **Arah desain:** Minimalis, modern, tetap fungsional
> **Lokasi template Tabler:** `D:\Project\Website Prodi Manajemen\tabler-dev`
> **Tanggal:** 17 Juni 2026

---

## 1. Tujuan & Ruang Lingkup

Membangun ulang website Program Studi Manajemen FEB UNM yang saat ini berbasis WordPress menjadi aplikasi Laravel yang lebih cepat, aman, dan mudah dikelola, dengan tampilan minimalis-modern menggunakan komponen Tabler.io.

**Dua bagian besar aplikasi:**
1. **Frontend Publik** — halaman yang dilihat pengunjung (profil, berita, dosen, dll).
2. **Backend / Panel Admin** — CMS internal untuk mengelola konten (pengganti wp-admin).

**Sasaran kualitas:**
- Waktu muat cepat (tanpa beban plugin WordPress).
- Konten sepenuhnya dapat dikelola admin (tidak ada lagi placeholder/Lorem ipsum).
- Responsif penuh (desktop, tablet, mobile).
- SEO-friendly dengan struktur URL yang dipertahankan.

---

## 2. Prinsip Desain (Minimalis · Modern · Fungsional)

| Prinsip | Penerapan |
|---------|-----------|
| **Minimalis** | Banyak ruang kosong (*whitespace*), palet warna terbatas (1 warna brand + netral), tipografi bersih, hindari elemen dekoratif berlebihan. |
| **Modern** | Sudut membulat halus, *shadow* lembut, transisi/animasi ringan, ikon garis (Tabler Icons), layout berbasis grid. |
| **Fungsional** | Navigasi jelas & konsisten, hierarki informasi rapi, CTA mudah ditemukan, aksesibilitas (kontras & label baik). |

**Sistem desain dasar (memanfaatkan Tabler):**
- **Warna brand:** ambil dari identitas UNM (mis. biru/oranye) — override variabel CSS Tabler `--tblr-primary`.
- **Tipografi:** font sans-serif modern (Inter / default Tabler), ukuran konsisten via skala Tabler.
- **Komponen:** gunakan Card, Badge, Button, Navbar, dan utility class Bootstrap 5 bawaan Tabler agar konsisten.
- **Dark mode:** Tabler mendukung mode gelap bawaan — opsional untuk panel admin.

> **Catatan strategi UI:** Tabler dirancang sebagai template dashboard. Komponennya (card, tabel, form, badge, tombol) sangat cocok untuk **panel admin**. Untuk **halaman publik**, kita pakai fondasi Bootstrap 5 + utility & komponen Tabler, lalu buat beberapa *section* kustom (hero, counter, grid dosen) agar tampilannya seperti situs profil, bukan dashboard.

---

## 3. Stack Teknologi

| Lapisan | Teknologi | Keterangan |
|---------|-----------|------------|
| Framework | **Laravel 11/12** | Backend & routing |
| Bahasa | PHP 8.2+ | |
| Template engine | **Blade** | View server-side |
| UI Kit | **Tabler.io** (Bootstrap 5) | Sudah tersedia di folder project |
| CSS/JS build | **Vite** | Bundling aset (bawaan Laravel) |
| Ikon | **Tabler Icons** (6.000+) | Konsisten dengan UI |
| Database | **MySQL / MariaDB** | |
| Autentikasi | **Laravel Breeze / Fortify** | Login admin |
| Otorisasi | **Spatie Laravel Permission** | Role & permission (opsional tapi disarankan) |
| Editor konten | **TinyMCE / CKEditor / Trix** | WYSIWYG untuk berita |
| Upload media | **Spatie Media Library** | Manajemen gambar/file (opsional) |

---

## 4. Arsitektur & Struktur Folder

```
project/
├── app/
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Frontend/        # Controller halaman publik
│   │   │   │   ├── HomeController.php
│   │   │   │   ├── PageController.php
│   │   │   │   ├── PostController.php
│   │   │   │   └── DosenController.php
│   │   │   └── Admin/           # Controller panel admin
│   │   │       ├── DashboardController.php
│   │   │       ├── PostController.php
│   │   │       ├── DosenController.php
│   │   │       ├── GalleryController.php
│   │   │       ├── MenuController.php
│   │   │       └── SettingController.php
│   │   ├── Middleware/
│   │   └── Requests/            # Form Request validation
│   └── Models/
│       ├── Post.php
│       ├── Category.php
│       ├── Dosen.php
│       ├── Gallery.php
│       ├── Menu.php
│       ├── Page.php
│       └── Setting.php
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── views/
│   │   ├── layouts/
│   │   │   ├── frontend.blade.php   # Master layout publik
│   │   │   └── admin.blade.php      # Master layout admin (Tabler dashboard)
│   │   ├── frontend/
│   │   │   ├── partials/            # header, footer, mega-menu, cards
│   │   │   ├── home.blade.php
│   │   │   ├── profil.blade.php
│   │   │   ├── dosen/
│   │   │   ├── posts/
│   │   │   └── pages/
│   │   └── admin/
│   │       ├── partials/            # sidebar, topbar
│   │       ├── dashboard.blade.php
│   │       ├── posts/
│   │       ├── dosen/
│   │       └── settings/
│   ├── css/
│   └── js/
├── public/
│   └── tabler/                      # Aset Tabler hasil salin/build dari D:\Project\Website Prodi Manajemen\tabler-dev\dist\
└── routes/
    └── web.php
```

---

## 5. Integrasi Tabler ke Laravel

> **Lokasi sumber template:** Folder Tabler tersedia di komputer lokal pada
> `D:\Project\Website Prodi Manajemen\tabler-dev`.
> Folder ini adalah sumber file Tabler (kemungkinan berisi `dist/`, `src/`, `preview/`, dan konfigurasi build seperti `package.json`). Aset yang siap pakai biasanya berada di subfolder **`dist/`** (`dist/css/`, `dist/js/`). Inilah yang akan disalin/di-build ke dalam project Laravel.

**Opsi A — Aset statis (paling sederhana, cocok jika template sudah berupa HTML/CSS/JS jadi):**
1. Salin isi `D:\Project\Website Prodi Manajemen\tabler-dev\dist\` ke `public/tabler/` di project Laravel.
2. Panggil di layout Blade:
   ```html
   <link href="{{ asset('tabler/css/tabler.min.css') }}" rel="stylesheet">
   <script src="{{ asset('tabler/js/tabler.min.js') }}"></script>
   ```
3. Convert halaman HTML Tabler menjadi layout & partial Blade (`@extends`, `@section`, `@yield`).

**Opsi B — Via NPM + Vite (lebih rapi untuk kustomisasi & override warna):**

Karena Anda sudah memiliki folder `tabler-dev` (versi *development/source*), Anda punya dua jalur:

*B-1. Build langsung dari folder `tabler-dev`* (jika ingin mengkustomisasi SCSS Tabler):
```bash
cd "D:\Project\Website Prodi Manajemen\tabler-dev"
npm install
npm run build          # menghasilkan folder dist/ yang sudah dikompilasi
```
Lalu salin hasil `dist/` ke `public/tabler/` project Laravel.

*B-2. Install paket Tabler ke project Laravel* (jika ingin dikelola lewat Vite Laravel):
```bash
npm install @tabler/core @tabler/icons
```
Lalu import di `resources/js/app.js` & `resources/css/app.scss`, dan override variabel SCSS Tabler untuk warna brand UNM.

> **Rekomendasi:** Gunakan **Opsi B** bila ingin mengubah warna/tema secara menyeluruh; **Opsi A** bila ingin cepat dan template sudah final.

**Langkah konversi template → Blade:**
1. Buat `layouts/admin.blade.php` dari satu halaman dashboard Tabler (ambil struktur sidebar + topbar).
2. Buat `layouts/frontend.blade.php` lebih ringan (tanpa sidebar dashboard, fokus navbar + footer).
3. Pisahkan elemen berulang jadi partial: `@include('admin.partials.sidebar')`, dst.
4. Ganti konten statis dengan data dinamis (`@foreach`, `{{ $variabel }}`).

---

## 6. Struktur Halaman

### 6.1 Frontend Publik (mengacu pada analisa situs lama)

| Halaman | Route | Sumber data |
|---------|-------|-------------|
| Beranda | `/` | Gabungan: post terbaru, dosen, statistik |
| Profil Program Studi | `/profil` | Page (statis) — sambutan, sejarah, visi, misi, tujuan, strategi, kompetensi |
| Daftar Dosen | `/daftar-dosen` | Model Dosen (4 kategori) |
| Detail Dosen | `/dosen/{slug}` | Model Dosen |
| Peta Proses Bisnis | `/sop-petaprosesbisnis` | Page |
| Akreditasi | `/akreditasi` | Page |
| Fasilitas | `/fasilitas` | Page |
| Galeri | `/gallery` | Model Gallery |
| Kalender Akademik | `/kalender-akademik` | Page |
| Daftar Seminar | `/daftar-seminar` | Konten dinamis |
| Prestasi | `/prestasi` | Post (kategori Prestasi) |
| HIMA Manajemen | `/hima` | Page |
| Alumni | `/alumni` | Page / Model |
| ICOMAN 2025 | `/icoman2025` | Page (landing event) |
| Hubungi Kami | `/hubungi-kami` | Page + form kontak |
| Daftar Berita | `/berita` | Post |
| Detail Berita | `/{tahun}/{bulan}/{tgl}/{slug}` | Post (pertahankan URL WP) |
| Arsip Kategori | `/category/{slug}` | Post per kategori |

**Komponen frontend kunci:**
- Mega-menu / nested dropdown (3 level) — replikasi navigasi lama.
- Footer 4 kolom (Kontak, Info Kemahasiswaan, Sumber Belajar, Tautan Penting).
- Hero dengan anchor navigation (Profil & Fasilitas).
- Counter statistik animasi.
- Card berita & card dosen (grid responsif).

### 6.2 Panel Admin (pengganti wp-admin)

| Menu Admin | Fungsi |
|------------|--------|
| Dashboard | Ringkasan statistik (jumlah post, dosen, dll) |
| Manajemen Berita | CRUD post + kategori + featured image (WYSIWYG) |
| Manajemen Dosen | CRUD dosen (nama, NIP, foto, konsentrasi, kategori, bio link) |
| Manajemen Halaman | Edit konten halaman statis (Profil, Akreditasi, dll) |
| Galeri | Upload & kelola media |
| Menu Builder | Atur struktur menu & tautan eksternal |
| Pengaturan Situs | Kontak, sosmed, tagline, statistik beranda, footer links |
| Manajemen User | Kelola admin & role (opsional) |

---

## 7. Desain Database (Ringkas)

Entitas utama (detail migration ada di dokumen analisa):

- **`posts`** — judul, slug, excerpt, konten, featured_image, user_id, published_at
- **`categories`** — Berita & Informasi, Artikel, Prestasi, Pengumuman
- **`category_post`** — pivot
- **`dosen`** — nama, slug, nip, foto, kategori (enum), konsentrasi, bio_link, urutan
- **`galleries`** — judul, gambar, kategori
- **`pages`** — slug, title, content (atau sections JSON) untuk halaman statis yang bisa diedit
- **`menus`** — struktur menu (parent_id untuk nesting, url, target, urutan)
- **`settings`** — key-value (kontak, sosmed, statistik, footer links)
- **`users`** — admin (Laravel default)

---

## 8. Fitur per Modul

**Modul Berita:**
- CRUD lengkap dengan editor WYSIWYG
- Multi-kategori per post
- Featured image + galeri dalam artikel
- Slug otomatis & URL `/YYYY/MM/DD/slug`
- Status draft/publish, jadwal publish
- Pencarian & filter

**Modul Dosen:**
- CRUD dengan upload foto
- Pengelompokan otomatis berdasarkan kategori (Guru Besar, Tetap Prodi, MKDU, Luar Biasa)
- Filter berdasarkan konsentrasi
- Pengaturan urutan tampil

**Modul Halaman Statis:**
- Editor untuk tiap section (mis. Profil punya 7 section)
- Hindari hardcode agar konten mudah diperbarui

**Modul Pengaturan:**
- Semua data footer & kontak dinamis
- Statistik beranda (jumlah mahasiswa/dosen) dapat diubah tanpa edit kode

---

## 9. Roadmap / Fase Pengembangan

### Fase 0 — Persiapan (1–2 hari)
- [ ] Setup project Laravel + koneksi database
- [ ] Integrasi aset Tabler ke `public/` atau via Vite
- [ ] Konversi 1 halaman Tabler → layout admin Blade
- [ ] Buat layout frontend dasar (navbar + footer)

### Fase 1 — Fondasi & Auth (2–3 hari)
- [ ] Migration semua tabel
- [ ] Seeder data awal (kategori, settings, contoh dosen)
- [ ] Autentikasi admin (Breeze/Fortify)
- [ ] Layout master frontend & admin final

### Fase 2 — Modul Inti (1–1.5 minggu)
- [ ] CRUD Berita + kategori (admin)
- [ ] CRUD Dosen (admin)
- [ ] Tampilan publik: Beranda, Daftar Dosen, Berita, Detail Berita

### Fase 3 — Halaman & Konten (1 minggu)
- [ ] Halaman Profil (lengkap section)
- [ ] Akreditasi, Fasilitas, Galeri, Kalender, HIMA, Alumni, Hubungi Kami
- [ ] Mega-menu dinamis (Menu Builder)
- [ ] Modul Pengaturan + footer dinamis

### Fase 4 — Penyempurnaan (3–5 hari)
- [ ] Responsif & uji lintas perangkat
- [ ] Optimasi SEO (meta tag, sitemap, redirect 301 dari URL lama)
- [ ] Optimasi performa (cache, lazy load gambar)
- [ ] Isi konten asli (ganti semua placeholder lama)

### Fase 5 — Deployment (1–2 hari)
- [ ] Setup server / hosting
- [ ] Migrasi data konten lama (berita & dosen)
- [ ] Pengujian akhir & go-live

> **Estimasi total:** ±4–6 minggu (tergantung ketersediaan konten & 1 developer).

---

## 10. Routing (Ringkas)

```php
// routes/web.php

// ===== FRONTEND =====
Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('home');
});

Route::controller(PageController::class)->group(function () {
    Route::get('/profil', 'profil');
    Route::get('/akreditasi', 'akreditasi');
    Route::get('/fasilitas', 'fasilitas');
    // ... halaman statis lain
});

Route::get('/daftar-dosen', [DosenController::class, 'index']);
Route::get('/dosen/{slug}', [DosenController::class, 'show']);

Route::get('/berita', [PostController::class, 'index']);
Route::get('/category/{slug}', [PostController::class, 'byCategory']);
Route::get('/{year}/{month}/{day}/{slug}', [PostController::class, 'show'])
    ->where(['year' => '\d{4}', 'month' => '\d{2}', 'day' => '\d{2}']);

// ===== ADMIN =====
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('posts', Admin\PostController::class);
    Route::resource('dosen', Admin\DosenController::class);
    Route::resource('gallery', Admin\GalleryController::class);
    Route::resource('menus', Admin\MenuController::class);
    Route::resource('pages', Admin\PageController::class);
    Route::get('/settings', [Admin\SettingController::class, 'edit'])->name('settings.edit');
    Route::put('/settings', [Admin\SettingController::class, 'update'])->name('settings.update');
});
```

---

## 11. Keamanan & Best Practice

- Validasi semua input lewat **Form Request**.
- Proteksi CSRF (bawaan Laravel) di semua form.
- Upload file: validasi tipe & ukuran, simpan di `storage/` + symbolic link.
- Gunakan **policy/gate** untuk membatasi akses admin.
- Sanitasi konten WYSIWYG untuk mencegah XSS.
- Backup database terjadwal.
- `.env` tidak masuk ke git; gunakan `APP_DEBUG=false` di produksi.

---

## 12. Catatan Migrasi dari WordPress

1. **Pertahankan URL berita** (`/YYYY/MM/DD/slug/`) atau siapkan **redirect 301** agar peringkat SEO & tautan lama tetap valid.
2. **Ekspor konten lama:** berita & data dosen dari WP bisa diekspor (XML/SQL) lalu di-*seed* ke database baru.
3. **Konten placeholder** (Kurikulum, Fasilitas, Alumni) di situs lama harus diisi konten asli — jangan ikut dimigrasikan.
4. **Tautan eksternal** (Google Forms, Drive, OJS) bisa dipertahankan, atau secara bertahap diganti modul internal Laravel.
5. **Perbaiki lokalisasi** ke `id_ID` (situs lama keliru set `en_US`).

---

## 13. Peluang Peningkatan dari Versi Lama

- Form layanan tugas akhir & TOEFL dibuat **native di Laravel** (tidak lagi tergantung Google Forms) → data terpusat & dapat dipantau admin.
- Direktori alumni interaktif dengan pencarian & filter.
- Dashboard admin dengan statistik real-time.
- Notifikasi email otomatis (mis. konfirmasi pendaftaran seminar).
- Manajemen menu fleksibel tanpa edit kode.

---

*Dokumen perencanaan ini bersifat panduan dan dapat disesuaikan dengan kebutuhan tim serta ketersediaan konten. Disarankan memvalidasi struktur database final setelah menelusuri seluruh halaman situs lama secara menyeluruh.*
