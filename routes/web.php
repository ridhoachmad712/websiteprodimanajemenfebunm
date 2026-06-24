<?php

use App\Http\Controllers\Admin\AppearanceController as AdminAppearanceController;
use App\Http\Controllers\Admin\ContactMessageController as AdminContactController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\DownloadController as AdminDownloadController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\HomeBuilderController as AdminHomeBuilderController;
use App\Http\Controllers\Admin\KegiatanController as AdminKegiatanController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PengumumanController as AdminPengumumanController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\PrestasiController as AdminPrestasiController;
use App\Http\Controllers\Admin\SeminarController as AdminSeminarController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\UploadController as AdminUploadController;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Frontend\ContactController;
use App\Http\Controllers\Frontend\DosenController;
use App\Http\Controllers\Frontend\DownloadController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\JadwalUjianController;
use App\Http\Controllers\Frontend\KalenderController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\PengumumanController;
use App\Http\Controllers\Frontend\PostController;
use App\Http\Controllers\Frontend\PrestasiController;
use App\Http\Controllers\Frontend\SearchController;
use App\Http\Controllers\Frontend\SeminarController;
use App\Http\Controllers\Frontend\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend (publik)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

// Pencarian situs
Route::get('/cari', [SearchController::class, 'index'])->name('search.index');

Route::get('/daftar-dosen', [DosenController::class, 'index'])->name('dosen.index');
Route::get('/dosen/{dosen}', [DosenController::class, 'show'])->name('dosen.show');

// Berita
Route::get('/berita', [PostController::class, 'index'])->name('post.index');
Route::get('/category/{category}', [PostController::class, 'byCategory'])->name('post.category');

// Pengumuman (modul mandiri, terpisah dari Berita)
Route::get('/pengumuman', [PengumumanController::class, 'index'])->name('pengumuman.index');
Route::get('/pengumuman/{pengumuman}', [PengumumanController::class, 'show'])->name('pengumuman.show');

// Galeri
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

// Prestasi
Route::get('/prestasi', [PrestasiController::class, 'index'])->name('prestasi.index');

// Pusat Unduhan
Route::get('/unduhan', [DownloadController::class, 'index'])->name('unduhan.index');

// Daftar Seminar (proposal/hasil/ujian tutup)
Route::get('/daftar-seminar', [SeminarController::class, 'index'])->name('seminar.index');

// Form kontak (Hubungi Kami)
Route::post('/hubungi-kami/kirim', [ContactController::class, 'store'])
    ->middleware('throttle:5,1')->name('contact.store');

// Kalender Akademik (kalender visual) & Agenda kegiatan
Route::get('/kalender-akademik', [KalenderController::class, 'index'])->name('page.kalender-akademik');
Route::get('/agenda', [KalenderController::class, 'agenda'])->name('agenda');

// Jadwal Ujian (ditarik dari Google Sheet, URL dapat diatur di Pengaturan)
Route::get('/jadwal-ujian', [JadwalUjianController::class, 'index'])->name('page.jadwal-ujian');

// Halaman custom buatan Page Builder (slug bebas di bawah /halaman)
Route::get('/halaman/{page}', [PageController::class, 'custom'])->name('page.custom');

// Halaman statis
Route::get('/profil', [PageController::class, 'profil'])->name('page.profil');

$staticPages = [
    'akreditasi', 'fasilitas', 'sop-petaprosesbisnis',
    'kurikulum', 'hima', 'alumni', 'icoman2025', 'hubungi-kami',
];
foreach ($staticPages as $slug) {
    Route::get("/{$slug}", [PageController::class, 'show'])
        ->defaults('slug', $slug)
        ->name("page.{$slug}");
}

/*
|--------------------------------------------------------------------------
| Admin (panel, butuh autentikasi)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('dosen', AdminDosenController::class)->except('show')->names('admin.dosen');
    Route::resource('posts', AdminPostController::class)->except('show')->names('admin.posts');
    Route::resource('pengumuman', AdminPengumumanController::class)->except('show')->names('admin.pengumuman')->parameters(['pengumuman' => 'pengumuman']);
    Route::resource('pages', AdminPageController::class)->only(['index', 'create', 'store', 'edit', 'update', 'destroy'])->names('admin.pages');
    Route::resource('menus', AdminMenuController::class)->except('show')->names('admin.menus');
    Route::resource('gallery', AdminGalleryController::class)->except('show')->names('admin.gallery')->parameters(['gallery' => 'gallery']);
    Route::resource('kegiatan', AdminKegiatanController::class)->except('show')->names('admin.kegiatan');
    Route::resource('prestasi', AdminPrestasiController::class)->except('show')->names('admin.prestasi')->parameters(['prestasi' => 'prestasi']);
    Route::resource('downloads', AdminDownloadController::class)->except('show')->names('admin.downloads');
    Route::resource('seminar', AdminSeminarController::class)->except('show')->names('admin.seminar')->parameters(['seminar' => 'seminar']);
    Route::resource('users', AdminUserController::class)->except('show')->names('admin.users');
    Route::get('beranda', [AdminHomeBuilderController::class, 'edit'])->name('admin.home.edit');
    Route::put('beranda', [AdminHomeBuilderController::class, 'update'])->name('admin.home.update');
    Route::get('tampilan', [AdminAppearanceController::class, 'edit'])->name('admin.appearance.edit');
    Route::put('tampilan', [AdminAppearanceController::class, 'update'])->name('admin.appearance.update');
    Route::get('settings', [AdminSettingController::class, 'edit'])->name('admin.settings.edit');
    Route::put('settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');
    Route::post('uploads/image', [AdminUploadController::class, 'image'])->name('admin.uploads.image');
    Route::get('pesan', [AdminContactController::class, 'index'])->name('admin.contacts.index');
    Route::get('pesan/{contact}', [AdminContactController::class, 'show'])->name('admin.contacts.show');
    Route::delete('pesan/{contact}', [AdminContactController::class, 'destroy'])->name('admin.contacts.destroy');
});

require __DIR__.'/auth.php';

/*
|--------------------------------------------------------------------------
| Detail berita — URL gaya WordPress: /YYYY/MM/DD/slug
|--------------------------------------------------------------------------
| Ditempatkan paling akhir (catch-all bersyarat numerik) agar tidak
| menabrak rute lain.
*/
Route::get('/{year}/{month}/{day}/{slug}', [PostController::class, 'show'])
    ->where(['year' => '\d{4}', 'month' => '\d{2}', 'day' => '\d{2}'])
    ->name('post.show');
