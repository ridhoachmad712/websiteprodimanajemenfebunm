<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\GalleryController as AdminGalleryController;
use App\Http\Controllers\Admin\MenuController as AdminMenuController;
use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\Admin\UploadController as AdminUploadController;
use App\Http\Controllers\Frontend\DosenController;
use App\Http\Controllers\Frontend\GalleryController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PageController;
use App\Http\Controllers\Frontend\PostController;
use App\Http\Controllers\Frontend\SitemapController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend (publik)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/sitemap.xml', [SitemapController::class, 'index'])->name('sitemap');

Route::get('/daftar-dosen', [DosenController::class, 'index'])->name('dosen.index');
Route::get('/dosen/{dosen}', [DosenController::class, 'show'])->name('dosen.show');

// Berita
Route::get('/berita', [PostController::class, 'index'])->name('post.index');
Route::get('/category/{category}', [PostController::class, 'byCategory'])->name('post.category');

// Galeri
Route::get('/gallery', [GalleryController::class, 'index'])->name('gallery.index');

// Halaman statis
Route::get('/profil', [PageController::class, 'profil'])->name('page.profil');

$staticPages = [
    'akreditasi', 'fasilitas', 'sop-petaprosesbisnis', 'kalender-akademik',
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
    Route::resource('pages', AdminPageController::class)->only(['index', 'edit', 'update'])->names('admin.pages');
    Route::resource('menus', AdminMenuController::class)->except('show')->names('admin.menus');
    Route::resource('gallery', AdminGalleryController::class)->except('show')->names('admin.gallery')->parameters(['gallery' => 'gallery']);
    Route::get('settings', [AdminSettingController::class, 'edit'])->name('admin.settings.edit');
    Route::put('settings', [AdminSettingController::class, 'update'])->name('admin.settings.update');
    Route::post('uploads/image', [AdminUploadController::class, 'image'])->name('admin.uploads.image');
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
