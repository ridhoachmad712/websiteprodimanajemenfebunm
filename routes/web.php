<?php

use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\DosenController as AdminDosenController;
use App\Http\Controllers\Frontend\DosenController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend (publik)
|--------------------------------------------------------------------------
*/

Route::view('/', 'frontend.home')->name('home');

Route::get('/daftar-dosen', [DosenController::class, 'index'])->name('dosen.index');
Route::get('/dosen/{dosen}', [DosenController::class, 'show'])->name('dosen.show');

/*
|--------------------------------------------------------------------------
| Admin (panel, butuh autentikasi)
|--------------------------------------------------------------------------
| Prefix /admin, nama route admin.*. Dashboard memakai nama 'dashboard'
| (tanpa prefix nama) agar kompatibel dengan redirect bawaan Breeze.
*/

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
    Route::resource('dosen', AdminDosenController::class)->except('show')->names('admin.dosen');
});

require __DIR__.'/auth.php';
