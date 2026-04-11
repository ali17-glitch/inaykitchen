<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
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
        // Force HTTPS on Vercel
        if (env('APP_ENV') === 'production') {
            URL::forceScheme('https');
        }

        // Auto-run migrations on Vercel
        if (config('database.default') === 'sqlite') {
            try {
                if (!Schema::hasTable('orders')) {
                    Artisan::call('migrate', [
                        '--force' => true,
                        '--no-interaction' => true,
                    ]);
                }
            } catch (\Throwable $e) {
                Log::warning('Auto-migration skipped: ' . $e->getMessage());
            }
        }
    }
}
