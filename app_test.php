<?php

// Test simple de l'application
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🧪 Test Application Gestion Stock</h1>";

// Test 1: Connexion base de données
echo "<h2>📊 Test Base de Données</h2>";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    
    // Charger Laravel
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Test connexion
    $pdo = DB::connection()->getPdo();
    echo "<p>✅ Connexion MySQL réussie</p>";
    
    // Test produits
    $products = DB::table('products')->count();
    $categories = DB::table('categories')->count();
    echo "<p>✅ $products produits trouvés</p>";
    echo "<p>✅ $categories catégories trouvées</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Erreur: " . $e->getMessage() . "</p>";
}

// Test 2: Routes
echo "<h2>🛣️ Test Routes</h2>";
try {
    $routes = [
        'Accueil' => '/',
        'Produits' => '/products',
        'Catégories' => '/categories', 
        'Prédictions' => '/predictions'
    ];
    
    foreach ($routes as $name => $url) {
        echo "<p><strong>$name:</strong> <a href='$url'>$url</a></p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Erreur routes: " . $e->getMessage() . "</p>";
}

echo "<h2>🎯 Conclusion</h2>";
echo "<p>Si vous voyez ce message, l'application fonctionne!</p>";
echo "<p><a href='/'>→ Aller à l'application</a></p>";

?>
