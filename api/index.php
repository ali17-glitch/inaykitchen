<?php

define('LARAVEL_START', microtime(true));

// -----------------------------------------------
// VERCEL SERVERLESS BOOTSTRAP
// -----------------------------------------------

// 1. Setup /tmp directories (only writable location on Vercel)
$tmpBase    = '/tmp';
$viewsPath  = $tmpBase . '/views';
$storagePath = $tmpBase . '/storage';

$dirs = [
    $viewsPath,
    $storagePath . '/framework/sessions',
    $storagePath . '/framework/cache/data',
    $storagePath . '/framework/views',
    $storagePath . '/logs',
    $storagePath . '/app/public',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0755, true);
    }
}

// 2. Setup SQLite database in /tmp
$dbPath = $tmpBase . '/database.sqlite';
if (!file_exists($dbPath)) {
    @touch($dbPath);
}

// 3. Override environment config before Laravel boots
putenv('STORAGE_PATH='       . $storagePath);
putenv('DB_DATABASE='        . $dbPath);
putenv('VIEW_COMPILED_PATH=' . $viewsPath);

// -----------------------------------------------

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

try {
    /** @var Application $app */
    $app = require_once __DIR__ . '/../bootstrap/app.php';

    // Override storage path to /tmp on Vercel
    $app->useStoragePath($storagePath);

    $app->handleRequest(Request::capture());

} catch (\Throwable $e) {
    // Show error if debug is on
    if (getenv('APP_DEBUG') === 'true' || getenv('APP_ENV') === 'local') {
        header('Content-Type: text/plain');
        echo "=== LARAVEL FATAL ERROR ===\n";
        echo "Message: " . $e->getMessage() . "\n";
        echo "File: " . $e->getFile() . "\n";
        echo "Line: " . $e->getLine() . "\n\n";
        echo "Trace:\n" . $e->getTraceAsString() . "\n";
        exit(1);
    }
    
    // Log error and throw for default 500 behavior
    error_log("Laravel Fatal Error: " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine());
    throw $e;
}
