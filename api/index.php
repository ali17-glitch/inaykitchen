<?php

define('LARAVEL_START', microtime(true));

// 1. Setup /tmp directories (Vercel only allows writing to /tmp)
$storagePath = '/tmp/storage';
foreach ([
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
    $storagePath . '/app/public',
    '/tmp/views',
] as $dir) {
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
putenv("DB_DATABASE=$dbPath");
putenv("STORAGE_PATH=$storagePath");
putenv("VIEW_COMPILED_PATH=/tmp/views");
putenv("CACHE_STORE=array");
putenv("SESSION_DRIVER=array");

// 4. Register Autoloader
require __DIR__ . '/../vendor/autoload.php';

// 5. Bootstrap Laravel
$app = require_once __DIR__ . '/../bootstrap/app.php';

// 6. Set Storage Path
$app->useStoragePath($storagePath);

// 7. Handle Request
try {
    // For Laravel 11+, we use the handleRequest method which is more robust
    $app->handleRequest(Illuminate\Http\Request::capture());
} catch (\Throwable $e) {
    if (getenv('APP_DEBUG') === 'true') {
        header('Content-Type: text/plain');
        echo "=== FATAL BOOT ERROR ===\n";
        echo $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . "\n";
        echo "Line: " . $e->getLine() . "\n\n";
        echo $e->getTraceAsString();
        exit(1);
    }
    throw $e;
}
