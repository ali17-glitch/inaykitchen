<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

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
        // Auto-run migrations on Vercel (SQLite in /tmp is empty on each cold start)
        if (config('database.default') === 'sqlite') {
            try {
                $dbPath = config('database.connections.sqlite.database');

                // Ensure the SQLite file exists
                if ($dbPath && $dbPath !== ':memory:') {
                    $dir = dirname($dbPath);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    if (!file_exists($dbPath)) {
                        touch($dbPath);
                    }
                }

                // Run migrations if the orders table does not exist yet
                if (!Schema::hasTable('orders')) {
                    \Illuminate\Support\Facades\Artisan::call('migrate', [
                        '--force'          => true,
                        '--no-interaction' => true,
                    ]);
                }
            } catch (\Throwable $e) {
                // Silently log and continue — do not crash the app
                \Illuminate\Support\Facades\Log::error('Auto-migration failed: ' . $e->getMessage());
            }
        }
    }
}
