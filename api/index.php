<?php

define('LARAVEL_START', microtime(true));

// 1. Setup /tmp directories
$storagePath = '/tmp/storage';
$dirs = [
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
    $storagePath . '/app/public',
    '/tmp/views',
    '/tmp/cache',
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

// 3. Environment Overrides - CRITICAL FOR VERCEL
$_ENV['DB_DATABASE'] = $dbPath;
$_ENV['STORAGE_PATH'] = $storagePath;
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';

// Tell Laravel to use /tmp for all its internal caches
$_ENV['APP_SERVICES_CACHE'] = '/tmp/cache/services.php';
$_ENV['APP_PACKAGES_CACHE'] = '/tmp/cache/packages.php';
$_ENV['APP_CONFIG_CACHE'] = '/tmp/cache/config.php';
$_ENV['APP_ROUTES_CACHE'] = '/tmp/cache/routes.php';

$_ENV['CACHE_STORE'] = 'array';
$_ENV['SESSION_DRIVER'] = 'array';
$_ENV['LOG_CHANNEL'] = 'stderr';

putenv("DB_DATABASE=$dbPath");
putenv("STORAGE_PATH=$storagePath");
putenv("VIEW_COMPILED_PATH=/tmp/views");

// 4. Register Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 5. Bootstrap Laravel
/** @var Illuminate\Foundation\Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 6. Set Storage Path
$app->useStoragePath($storagePath);

// 7. Handle Request
try {
    $app->handleRequest(Illuminate\Http\Request::capture());
} catch (\Throwable $e) {
    if (getenv('APP_DEBUG') === 'true') {
        header('Content-Type: text/plain');
        echo "=== LARAVEL FATAL ERROR ===\n";
        echo "Message: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . "\n";
        echo "Line: " . $e->getLine() . "\n\n";
        echo "Trace:\n" . $e->getTraceAsString() . "\n";
        exit(1);
    }
    throw $e;
}
