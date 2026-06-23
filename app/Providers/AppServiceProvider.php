<?php

namespace App\Providers;

use App\Http\Controllers\Admin\PageController as AdminPageController;
use App\Http\Controllers\Frontend\PageController as FrontendPageController;
use App\Models\Menu;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Pagination memakai markup Bootstrap 5 agar konsisten dengan Tabler.
        Paginator::useBootstrapFive();

        Route::middleware(['web', 'auth'])->prefix('admin')->group(function () {
            Route::get('pages/create', [AdminPageController::class, 'create'])->name('admin.pages.create');
            Route::post('pages', [AdminPageController::class, 'store'])->name('admin.pages.store');
            Route::delete('pages/{page}', [AdminPageController::class, 'destroy'])->name('admin.pages.destroy');
        });

        Route::get('halaman/{page}', [FrontendPageController::class, 'custom'])
            ->middleware('web')
            ->name('page.custom');

        // Menu navigasi publik (3 level) tersedia di layout frontend & partial-nya.
        View::composer('layouts.frontend', function ($view) {
            $view->with('mainMenu', Menu::whereNull('parent_id')
                ->where('aktif', true)
                ->with('activeChildren.activeChildren')
                ->orderBy('urutan')
                ->get());
        });
    }
}
