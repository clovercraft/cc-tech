<?php

namespace App\Providers;

use App\Jobs\ExportWhitelist;
use Illuminate\Cache\RateLimiting\Limit;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\ServiceProvider;
use Studio\Totem\Totem;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\RateLimiter;

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
        if ($this->app->environment('production')) {
            URL::forceScheme('https');
        }

        Totem::auth(function ($request) {
            return Auth::check() && Auth::user()->hasAccess('staff.system');
        });

        RateLimiter::for(ExportWhitelist::class, function (object $job) {
            return Limit::perMinute(10);
        });
    }
}
