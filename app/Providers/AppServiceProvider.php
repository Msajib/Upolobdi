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
        if (
            (request()->hasHeader('x-forwarded-proto') && request()->header('x-forwarded-proto') === 'https') ||
            (request()->hasHeader('x-forwarded-ssl') && request()->header('x-forwarded-ssl') === 'on') ||
            request()->hasHeader('x-forwarded-host') ||
            request()->isSecure() ||
            str_contains(request()->getHttpHost(), 'upolobdi.com') ||
            str_contains(request()->getHttpHost(), 'ngrok')
        ) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }
    }
}
