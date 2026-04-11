<?php

// Quick diagnostic - show PHP info and check if paths exist
header('Content-Type: text/plain');

echo "=== PHP VERSION ===\n";
echo phpversion() . "\n\n";

echo "=== ENVIRONMENT ===\n";
echo "DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'NOT SET') . "\n";
echo "APP_KEY set: " . (getenv('APP_KEY') ? 'yes' : 'no') . "\n";
echo "APP_ENV: " . (getenv('APP_ENV') ?: 'NOT SET') . "\n\n";

echo "=== FILE PATHS ===\n";
echo "vendor/autoload.php exists: " . (file_exists(__DIR__ . '/../vendor/autoload.php') ? 'YES' : 'NO') . "\n";
echo "bootstrap/app.php exists: " . (file_exists(__DIR__ . '/../bootstrap/app.php') ? 'YES' : 'NO') . "\n";

echo "=== /tmp CHECK ===\n";
echo "/tmp writable: " . (is_writable('/tmp') ? 'yes' : 'no') . "\n";

// Try to setup /tmp dirs
$dirs = ['/tmp/views', '/tmp/storage/framework/sessions', '/tmp/storage/framework/cache/data', '/tmp/storage/framework/views', '/tmp/storage/logs', '/tmp/storage/app/public'];
foreach ($dirs as $dir) {
    if (!is_dir($dir)) @mkdir($dir, 0755, true);
    echo "$dir: " . (is_dir($dir) ? 'OK' : 'FAILED') . "\n";
}

// SQLite
$db = '/tmp/database.sqlite';
if (!file_exists($db)) @touch($db);
echo "SQLite at $db: " . (file_exists($db) ? 'OK' : 'FAILED') . "\n";

echo "\n=== TESTING AUTOLOAD ===\n";
try {
    require __DIR__ . '/../vendor/autoload.php';
    echo "autoload.php: OK\n";
} catch (Throwable $e) {
    echo "autoload.php ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== TESTING BOOTSTRAP ===\n";
try {
    putenv('STORAGE_PATH=/tmp/storage');
    putenv('DB_DATABASE=/tmp/database.sqlite');
    putenv('VIEW_COMPILED_PATH=/tmp/views');
    putenv('CACHE_DRIVER=array');
    putenv('SESSION_DRIVER=array');
    $_ENV['STORAGE_PATH'] = '/tmp/storage';
    $_ENV['DB_DATABASE'] = '/tmp/database.sqlite';
    $_ENV['VIEW_COMPILED_PATH'] = '/tmp/views';
    $_ENV['CACHE_DRIVER'] = 'array';
    $_ENV['SESSION_DRIVER'] = 'array';

    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $app->useStoragePath('/tmp/storage');
    echo "bootstrap/app.php: OK\n";
    echo "App class: " . get_class($app) . "\n";
} catch (Throwable $e) {
    echo "bootstrap ERROR: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . "\n";
    echo "Line: " . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}

echo "\nDONE\n";
