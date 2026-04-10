<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

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

                // Ensure the directory and file exist (needed in Vercel /tmp)
                if ($dbPath && $dbPath !== ':memory:') {
                    $dir = dirname($dbPath);
                    if (!is_dir($dir)) {
                        mkdir($dir, 0755, true);
                    }
                    if (!file_exists($dbPath)) {
                        touch($dbPath);
                    }
                }

                // Check if the orders table exists, if not run migrations
                if (!Schema::hasTable('orders')) {
                    \Illuminate\Support\Facades\Artisan::call('migrate', [
                        '--force' => true,
                        '--no-interaction' => true,
                    ]);
                }
            } catch (\Exception $e) {
                // Silently continue if migration fails (e.g., read-only filesystem)
                \Illuminate\Support\Facades\Log::error('Auto-migration failed: ' . $e->getMessage());
            }
        }
    }
}
