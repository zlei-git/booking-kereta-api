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
putenv('APP_SERVICES_CACHE=' . $tmpStorage . '/framework/cache/services.php');
putenv('APP_PACKAGES_CACHE=' . $tmpStorage . '/framework/cache/packages.php');
putenv('APP_CONFIG_CACHE=' . $tmpStorage . '/framework/cache/config.php');
putenv('APP_ROUTES_CACHE=' . $tmpStorage . '/framework/cache/routes.php');
putenv('APP_EVENTS_CACHE=' . $tmpStorage . '/framework/cache/events.php');
putenv('APP_MAINTENANCE_DRIVER=file');
putenv('APP_NAME=NusaRail');
putenv('SESSION_COOKIE=nusarail_session');
putenv('SESSION_DRIVER=file');

$_ENV['VIEW_COMPILED_PATH'] = $tmpStorage . '/framework/views';
$_ENV['APP_STORAGE'] = $tmpStorage;
$_ENV['APP_SERVICES_CACHE'] = $tmpStorage . '/framework/cache/services.php';
$_ENV['APP_PACKAGES_CACHE'] = $tmpStorage . '/framework/cache/packages.php';
$_ENV['APP_CONFIG_CACHE'] = $tmpStorage . '/framework/cache/config.php';
$_ENV['APP_ROUTES_CACHE'] = $tmpStorage . '/framework/cache/routes.php';
$_ENV['APP_EVENTS_CACHE'] = $tmpStorage . '/framework/cache/events.php';
$_ENV['APP_MAINTENANCE_DRIVER'] = 'file';
$_ENV['APP_NAME'] = 'NusaRail';
$_ENV['SESSION_COOKIE'] = 'nusarail_session';
$_ENV['SESSION_DRIVER'] = 'file';

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
