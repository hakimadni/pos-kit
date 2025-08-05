<?php

namespace App\Providers;

use Illuminate\Support\Facades\URL;
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
        // $host = request()->getHost();

        // if (!filter_var($host, FILTER_VALIDATE_IP)) {
        //     URL::forceScheme('https');
        // }
    }
}
