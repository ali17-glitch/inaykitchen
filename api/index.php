<?php

define('LARAVEL_START', microtime(true));

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// -----------------------------------------------
// VERCEL SERVERLESS FIXES
// -----------------------------------------------

// 1. Set storage path to /tmp (writable on Vercel)
$tmpStorage = '/tmp/laravel/storage';
$fwPath = $tmpStorage . '/framework';

// Create required directories in /tmp if they don't exist
foreach ([
    $tmpStorage . '/logs',
    $fwPath . '/cache/data',
    $fwPath . '/sessions',
    $fwPath . '/views',
    $tmpStorage . '/app/public',
] as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

// 2. Set SQLite DB path to /tmp (writable)
$dbPath = '/tmp/laravel/database.sqlite';
if (!file_exists($dbPath)) {
    touch($dbPath);
}

// Override environment variables for Vercel runtime
$_ENV['STORAGE_PATH'] = $tmpStorage;
$_ENV['DB_DATABASE']  = $dbPath;

putenv('STORAGE_PATH=' . $tmpStorage);
putenv('DB_DATABASE=' . $dbPath);

// -----------------------------------------------

// Bootstrap Laravel and handle the request...
use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->useStoragePath($tmpStorage);

$app->handleRequest(Request::capture());
