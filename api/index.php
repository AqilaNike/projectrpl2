<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

require __DIR__.'/../vendor/autoload.php';

$app = require_once __DIR__.'/../bootstrap/app.php';

// Memindahkan direktori storage ke /tmp karena Vercel read-only
$app->useStoragePath($_ENV['APP_STORAGE'] ?? '/tmp/storage');

// Membuat folder storage yang diperlukan di /tmp jika belum ada
$storagePaths = [
    '/tmp/storage/app',
    '/tmp/storage/framework/cache/data',
    '/tmp/storage/framework/sessions',
    '/tmp/storage/framework/views',
    '/tmp/storage/bootstrap/cache',
    '/tmp/storage/logs',
];

foreach ($storagePaths as $path) {
    if (!is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

$app->handleRequest(Request::capture());
