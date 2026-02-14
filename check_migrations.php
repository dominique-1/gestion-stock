<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Migrations Status ===\n\n";

$migrations = \DB::table('migrations')->get();
echo "Total migrations: " . count($migrations) . "\n\n";

foreach ($migrations as $migration) {
    echo "✓ " . $migration->migration . "\n";
}
