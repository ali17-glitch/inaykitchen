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
        mkdir($dir, 0755, true);
    }
}

// 2. Setup SQLite database in /tmp
$dbPath = $tmpBase . '/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);
}

// 3. Override environment config before Laravel boots
$_ENV['STORAGE_PATH']       = $storagePath;
$_ENV['DB_DATABASE']        = $dbPath;
$_ENV['VIEW_COMPILED_PATH'] = $viewsPath;
$_ENV['CACHE_DRIVER']       = 'array';
$_ENV['SESSION_DRIVER']     = 'array';
$_ENV['QUEUE_CONNECTION']   = 'sync';

putenv('STORAGE_PATH='       . $storagePath);
putenv('DB_DATABASE='        . $dbPath);
putenv('VIEW_COMPILED_PATH=' . $viewsPath);
putenv('CACHE_DRIVER=array');
putenv('SESSION_DRIVER=array');
putenv('QUEUE_CONNECTION=sync');

// -----------------------------------------------

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

// Override storage path to /tmp on Vercel
$app->useStoragePath($storagePath);

$app->handleRequest(Request::capture());
