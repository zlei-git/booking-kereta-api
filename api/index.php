<?php
// Ensure $_SERVER variables are friendly to Laravel routing in Vercel
$_SERVER['SCRIPT_NAME'] = '/index.php';

// Prepare writable directories in Vercel Serverless environment (/tmp)
$tmpStorage = '/tmp/storage';
$dirs = [
    $tmpStorage . '/app/public',
    $tmpStorage . '/framework/views',
    $tmpStorage . '/framework/cache',
    $tmpStorage . '/framework/cache/data',
    $tmpStorage . '/framework/sessions',
    $tmpStorage . '/logs',
];

foreach ($dirs as $dir) {
    if (!is_dir($dir)) {
        @mkdir($dir, 0777, true);
    }
}

putenv('VIEW_COMPILED_PATH=' . $tmpStorage . '/framework/views');
putenv('APP_STORAGE=' . $tmpStorage);
$_ENV['VIEW_COMPILED_PATH'] = $tmpStorage . '/framework/views';
$_ENV['APP_STORAGE'] = $tmpStorage;

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

try {
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    http_response_code(500);
    header('Content-Type: text/plain');
    echo "Fatal Error: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n\n";
    if ($prev = $e->getPrevious()) {
        echo "Caused By: " . $prev->getMessage() . "\n";
        echo "File: " . $prev->getFile() . ":" . $prev->getLine() . "\n\n";
    }
    echo $e->getTraceAsString();
}
