<?php

echo "=== Diagnostic de la base de données ===" . PHP_EOL;

// 1. Vérifier les extensions PDO
echo "1. Extensions PDO disponibles:" . PHP_EOL;
$pdo_drivers = PDO::getAvailableDrivers();
if (empty($pdo_drivers)) {
    echo "❌ Aucun driver PDO disponible" . PHP_EOL;
} else {
    echo "✅ Drivers PDO: " . implode(', ', $pdo_drivers) . PHP_EOL;
}

// 2. Vérifier si mysql est disponible
echo PHP_EOL . "2. Driver MySQL: ";
if (in_array('mysql', $pdo_drivers)) {
    echo "✅ Disponible" . PHP_EOL;
} else {
    echo "❌ Non disponible" . PHP_EOL;
}

// 3. Vérifier la configuration Laravel
echo PHP_EOL . "3. Configuration Laravel:" . PHP_EOL;
echo "DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'non défini') . PHP_EOL;
echo "DB_HOST: " . (getenv('DB_HOST') ?: 'non défini') . PHP_EOL;
echo "DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'non défini') . PHP_EOL;
echo "DB_USERNAME: " . (getenv('DB_USERNAME') ?: 'non défini') . PHP_EOL;

// 4. Test de connexion directe PDO
echo PHP_EOL . "4. Test de connexion PDO directe:" . PHP_EOL;
if (in_array('mysql', $pdo_drivers)) {
    try {
        $dsn = 'mysql:host=' . (getenv('DB_HOST', '127.0.0.1') . ';dbname=' . (getenv('DB_DATABASE', 'stock'));
        $pdo = new PDO($dsn, getenv('DB_USERNAME', 'root'), getenv('DB_PASSWORD', ''));
        echo "✅ Connexion PDO MySQL réussie" . PHP_EOL;
    } catch (PDOException $e) {
        echo "❌ Erreur PDO: " . $e->getMessage() . PHP_EOL;
    }
} else {
    echo "⚠️  Test PDO MySQL sauté (driver non disponible)" . PHP_EOL;
}

echo PHP_EOL;

// 5. Test avec Laravel
echo PHP_EOL . "5. Test avec Laravel:" . PHP_EOL;
try {
    require_once 'vendor/autoload.php';
    $app = require_once 'bootstrap/app.php';
    
    use Illuminate\Support\Facades\DB;
    
    $pdo = DB::connection()->getPdo();
    echo "✅ Connexion Laravel réussie" . PHP_EOL;
    echo "Driver: " . $pdo->getAttribute(PDO::ATTR_DRIVER_NAME) . PHP_EOL;
    echo "Database: " . $pdo->getAttribute(PDO::ATTR_DB_NAME) . PHP_EOL;
} catch (Exception $e) {
    echo "❌ Erreur Laravel: " . $e->getMessage() . PHP_EOL;
}

echo PHP_EOL . "=== Diagnostic terminé ===" . PHP_EOL;
