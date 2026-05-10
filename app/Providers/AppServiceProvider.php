<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;

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
        // Force HTTPS for all generated URLs when running in production/live.
        // Railway terminates TLS at its edge proxy and forwards requests over
        // HTTP internally, so Laravel's scheme detection can sometimes be unreliable.
        // Calling forceScheme() ensures that route() and asset() helpers always 
        // produce https:// URLs, resolving "Invalid signature" errors.
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        \Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });
    }
}
