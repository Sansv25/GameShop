<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\URL;

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
        if (config('app.env') !== 'local') {
            URL::forceScheme('https');
        }

        \Gate::define('admin', function ($user) {
            return $user->role === 'admin';
        });

        // Force HTTPS for all generated URLs when running in production.
        // Railway terminates TLS at its edge proxy and forwards requests over
        // HTTP internally, so Laravel's scheme detection via X-Forwarded-Proto
        // can be unreliable. Calling forceScheme() ensures that route() and
        // asset() helpers always produce https:// URLs, eliminating mixed
        // content errors regardless of what the incoming request looks like.
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }
    }
}
