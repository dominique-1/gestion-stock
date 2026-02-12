<?php

// Script de diagnostic pour Render.com
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 Diagnostic Render.com</h1>";

// 1. Vérification des variables d'environnement
echo "<h2>📋 Variables d'environnement</h2>";
$env_vars = [
    'APP_ENV',
    'APP_DEBUG', 
    'DB_CONNECTION',
    'DB_DATABASE',
    'DATABASE_URL'
];

foreach ($env_vars as $var) {
    $value = getenv($var) ?: 'NON DÉFINI';
    echo "<p><strong>$var:</strong> $value</p>";
}

// 2. Vérification des extensions PHP
echo "<h2>🔌 Extensions PHP</h2>";
$required_extensions = ['pdo', 'pdo_mysql', 'pdo_sqlite', 'mbstring', 'tokenizer'];
foreach ($required_extensions as $ext) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo "<p>$status $ext</p>";
}

// 3. Test de connexion à la base de données
echo "<h2>🗄️ Test Base de Données</h2>";
try {
    if (getenv('DB_CONNECTION') === 'sqlite') {
        $db_path = getenv('DB_DATABASE') ?: database_path('database.sqlite');
        echo "<p>Tentative SQLite: $db_path</p>";
        
        if (file_exists($db_path)) {
            $pdo = new PDO("sqlite:$db_path");
            echo "<p>✅ Connexion SQLite réussie</p>";
            
            $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
            echo "<p>Tables: " . implode(', ', $tables) . "</p>";
        } else {
            echo "<p>❌ Fichier SQLite non trouvé: $db_path</p>";
        }
    } else {
        echo "<p>⚠️ Configuration DB non gérée</p>";
    }
} catch (Exception $e) {
    echo "<p>❌ Erreur DB: " . $e->getMessage() . "</p>";
}

// 4. Vérification des permissions
echo "<h2>📁 Permissions</h2>";
$paths = [
    'storage' => storage_path(),
    'bootstrap/cache' => base_path('bootstrap/cache'),
    'public/storage' => public_path('storage')
];

foreach ($paths as $name => $path) {
    $writable = is_writable($path) ? '✅' : '❌';
    $exists = file_exists($path) ? '✅' : '❌';
    echo "<p>$exists $name | $writable writable</p>";
}

// 5. Test Laravel
echo "<h2>🖥️ Test Laravel</h2>";
try {
    require_once __DIR__ . '/vendor/autoload.php';
    $app = require_once __DIR__ . '/bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    echo "<p>✅ Laravel initialisé</p>";
    
    // Test des routes
    $routes = [
        'products' => route('products.index'),
        'categories' => route('categories.index'),
        'predictions' => route('predictions.index')
    ];
    
    foreach ($routes as $name => $url) {
        echo "<p><strong>$name:</strong> $url</p>";
    }
    
} catch (Exception $e) {
    echo "<p>❌ Erreur Laravel: " . $e->getMessage() . "</p>";
}

echo "<h2>📝 Logs d'erreurs récents</h2>";
$log_file = storage_path('logs/laravel.log');
if (file_exists($log_file)) {
    $logs = file_get_contents($log_file);
    $recent_logs = substr($logs, -2000); // Derniers 2000 caractères
    echo "<pre>" . htmlspecialchars($recent_logs) . "</pre>";
} else {
    echo "<p>Pas de fichier de logs trouvé</p>";
}

?>
