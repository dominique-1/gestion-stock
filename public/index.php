<?php

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Forcer SQLite pour Render.com
if (!getenv('DATABASE_URL') && !getenv('DB_DATABASE')) {
    putenv('DB_CONNECTION=sqlite');
    putenv('DB_DATABASE=database/database.sqlite');
}

/*
|--------------------------------------------------------------------------
| Check If The Application Is Under Maintenance
|--------------------------------------------------------------------------
|
| If the application is in maintenance / demo mode via the "down" command
| we will load this file so that any pre-rendered content can be shown
| instead of starting the framework, which could cause an exception.
|
*/

if (file_exists($maintenance = __DIR__.'/../storage/framework/maintenance.php')) {
    require $maintenance;
}

/*
|--------------------------------------------------------------------------
| Register The Auto Loader
|--------------------------------------------------------------------------
|
| Composer provides a convenient, automatically generated class loader for
| this application. We just need to utilize it! We'll simply require it
| into the script here so we don't need to manually load our classes.
|
*/

require __DIR__.'/../vendor/autoload.php';

/*
|--------------------------------------------------------------------------
| Run The Application
|--------------------------------------------------------------------------
|
| Once we have the application, we can handle the incoming request using
| the application's HTTP kernel. Then, we will send the response back
| to this client's browser, allowing them to enjoy our application.
|
*/

$app = require_once __DIR__.'/../bootstrap/app.php';

$kernel = $app->make(Kernel::class);

// #region agent log
try {
    $logPath = base_path('.cursor/debug.log');
    $logDir = dirname($logPath);
    if (!is_dir($logDir)) {
        @mkdir($logDir, 0755, true);
    }
    $entry = [
        'id' => 'log_'.uniqid(),
        'timestamp' => (int) round(microtime(true) * 1000),
        'runId' => 'pre-fix',
        'hypothesisId' => 'H1',
        'location' => 'public/index.php:HTTP_ENTRY',
        'message' => 'HTTP request received',
        'data' => [
            'uri' => $_SERVER['REQUEST_URI'] ?? null,
            'method' => $_SERVER['REQUEST_METHOD'] ?? null,
            'app_env' => env('APP_ENV'),
            'db_connection_env' => env('DB_CONNECTION'),
        ],
    ];
    file_put_contents($logPath, json_encode($entry).PHP_EOL, FILE_APPEND);
} catch (\Throwable $e) {
    // ignore logging errors
}
// #endregion agent log

$response = $kernel->handle(
    $request = Request::capture()
)->send();

$kernel->terminate($request, $response);
