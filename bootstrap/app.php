<?php

/*
|--------------------------------------------------------------------------
| Create The Application
|--------------------------------------------------------------------------
|
| The first thing we will do is create a new Laravel application instance
| which serves as the "glue" for all the components of Laravel, and
| is the IoC container for the system binding all of the various parts.
|
*/

$app = new Illuminate\Foundation\Application(
    $_ENV['APP_BASE_PATH'] ?? dirname(__DIR__)
);

/*
|--------------------------------------------------------------------------
| Bind Important Interfaces
|--------------------------------------------------------------------------
|
| Next, we need to bind some important interfaces into the container so
| we will be able to resolve them when needed. The kernels serve the
| requestors to this container so we can resolve them from here.
|
*/

$app->singleton(
    Illuminate\Contracts\Http\Kernel::class,
    App\Http\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Console\Kernel::class,
    App\Console\Kernel::class
);

$app->singleton(
    Illuminate\Contracts\Debug\ExceptionHandler::class,
    App\Exceptions\Handler::class
);

/*
|--------------------------------------------------------------------------
| Return The Application
|--------------------------------------------------------------------------
|
| This script returns the application instance. The instance is given to
| the calling script so we can separate the building of the instances
| from the actual running of the application and sending responses.
|
*/

// FORCER LES VARIABLES D'ENVIRONNEMENT POUR POSTGRESQL
if (env('APP_ENV') === 'production') {
    $_ENV['DB_CONNECTION'] = 'pgsql';
    $_ENV['DB_HOST'] = 'dpg-d668asmsb7us73ckg96g-a';
    $_ENV['DB_PORT'] = '5432';
    $_ENV['DB_DATABASE'] = 'gestion_stock_2026';
    $_ENV['DB_USERNAME'] = 'gestion_stock_2026_user';
    $_ENV['DB_PASSWORD'] = 'D8gXzuYh4Luitly3Ly9Kv0Rkwpk64nm2';
    
    putenv('DB_CONNECTION=pgsql');
    putenv('DB_HOST=dpg-d668asmsb7us73ckg96g-a');
    putenv('DB_PORT=5432');
    putenv('DB_DATABASE=gestion_stock_2026');
    putenv('DB_USERNAME=gestion_stock_2026_user');
    putenv('DB_PASSWORD=D8gXzuYh4Luitly3Ly9Kv0Rkwpk64nm2');
}

return $app;
