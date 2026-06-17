# Analisa Konten & Struktur Website — manajemenunm.com

> **Tujuan dokumen:** Referensi untuk membangun ulang Website Program Studi Manajemen FEB UNM dari WordPress ke Laravel.
> **Sumber:** Hasil analisa langsung pada [https://manajemenunm.com](https://manajemenunm.com)
> **Tanggal analisa:** 17 Juni 2026

---

## 1. Ringkasan Eksekutif

Website ini adalah situs resmi **Program Studi Manajemen, Fakultas Ekonomi dan Bisnis, Universitas Negeri Makassar (FEB UNM)**. Karakter situs adalah *company profile akademik* yang dipadukan dengan portal berita/informasi dan pusat tautan layanan (banyak mengarah ke Google Forms, Google Drive, dan sistem eksternal UNM).

Secara fungsi, situs terbagi menjadi tiga kelompok besar:

1. **Halaman statis (company profile):** Profil prodi, daftar dosen, akreditasi, fasilitas, galeri, dll.
2. **Konten dinamis (berita/artikel):** Post dengan kategori Berita & Informasi, Artikel, Prestasi, Pengumuman.
3. **Tautan layanan eksternal:** Banyak menu mengarah ke Google Forms (layanan tugas akhir, TOEFL), Google Drive (SK Mengajar, RPS), OJS (jurnal), dan subdomain UNM (SYAM-OK, Tracer Study).

**Catatan penting:** Beberapa halaman masih berupa *placeholder* (belum ada konten asli) — lihat Bagian 7.

---

## 2. Identitas & Informasi Teknis

| Item | Detail |
|------|--------|
| Nama situs | Website Manajemen FEB UNM / Prodi Manajemen FEB UNM |
| Tagline utama | "Forever in Brotherhood" |
| Tagline brand | "Build – Manage – Integrate" |
| CMS saat ini | WordPress 7.0 |
| Hosting | Hostinger |
| Bahasa | Indonesia (locale di-set `en_US`, perlu dikoreksi ke `id_ID`) |
| LMS terkait | SYAM-OK (`syam-ok.unm.ac.id`) |

**Informasi kontak (footer):**
- Alamat: Kampus Gunung Sari, Jl. A.P Pettarani – Jl. Pendidikan, Makassar
- Telepon: 082 293 000 192
- Email: manajemen_fe@unm.ac.id

**Media sosial:**
- Instagram: [@manajemen.febunm](https://www.instagram.com/manajemen.febunm/)
- TikTok: [@manajemenfebunm](https://www.tiktok.com/@manajemenfebunm)
- Facebook: (tertaut tapi belum aktif / `#`)

---

## 3. Struktur Navigasi Lengkap (Peta Menu)

Navigasi utama terdiri dari **8 menu utama**, beberapa dengan dropdown bertingkat (hingga 3 level). Ini adalah tulang punggung yang harus direplikasi di Laravel.

### 3.1 Beranda
`/` — halaman depan

### 3.2 Profil (dropdown)
| Sub-menu | URL | Tipe |
|----------|-----|------|
| Profil Program Studi | `/profil/` | Halaman statis |
| Daftar Dosen | `/daftar-dosen/` | Data terstruktur (CPT Dosen) |
| Peta Proses Bisnis Fakultas | `/sop-petaprosesbisnis/` | Halaman statis |
| Akreditasi | `/akreditasi/` | Halaman statis |
| Fasilitas | `/fasilitas/` | Halaman statis |
| Galeri | `/gallery/` | Galeri media |

### 3.3 Akademik (dropdown)
| Sub-menu | URL | Tipe |
|----------|-----|------|
| Kurikulum | `/kurikulum/` | Halaman statis *(placeholder)* |
| Kalender Akademik | `/kalender-akademik/` | Halaman statis |
| Jadwal Perkuliahan | `#` | Belum ada / kosong |
| Jadwal Seminar & Ujian Skripsi | Google Docs (eksternal) | Tautan eksternal |
| **Layanan Akademik** (sub-dropdown) | | |
| → Pendaftaran Tes TOEFL | Google Form | Tautan eksternal |
| Daftar Seminar | `/daftar-seminar/` | Konten dinamis |
| **Layanan Tugas Akhir** (sub-dropdown) | | |
| → Surat Izin Pra Penelitian | Google Form | Tautan eksternal |
| → Surat Izin Penelitian | Google Form | Tautan eksternal |
| → Surat Undangan Seminar | Google Form | Tautan eksternal |
| → Surat Usulan Ujian Skripsi | Google Form | Tautan eksternal |
| → Form Pendaftaran Seminar Proposal | Google Form | Tautan eksternal |
| → Form Pendaftaran Seminar Hasil | Google Form | Tautan eksternal |
| → Upload Berkas Pendaftaran Skripsi | Google Form | Tautan eksternal |
| → Upload Berkas Arsip Seminar & Ujian | Google Form | Tautan eksternal |
| Prestasi | `/prestasi/` | Arsip post (kategori Prestasi) |

### 3.4 Kemahasiswaan (dropdown)
| Sub-menu | URL | Tipe |
|----------|-----|------|
| HIMA Manajemen | `/hima/` | Halaman statis |
| KMM Asy Asyaamil | `#` | Belum ada / kosong |

### 3.5 Alumni (dropdown)
| Sub-menu | URL | Tipe |
|----------|-----|------|
| Direktori Alumni | `/alumni/` | Halaman statis *(placeholder)* |
| Pusat Karir UNM | `#` | Belum ada / kosong |
| **Tracer Study** (sub-dropdown) | | |
| → Tracer Study Universitas | `tracerstudy.unm.ac.id` | Tautan eksternal |
| → Tracer Study Prodi | Google Form | Tautan eksternal |

### 3.6 Jurnal (dropdown)
| Sub-menu | URL | Tipe |
|----------|-----|------|
| ICOMAN 2025 | `/icoman2025/` | Halaman statis (landing event) |
| Jurnal | `ojs.unm.ac.id/manajemen` | Tautan eksternal (OJS) |

### 3.7 Download (dropdown)
| Sub-menu | URL | Tipe |
|----------|-----|------|
| **SK Mengajar** (sub-dropdown) | | |
| → Semester Ganjil | Google Drive | Tautan eksternal |
| → Semester Genap | Google Drive | Tautan eksternal |
| **RPS** (sub-dropdown) | | |
| → Semester Ganjil | Google Drive | Tautan eksternal |
| → Semester Genap | Google Drive | Tautan eksternal |

### 3.8 Hubungi Kami
`/hubungi-kami/` — halaman kontak

---

## 4. Analisa Konten Per-Halaman

### 4.1 Beranda (`/`)
Halaman landing dengan beberapa *section*:
1. **Hero** — judul "Build, Manage, Integrate-" + sambutan singkat + 2 tombol CTA (Profil Program Studi, Informasi Terbaru).
2. **Pengantar Prodi** — kutipan visi + foto & nama Ketua Prodi (Dr. Anwar, S.E., M.Si.).
3. **3 Pilar Brand** — Build / Manage / Integrate (masing-masing dengan deskripsi & tautan).
4. **Statistik** — counter angka: 2000+ mahasiswa aktif, 60+ dosen & tendik.
5. **Berita & Informasi** — 3 post terbaru (kartu dengan gambar + tanggal).
6. **Dosen & Tendik** — preview daftar dosen + tombol "Lihat Semua Dosen".
7. **Prestasi** — daftar post kategori Prestasi.
8. **Artikel** — preview post kategori Artikel.
9. **Pengumuman** — daftar post kategori Pengumuman.
10. **Informasi Lainnya** — daftar artikel campuran.
11. **Footer** — kontak, Info Kemahasiswaan, Sumber Belajar, Tautan Penting, sosial media.

### 4.2 Profil Program Studi (`/profil/`)
Halaman terlengkap, berisi *anchor navigation* ke section:
- **Sambutan Ketua Program Studi** (foto + teks sambutan Dr. Anwar)
- **Sejarah Program Studi** — timeline: 1999 → 2006 → 2007 → Sekarang (berdasarkan SK Dikti)
- **Visi** — pusat pendidikan manajemen berbasis kewirausahaan, berdaya saing global, target 2030
- **Misi** — 4 poin (tridharma, riset, pengabdian, kerja sama)
- **Tujuan** — 4 poin
- **Strategi** — 5 poin
- **Kompetensi Lulusan** — peluang kerja (3 poin) + kompetensi lulusan (14 poin)

> Section dengan *anchor link*: `#sambutan`, `#sejarah`, `#visi`, `#misi`, `#tujuan`, `#strategi`, `#kompetensi`.

### 4.3 Daftar Dosen (`/daftar-dosen/`)
Data terstruktur — **dikelompokkan dalam 4 kategori**:

1. **Guru Besar (Professor)** — 5 orang
2. **Dosen Tetap Program Studi** — ±40 orang
3. **Dosen Tetap MKDU** — 5 orang (Kewarganegaraan, Bahasa Inggris, Pend. Agama Islam, Bahasa Indonesia)
4. **Dosen Luar Biasa** — ±9 orang

Setiap dosen memiliki atribut:
- Foto
- Nama lengkap + gelar
- NIP (sebagian belum ada → tanda "–")
- **Konsentrasi** (Manajemen Keuangan / Manajemen Pemasaran / Manajemen SDM / atau mata kuliah MKDU)
- **Bio Link** (mengarah ke subdomain `biomanaj.web.id/{slug}`)

> Ini kandidat kuat untuk dijadikan **tabel database `dosen`** di Laravel.

### 4.4 Akreditasi (`/akreditasi/`)
Konten utama: Prodi terakreditasi **"BAIK SEKALI"** — SK LAMEMBA No.471/DE/A.5/AR.10/VI/2023. Disertai logo/sertifikat LAMEMBA.

### 4.5 Fasilitas (`/fasilitas/`)
4 section dengan anchor: Perpustakaan (`#perpustakaan`), Taman Bermain (`#taman`), Ruang Kelas (`#ruangkelas`), Full Wifi (`#wifi`).
> ⚠️ Konten masih **Lorem ipsum** (placeholder) — perlu konten asli.

### 4.6 Alumni (`/alumni/`)
Struktur: hero + "Silaturahmi alumni semua angkatan" + "Artikel Alumni".
> ⚠️ Konten masih placeholder ("Univ Lorem").

### 4.7 Kurikulum (`/kurikulum/`)
> ⚠️ Halaman masih menampilkan **halaman default Hostinger** ("You Are All Set to Go!") — belum dibangun sama sekali.

### 4.8 Halaman Post / Berita (contoh: artikel CIMR's 9th Conference)
Struktur post standar:
- Breadcrumb: `Home » Kategori » Judul`
- Judul
- Meta: penulis (`gpuser`), tanggal publish & modified, estimasi waktu baca
- Featured image
- Isi artikel
- **Kategori yang teridentifikasi:** Berita & Informasi (cat ID 19), Artikel, Prestasi, Pengumuman

### 4.9 Halaman lain (belum ditelusuri detail, namun ada di menu)
Peta Proses Bisnis Fakultas, Galeri, Kalender Akademik, Daftar Seminar, HIMA Manajemen, ICOMAN 2025, Hubungi Kami.

---

## 5. Pemetaan Jenis Konten (untuk Model/Tabel Laravel)

Dari analisa di atas, konten dapat dipetakan menjadi entitas berikut:

| Entitas | Sumber WP | Saran Implementasi Laravel |
|---------|-----------|----------------------------|
| **Halaman statis** | WP Pages | Tabel `pages` (slug, title, content, sections JSON) atau view Blade khusus |
| **Post/Berita** | WP Posts | Tabel `posts` + `categories` (relasi many-to-many) |
| **Kategori** | WP Categories | `categories`: Berita & Informasi, Artikel, Prestasi, Pengumuman |
| **Dosen** | Custom (manual di WP) | Tabel `dosen` (nama, gelar, nip, foto, konsentrasi, kategori, bio_link) |
| **Kategori Dosen** | — | enum/tabel: Guru Besar, Dosen Tetap Prodi, Dosen MKDU, Dosen Luar Biasa |
| **Konsentrasi** | — | enum: Manajemen Keuangan, Pemasaran, SDM |
| **Galeri** | WP Media | Tabel `galleries` / `gallery_items` |
| **Menu & Tautan eksternal** | WP Menu | Tabel `menus` (mendukung nested + link eksternal) |
| **Statistik beranda** | Hardcoded | Tabel `settings` atau `stats` (jumlah mahasiswa, dosen) |
| **Pengaturan situs** | WP Options | Tabel `settings` (kontak, sosmed, tagline) |

---

## 6. Rekomendasi Struktur Laravel

### 6.1 Saran Routing (`web.php`)
```php
// Halaman statis
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/profil', [PageController::class, 'profil'])->name('profil');
Route::get('/akreditasi', [PageController::class, 'akreditasi']);
Route::get('/fasilitas', [PageController::class, 'fasilitas']);
Route::get('/sop-petaprosesbisnis', [PageController::class, 'sop']);
Route::get('/kalender-akademik', [PageController::class, 'kalender']);
Route::get('/kurikulum', [PageController::class, 'kurikulum']);
Route::get('/hima', [PageController::class, 'hima']);
Route::get('/alumni', [PageController::class, 'alumni']);
Route::get('/icoman2025', [PageController::class, 'icoman']);
Route::get('/hubungi-kami', [PageController::class, 'kontak']);

// Dosen
Route::get('/daftar-dosen', [DosenController::class, 'index'])->name('dosen.index');
Route::get('/dosen/{slug}', [DosenController::class, 'show'])->name('dosen.show');

// Galeri
Route::get('/gallery', [GalleryController::class, 'index']);

// Post / Berita (struktur URL WP: /YYYY/MM/DD/slug/)
Route::get('/berita', [PostController::class, 'index'])->name('post.index');
Route::get('/category/{category}', [PostController::class, 'byCategory']);
Route::get('/prestasi', [PostController::class, 'prestasi']);
Route::get('/{year}/{month}/{day}/{slug}', [PostController::class, 'show'])
    ->where(['year' => '\d{4}', 'month' => '\d{2}', 'day' => '\d{2}']);
```

> **Penting (SEO):** Pertahankan pola URL WordPress (`/YYYY/MM/DD/slug/`) agar tautan lama & indeks Google tidak rusak. Jika diubah, siapkan redirect 301.

### 6.2 Saran Migration `dosen`
```php
Schema::create('dosen', function (Blueprint $table) {
    $table->id();
    $table->string('nama');
    $table->string('slug')->unique();
    $table->string('nip')->nullable();
    $table->string('foto')->nullable();
    $table->enum('kategori', ['guru_besar', 'tetap_prodi', 'mkdu', 'luar_biasa']);
    $table->string('konsentrasi')->nullable(); // Keuangan / Pemasaran / SDM / MKDU
    $table->string('bio_link')->nullable();
    $table->integer('urutan')->default(0);
    $table->timestamps();
});
```

### 6.3 Saran Migration `posts` & `categories`
```php
Schema::create('categories', function (Blueprint $table) {
    $table->id();
    $table->string('nama');      // Berita & Informasi, Artikel, Prestasi, Pengumuman
    $table->string('slug')->unique();
    $table->timestamps();
});

Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('judul');
    $table->string('slug')->unique();
    $table->text('excerpt')->nullable();
    $table->longText('konten');
    $table->string('featured_image')->nullable();
    $table->foreignId('user_id')->constrained();
    $table->timestamp('published_at')->nullable();
    $table->timestamps();
});

// Pivot many-to-many
Schema::create('category_post', function (Blueprint $table) {
    $table->foreignId('post_id')->constrained()->cascadeOnDelete();
    $table->foreignId('category_id')->constrained()->cascadeOnDelete();
});
```

### 6.4 Komponen UI yang perlu dibuat (Blade)
- Header dengan **mega menu / nested dropdown** (3 level) — bagian paling kompleks
- Footer 4 kolom (Kontak, Info Kemahasiswaan, Sumber Belajar, Tautan Penting)
- Card berita / artikel / prestasi
- Card dosen (grid)
- Counter statistik animasi
- Hero section dengan anchor navigation (untuk halaman Profil & Fasilitas)

---

## 7. Temuan & Catatan Penting

1. **Halaman placeholder yang perlu konten asli:**
   - `/kurikulum/` → masih halaman default Hostinger (kosong total)
   - `/fasilitas/` → masih Lorem ipsum
   - `/alumni/` → masih konten "Univ Lorem"
   - Menu dengan link `#`: Jadwal Perkuliahan, KMM Asy Asyaamil, Pusat Karir UNM, Facebook

2. **Ketergantungan eksternal yang tinggi.** Banyak layanan inti (tugas akhir, TOEFL, tracer study, jurnal, RPS, SK Mengajar) hanya berupa tautan ke Google Forms/Drive/OJS. Anda bisa:
   - **Opsi A:** Pertahankan sebagai tautan eksternal (cepat, sederhana).
   - **Opsi B:** Bangun modul internal di Laravel (mis. form pendaftaran tugas akhir + manajemen berkas) untuk mengurangi ketergantungan — peluang peningkatan dari versi WP.

3. **Footer berisi 3 grup tautan tetap** yang sebaiknya dibuat *manageable* lewat tabel settings:
   - Info Kemahasiswaan: Pusat Prestasi Nasional, IISMA, Kampus Mengajar, Wirausaha Merdeka
   - Sumber Belajar: Pustaka UNM, OJS UNM, Repositori (eprints), OER UNM, Thesis UNM
   - Tautan Penting: PDDIKTI, UNM, Beasiswa Pendidikan Indonesia

4. **Lokalisasi.** `og:locale` di-set `en_US` padahal konten berbahasa Indonesia — sebaiknya diperbaiki ke `id_ID`.

5. **Bio dosen** saat ini mengarah ke subdomain terpisah (`biomanaj.web.id`). Pertimbangkan apakah ingin mengintegrasikan profil dosen langsung di Laravel (`/dosen/{slug}`) atau tetap eksternal.

6. **Subdomain/sistem terkait UNM** (di luar lingkup tapi penting dicatat): SYAM-OK (LMS), tracerstudy.unm.ac.id, ojs.unm.ac.id, pustaka.unm.ac.id, eprints.unm.ac.id, oer.unm.ac.id, thesis.unm.ac.id.

---

## 8. Checklist Prioritas Pembangunan

**Prioritas 1 (inti situs):**
- [ ] Layout master (header mega-menu + footer)
- [ ] Beranda dengan semua section
- [ ] Modul Post/Berita + kategori (Berita, Artikel, Prestasi, Pengumuman)
- [ ] Modul Dosen (4 kategori + konsentrasi)
- [ ] Halaman Profil (lengkap dengan semua section)

**Prioritas 2 (halaman pendukung):**
- [ ] Akreditasi, Fasilitas, Galeri, Kalender Akademik, Hubungi Kami, HIMA

**Prioritas 3 (peningkatan / konten baru):**
- [ ] Isi halaman placeholder (Kurikulum, Alumni)
- [ ] Pertimbangkan modul internal untuk layanan tugas akhir
- [ ] Admin panel (CRUD post, dosen, galeri, menu, settings)

---

*Dokumen ini disusun sebagai referensi migrasi. Disarankan menelusuri lebih lanjut halaman yang belum dibuka detail (Peta Proses Bisnis, Galeri, Kalender Akademik, Daftar Seminar, HIMA, ICOMAN 2025, Hubungi Kami) sebelum finalisasi struktur database.*
