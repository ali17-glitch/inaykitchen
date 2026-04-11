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

// 3. Environment Overrides
$_ENV['DB_DATABASE'] = $dbPath;
$_ENV['STORAGE_PATH'] = $storagePath;
$_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';

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

// 5. Force clear any cached bootstrap files
@unlink(__DIR__ . '/../bootstrap/cache/config.php');
@unlink(__DIR__ . '/../bootstrap/cache/services.php');
@unlink(__DIR__ . '/../bootstrap/cache/packages.php');

// 6. Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 7. Set Storage Path
$app->useStoragePath($storagePath);

// 8. Handle Request
$app->handleRequest(Illuminate\Http\Request::capture());
