<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use RyanChandler\FilamentNavigation\Filament\Resources\NavigationResource;

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
        //
        NavigationResource::navigationGroup(__('Settings'));

        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }
    }
}
