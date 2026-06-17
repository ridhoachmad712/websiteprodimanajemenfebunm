<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Admin\PostController as AdminPostController;
use App\Http\Controllers\Frontend\DosenController;
use App\Http\Controllers\Frontend\HomeController;
use App\Http\Controllers\Frontend\PostController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend (publik)
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::get('/daftar-dosen', [DosenController::class, 'index'])->name('dosen.index');
Route::get('/dosen/{dosen}', [DosenController::class, 'show'])->name('dosen.show');

// Berita
Route::get('/berita', [PostController::class, 'index'])->name('post.index');
Route::get('/category/{category}', [PostController::class, 'byCategory'])->name('post.category');

/*
|--------------------------------------------------------------------------
| Admin (panel, butuh autentikasi)
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('dosen', AdminDosenController::class)->except('show')->names('admin.dosen');
    Route::resource('posts', AdminPostController::class)->except('show')->names('admin.posts');
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
