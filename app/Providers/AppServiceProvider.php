<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Pagination\Paginator;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    public function boot(): void
    {
        // Set the custom dark-themed pagination globally
        Paginator::defaultView('vendor.pagination.custom-repuestofijo');
        Paginator::defaultSimpleView('vendor.pagination.simple-bootstrap-5');

        if ($this->app->environment('production')) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
