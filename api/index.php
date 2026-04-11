<?php

define('LARAVEL_START', microtime(true));

// 1. Setup /tmp directories (Vercel only allows writing to /tmp)
$storagePath = '/tmp/storage';
$dirs = [
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
    $storagePath . '/app/public',
    '/tmp/views',
];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Database setup
$dbPath = '/tmp/database.sqlite';
if (!file_exists($dbPath)) {
    @touch($dbPath);
}

// 3. Environment Overrides
// Use $_ENV for better compatibility with Laravel
$_ENV['DB_DATABASE'] = $dbPath;
$_ENV['STORAGE_PATH'] = $storagePath;
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';
$_ENV['CACHE_STORE'] = 'array';
$_ENV['SESSION_DRIVER'] = 'array';
$_ENV['APP_DEBUG'] = 'true'; // Keep true for now to debug

putenv("DB_DATABASE=$dbPath");
putenv("STORAGE_PATH=$storagePath");
putenv("VIEW_COMPILED_PATH=/tmp/views");

// 4. Register Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 5. Force clear any cached bootstrap files that might have been uploaded
@unlink(__DIR__ . '/../bootstrap/cache/config.php');
@unlink(__DIR__ . '/../bootstrap/cache/services.php');
@unlink(__DIR__ . '/../bootstrap/cache/packages.php');

// 6. Bootstrap Laravel
/** @var Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 7. Set Storage Path
$app->useStoragePath($storagePath);

// 8. Handle Request
try {
    // Explicitly register the ViewServiceProvider if it's somehow missing
    // (This is a safety measure for the "Target class [view] does not exist" error)
    if (!$app->bound('view')) {
        $app->register(Illuminate\View\ViewServiceProvider::class);
    }

    $app->handleRequest(Illuminate\Http\Request::capture());
} catch (\Throwable $e) {
    header('Content-Type: text/plain');
    echo "=== LARAVEL FATAL ERROR ===\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
    exit(1);
}
