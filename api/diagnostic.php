<?php

// Quick diagnostic - show PHP info and check if paths exist
header('Content-Type: text/plain');

echo "=== CACHE CHECK ===\n";
echo "bootstrap/cache/packages.php exists: " . (file_exists(__DIR__ . '/../bootstrap/cache/packages.php') ? 'YES' : 'NO') . "\n";
echo "bootstrap/cache/services.php exists: " . (file_exists(__DIR__ . '/../bootstrap/cache/services.php') ? 'YES' : 'NO') . "\n";
echo "bootstrap/cache/config.php exists: " . (file_exists(__DIR__ . '/../bootstrap/cache/config.php') ? 'YES' : 'NO') . "\n";
echo "bootstrap/cache/routes-v7.php exists: " . (file_exists(__DIR__ . '/../bootstrap/cache/routes-v7.php') ? 'YES' : 'NO') . "\n";

echo "\n=== ENVIRONMENT ===\n";
echo "APP_ENV: " . (getenv('APP_ENV') ?: 'NOT SET') . "\n";
echo "APP_DEBUG: " . (getenv('APP_DEBUG') ?: 'NOT SET') . "\n";

echo "\n=== FILE PERMISSIONS ===\n";
echo "bootstrap/cache writable: " . (is_writable(__DIR__ . '/../bootstrap/cache') ? 'YES' : 'NO') . "\n";

echo "\nDONE\n";
