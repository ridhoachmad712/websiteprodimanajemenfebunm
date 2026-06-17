<?php

use App\Http\Controllers\Admin\DashboardController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Frontend (publik)
|--------------------------------------------------------------------------
*/

Route::view('/', 'frontend.home')->name('home');

/*
|--------------------------------------------------------------------------
| Admin (panel, butuh autentikasi)
|--------------------------------------------------------------------------
| Diberi prefix /admin dan nama route admin.*. Dashboard memakai nama
| 'dashboard' (tanpa prefix nama) agar kompatibel dengan redirect bawaan
| Breeze setelah login.
*/

Route::middleware('auth')->prefix('admin')->group(function () {
    Route::get('/', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
