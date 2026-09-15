<?php
// Prepare writable directories in Vercel Serverless environment (/tmp)
$tmpStorage = '/tmp/storage';
$dirs = [
    $tmpStorage . '/framework/views',
    $tmpStorage . '/framework/cache',
    $tmpStorage . '/framework/sessions',
    $tmpStorage . '/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }
}

putenv('VIEW_COMPILED_PATH=' . $tmpStorage . '/framework/views');
putenv('APP_STORAGE=' . $tmpStorage);

// Fallback APP_KEY if not set in Vercel Environment Variables
if (!getenv('APP_KEY')) {
    putenv('APP_KEY=base64:oXrP5Tt9EvXnIIGQd4MoFIStFPE1QJ7qMGs2oKcHVzo=');
    $_ENV['APP_KEY'] = 'base64:oXrP5Tt9EvXnIIGQd4MoFIStFPE1QJ7qMGs2oKcHVzo=';
}

// Setup SQLite database in /tmp from pre-seeded database
$sqliteFile = '/tmp/database.sqlite';
$bundledDb = __DIR__ . '/../database/database.sqlite';

if (!file_exists($sqliteFile)) {
    if (file_exists($bundledDb)) {
        copy($bundledDb, $sqliteFile);
    } else {
        touch($sqliteFile);
    }
}

putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=' . $sqliteFile);
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = $sqliteFile;

require __DIR__ . '/../public/index.php';
