<?php

namespace App\Providers;

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
        // Register Orderdetail Observer untuk manajemen stok otomatis
        \App\Models\Orderdetail::observe(\App\Observers\OrderdetailObserver::class);
    }
}
