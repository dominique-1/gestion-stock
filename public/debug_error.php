<?php

// Activer tous les rapports d'erreur
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔍 DEBUG ERREUR 500</h1>";

// Afficher les variables d'environnement
echo "<h2>📋 Variables d'environnement</h2>";
echo "<pre>";
var_dump([
    'APP_ENV' => getenv('APP_ENV'),
    'DB_CONNECTION' => getenv('DB_CONNECTION'),
    'DB_HOST' => getenv('DB_HOST'),
    'DB_DATABASE' => getenv('DB_DATABASE'),
    'DB_USERNAME' => getenv('DB_USERNAME'),
    'DB_PASSWORD' => getenv('DB_PASSWORD') ? '***' : 'NULL'
]);
echo "</pre>";

// Afficher $_ENV
echo "<h2>📋 \$_ENV</h2>";
echo "<pre>";
var_dump([
    'DB_CONNECTION' => $_ENV['DB_CONNECTION'] ?? 'NON DÉFINI',
    'DB_DATABASE' => $_ENV['DB_DATABASE'] ?? 'NON DÉFINI'
]);
echo "</pre>";

// Tester la connexion à la base de données
echo "<h2>🗄️ Test connexion BDD</h2>";
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    
    $db = \Illuminate\Support\Facades\DB::connection();
    echo "✅ Connexion réussie : " . get_class($db) . "<br>";
    echo "📊 Base de données : " . $db->getDatabaseName() . "<br>";
    
} catch (Exception $e) {
    echo "❌ Erreur connexion : " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}

// Tester si Laravel fonctionne
echo "<h2>🚀 Test Laravel</h2>";
try {
    $response = file_get_contents('http://localhost:' . ($_SERVER['PORT'] ?? '10000'));
    echo "✅ Laravel répond : " . substr($response, 0, 100) . "...<br>";
} catch (Exception $e) {
    echo "❌ Erreur Laravel : " . $e->getMessage() . "<br>";
}

?>
