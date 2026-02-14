<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;

class AutoMigrationServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        // Only run in production on Render.com
        if ($this->app->environment('production') && env('DB_CONNECTION') === 'sqlite') {
            $dbPath = database_path('database.sqlite');
            
            // Check if database exists and has tables
            if (file_exists($dbPath)) {
                try {
                    // Check if migrations table exists
                    $pdo = new \PDO('sqlite:' . $dbPath);
                    $result = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='migrations'");
                    
                    if ($result->fetchColumn() === false) {
                        // No migrations table, run migrations
                        $this->runMigrationsAndSeeds();
                    } else {
                        // Check if we have any real tables
                        $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name != 'migrations'")->fetchAll(\PDO::FETCH_COLUMN);
                        
                        if (empty($tables)) {
                            // Database exists but no tables, run migrations
                            $this->runMigrationsAndSeeds();
                        }
                    }
                } catch (\Exception $e) {
                    error_log("Auto-migration error: " . $e->getMessage());
                }
            }
        }
    }

    private function runMigrationsAndSeeds()
    {
        try {
            // Run migrations
            Artisan::call('migrate', ['--force' => true]);
            error_log("Auto-migrations completed: " . Artisan::output());
            
            // Run seeders
            Artisan::call('db:seed', ['--force' => true]);
            error_log("Auto-seeding completed: " . Artisan::output());
            
        } catch (\Exception $e) {
            error_log("Auto-migration/seeding error: " . $e->getMessage());
        }
    }
}
