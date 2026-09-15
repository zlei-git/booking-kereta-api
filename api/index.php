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

// Setup SQLite database in /tmp if not exists
$sqliteFile = '/tmp/database.sqlite';
if (!file_exists($sqliteFile)) {
    touch($sqliteFile);
}
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=' . $sqliteFile);

require __DIR__ . '/../public/index.php';
