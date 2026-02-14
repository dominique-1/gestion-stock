<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

echo "=== Application Health Check ===\n\n";

// Test 1: Database connection
echo "1. Database Connection...\n";
try {
    $connected = \DB::connection()->getPdo();
    echo "   ✓ Connected\n";
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 2: Models
echo "\n2. Testing Models...\n";
$models = [
    \App\Models\User::class => 'User',
    \App\Models\Category::class => 'Category',
    \App\Models\Product::class => 'Product',
    \App\Models\Alert::class => 'Alert',
];

foreach ($models as $class => $name) {
    try {
        $count = $class::count();
        echo "   ✓ $name model works (count: $count)\n";
    } catch (\Exception $e) {
        echo "   ✗ $name error: " . substr($e->getMessage(), 0, 50) . "...\n";
    }
}

// Test 3: Routes
echo "\n3. Routes...\n";
try {
    $routes = \Route::getRoutes()->count();
    echo "   ✓ Found $routes routes\n";
} catch (\Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

// Test 4: Views
echo "\n4. Checking view files...\n";
$viewPath = resource_path('views');
$files = array_filter(scandir($viewPath), function($f) { 
    return $f !== '.' && $f !== '..' && is_file("$viewPath/$f");
});
echo "   ✓ Found " . count($files) . " view files\n";

echo "\n=== Health Check Complete ===\n";
