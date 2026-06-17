<?php

namespace App\Providers;

use App\Models\Menu;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\Cache;
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

        // Menu navigasi publik (3 level) tersedia di layout frontend & partial-nya.
        View::composer('layouts.frontend', function ($view) {
            $view->with('mainMenu', Cache::rememberForever('menu.main', fn () => Menu::whereNull('parent_id')
                ->where('aktif', true)
                ->with('activeChildren.activeChildren')
                ->orderBy('urutan')
                ->get()));
        });
    }
}
