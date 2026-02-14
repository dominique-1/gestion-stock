<?php

// Script de correction pour le déploiement sur Render
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Correction Déploiement Render</h1>";

// 1. Vérifier les variables d'environnement Render
echo "<h2>🌍 Variables Render</h2>";
$renderVars = [
    'DATABASE_URL' => getenv('DATABASE_URL'),
    'DB_HOST' => getenv('DB_HOST'),
    'DB_PORT' => getenv('DB_PORT'),
    'DB_NAME' => getenv('DB_NAME'),
    'DB_USER' => getenv('DB_USER'),
    'DB_PASSWORD' => getenv('DB_PASSWORD'),
];

foreach ($renderVars as $key => $value) {
    echo "<p><strong>$key:</strong> " . ($value ?: 'NON DÉFINI') . "</p>";
}

// 2. Configuration automatique de la base de données
echo "<h2>⚙️ Configuration Base de Données</h2>";

// Utiliser la base de données Render si disponible
if (getenv('DATABASE_URL')) {
    $dbUrl = getenv('DATABASE_URL');
    echo "<p>✅ DATABASE_URL trouvé: $dbUrl</p>";
    
    // Parser DATABASE_URL (format: mysql://user:pass@host:port/dbname)
    $parsed = parse_url($dbUrl);
    if ($parsed) {
        $dbHost = $parsed['host'];
        $dbPort = $parsed['port'] ?? 3306;
        $dbName = ltrim($parsed['path'], '/');
        $dbUser = $parsed['user'];
        $dbPass = $parsed['pass'] ?? '';
        
        echo "<p>Configuration extraite: Host=$dbHost, Port=$dbPort, DB=$dbName, User=$dbUser</p>";
        
        // Créer le fichier .env avec les bonnes valeurs
        $envContent = "APP_NAME=Gestion_Stock\n";
        $envContent .= "APP_ENV=production\n";
        $envContent .= "APP_DEBUG=false\n";
        $envContent .= "APP_KEY=base64:QgpMsiEgxGyD2d4eB4wwXCpOmR8oo2LUF39yw05cjqY=\n";
        $envContent .= "DB_CONNECTION=mysql\n";
        $envContent .= "DB_HOST=$dbHost\n";
        $envContent .= "DB_PORT=$dbPort\n";
        $envContent .= "DB_DATABASE=$dbName\n";
        $envContent .= "DB_USERNAME=$dbUser\n";
        $envContent .= "DB_PASSWORD=$dbPass\n";
        $envContent .= "CACHE_DRIVER=file\n";
        $envContent .= "SESSION_DRIVER=file\n";
        $envContent .= "QUEUE_CONNECTION=sync\n";
        
        file_put_contents('.env', $envContent);
        echo "<p>✅ Fichier .env mis à jour</p>";
    }
} else {
    echo "<p>⚠️ DATABASE_URL non trouvé, utilisation configuration par défaut</p>";
}

// 3. Test de connexion
echo "<h2>🔌 Test Connexion</h2>";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    
    // Forcer la relecture des variables d'environnement
    Dotenv\Dotenv::createImmutable(__DIR__)->load();
    
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    // Test de connexion DB
    $pdo = DB::connection()->getPdo();
    echo "<p>✅ Connexion base de données réussie</p>";
    
    // Vérifier les tables
    $tables = DB::select('SHOW TABLES');
    echo "<p>Tables trouvées: " . count($tables) . "</p>";
    
    // Créer des données de test si nécessaire
    $productCount = DB::table('products')->count();
    $categoryCount = DB::table('categories')->count();
    
    echo "<p>Produits: $productCount, Catégories: $categoryCount</p>";
    
    if ($categoryCount == 0) {
        DB::table('categories')->insert([
            ['name' => 'Électronique', 'description' => 'Produits électroniques', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Alimentation', 'description' => 'Produits alimentaires', 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Vêtements', 'description' => 'Vêtements et accessoires', 'created_at' => now(), 'updated_at' => now()],
        ]);
        echo "<p>✅ Catégories de test créées</p>";
    }
    
    if ($productCount == 0) {
        DB::table('products')->insert([
            ['name' => 'Laptop Dell', 'description' => 'Ordinateur portable Dell', 'current_stock' => 15, 'stock_min' => 5, 'stock_optimal' => 20, 'category_id' => 1, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'Pain frais', 'description' => 'Pain de qualité', 'current_stock' => 50, 'stock_min' => 10, 'stock_optimal' => 100, 'category_id' => 2, 'created_at' => now(), 'updated_at' => now()],
            ['name' => 'T-shirt', 'description' => 'T-shirt en coton', 'current_stock' => 30, 'stock_min' => 8, 'stock_optimal' => 50, 'category_id' => 3, 'created_at' => now(), 'updated_at' => now()],
        ]);
        echo "<p>✅ Produits de test créés</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Erreur: " . $e->getMessage() . "</p>";
}

// 4. Test des routes
echo "<h2>🛣️ Test Routes</h2>";
try {
    $routes = [
        'products' => route('products.index'),
        'categories' => route('categories.index'),
        'predictions' => route('predictions.index')
    ];
    
    foreach ($routes as $name => $url) {
        echo "<p><strong>$name:</strong> $url</p>";
    }
    echo "<p>✅ Routes configurées</p>";
} catch (Exception $e) {
    echo "<p>❌ Erreur routes: " . $e->getMessage() . "</p>";
}

echo "<h2>✅ Configuration terminée!</h2>";
echo "<p><a href='/'>Aller à l'accueil</a> | <a href='/products'>Produits</a> | <a href='/categories'>Catégories</a> | <a href='/predictions'>Prédictions</a></p>";

?>
