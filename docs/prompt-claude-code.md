# Prompt untuk Memulai Pembuatan Website di Claude Code

> **Cara pakai:** Buka terminal di folder project `D:\Project\Website Prodi Manajemen`, jalankan `claude`, lalu tempel **PROMPT KONTEKS** di bawah sebagai pesan pertama. Setelah Claude Code memahami konteks, lanjutkan dengan **PROMPT TUGAS #1**. Kerjakan modul satu per satu — jangan minta semuanya sekaligus.

---

## BAGIAN A — PROMPT KONTEKS (kirim pertama kali)

```
Saya sedang membangun ulang website resmi Program Studi Manajemen FEB UNM
(sebelumnya berbasis WordPress di manajemenunm.com) menjadi aplikasi Laravel.

== TUJUAN ==
Membuat website akademik yang minimalis, modern, dan fungsional, dengan
panel admin (CMS) sendiri sebagai pengganti wp-admin.

== STACK TEKNOLOGI ==
- Laravel (versi terbaru) + Blade
- MySQL/MariaDB
- UI: Tabler.io (berbasis Bootstrap 5) + Tabler Icons
- Build: Vite
- Auth: Laravel Breeze
- Editor konten: TinyMCE/CKEditor untuk berita

== LOKASI TEMPLATE TABLER ==
Folder source Tabler ada di: tabler-dev/
(relatif terhadap root project). Gunakan folder ini sebagai sumber aset UI.
Jika perlu di-build, jalankan npm install lalu npm run build di dalamnya,
lalu salin hasil dist/ ke public/tabler/. Cek dulu apakah dist/ sudah ada.

== ARAH DESAIN ==
- Minimalis: banyak whitespace, palet warna terbatas (1 warna brand + netral)
- Modern: sudut membulat, shadow lembut, transisi ringan, ikon garis
- Fungsional: navigasi jelas & konsisten, hierarki rapi, mobile-first responsif
- Warna brand mengikuti identitas UNM (override variabel --tblr-primary)

== ARSITEKTUR ==
Pisahkan jelas antara:
- Frontend publik: Controllers/Frontend, views/frontend, layouts/frontend.blade.php
- Admin panel: Controllers/Admin (prefix route /admin, middleware auth),
  views/admin, layouts/admin.blade.php (pakai layout dashboard Tabler)

== ENTITAS DATABASE UTAMA ==
- posts (judul, slug, excerpt, konten, featured_image, user_id, published_at)
- categories (Berita & Informasi, Artikel, Prestasi, Pengumuman) + pivot category_post
- dosen (nama, slug, nip, foto, kategori[guru_besar/tetap_prodi/mkdu/luar_biasa],
  konsentrasi[Keuangan/Pemasaran/SDM], bio_link, urutan)
- galleries (judul, gambar, kategori)
- pages (slug, title, content) untuk halaman statis yang bisa diedit admin
- menus (parent_id untuk nesting 3 level, title, url, target, urutan)
- settings (key-value: kontak, sosmed, tagline, statistik beranda, footer links)
- users (admin)

== STRUKTUR HALAMAN PUBLIK (mengikuti situs lama) ==
- Beranda (/), Profil (/profil), Daftar Dosen (/daftar-dosen),
  Detail Dosen (/dosen/{slug}), Akreditasi, Fasilitas, Galeri, Peta Proses Bisnis,
  Kalender Akademik, Daftar Seminar, Prestasi, HIMA, Alumni, ICOMAN 2025, Hubungi Kami
- Berita: daftar (/berita), kategori (/category/{slug}),
  detail dengan URL dipertahankan dari WordPress: /{tahun}/{bulan}/{tgl}/{slug}

== ATURAN KERJA ==
1. Ikuti konvensi & best practice Laravel (Form Request untuk validasi,
   resource controller, Eloquent relationship, migration rapi).
2. Konsisten gunakan komponen & utility class Tabler untuk semua UI.
3. Jangan hardcode konten — semua harus bisa dikelola dari panel admin.
4. Buat kode bersih, beri komentar secukupnya, dan jelaskan keputusan penting.
5. Kerjakan bertahap sesuai tugas yang saya berikan. Tunggu konfirmasi saya
   sebelum lanjut ke modul berikutnya.

Tolong konfirmasi bahwa kamu sudah memahami konteks ini, lalu periksa isi
folder tabler-dev/ dan beri tahu saya struktur yang kamu temukan sebelum
kita mulai scaffolding.
```

---

## BAGIAN B — PROMPT TUGAS #1 (Fondasi Project)

> Kirim setelah Claude Code mengonfirmasi konteks & memeriksa folder Tabler.

```
Mulai TUGAS #1 — Setup fondasi project.

Lakukan langkah berikut secara berurutan, jelaskan tiap langkah:

1. Inisialisasi project Laravel baru (jika belum ada) di folder ini.
2. Setup file .env untuk koneksi database lokal (MySQL, nama db: manajemen_unm).
3. Integrasikan Tabler:
   - Periksa folder tabler-dev/. Jika ada dist/ siap pakai, salin ke public/tabler/.
     Jika belum, build dulu (npm install && npm run build) lalu salin.
   - Buat layouts/frontend.blade.php (navbar + footer, tanpa sidebar dashboard).
   - Buat layouts/admin.blade.php dari layout dashboard Tabler (sidebar + topbar).
4. Pasang Laravel Breeze untuk autentikasi admin.
5. Buat satu halaman beranda placeholder sederhana yang sudah memakai
   layout frontend + komponen Tabler, supaya saya bisa cek tampilannya.

Setelah selesai, beri tahu saya cara menjalankan project (npm run dev,
php artisan serve) dan tunggu konfirmasi saya sebelum lanjut ke TUGAS #2.
```

---

## BAGIAN C — Urutan Tugas Berikutnya (referensi)

Kirim satu per satu setelah tugas sebelumnya selesai & dicek:

- **Tugas #2 — Database:** Buat semua migration, model, relasi, dan seeder data awal (kategori, settings, beberapa contoh dosen & berita).
- **Tugas #3 — Modul Dosen:** CRUD admin (upload foto, kategori, konsentrasi) + halaman publik Daftar Dosen (dikelompokkan 4 kategori) & detail dosen.
- **Tugas #4 — Modul Berita:** CRUD admin + WYSIWYG + kategori; halaman publik daftar, kategori, dan detail (URL `/{tahun}/{bulan}/{tgl}/{slug}`).
- **Tugas #5 — Beranda Lengkap:** Susun semua section beranda (hero, statistik, berita terbaru, dosen, prestasi, pengumuman).
- **Tugas #6 — Halaman Statis:** Modul `pages` + halaman Profil (7 section), Akreditasi, Fasilitas, dll.
- **Tugas #7 — Menu Builder & Settings:** Mega-menu dinamis (3 level) + pengaturan situs (kontak, sosmed, footer links).
- **Tugas #8 — Galeri:** CRUD + tampilan publik.
- **Tugas #9 — Finishing:** Responsif, SEO (meta tag, sitemap, redirect 301), optimasi performa.

---

## Tips Bekerja dengan Claude Code

1. **Satu tugas per sesi** — jangan gabung banyak modul; hasilnya lebih terkontrol.
2. **Selalu cek hasil** di browser sebelum lanjut ke tugas berikutnya.
3. **Commit per modul** — minta Claude Code membuat commit git setelah tiap tugas selesai.
4. **Beri feedback spesifik** — mis. "warna primary terlalu terang, ganti ke #003D7A".
5. **Simpan kedua dokumen** (analisa & planning) di dalam project (mis. folder `/docs`) agar Claude Code bisa membacanya kapan saja sebagai rujukan.
