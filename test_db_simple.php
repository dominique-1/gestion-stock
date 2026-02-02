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
        $host = getenv('DB_HOST', '127.0.0.1');
        $database = getenv('DB_DATABASE', 'stock');
        $username = getenv('DB_USERNAME', 'root');
        $password = getenv('DB_PASSWORD', '');
        
        $dsn = 'mysql:host=' . $host . ';dbname=' . $database;
        $pdo = new PDO($dsn, $username, $password);
        echo "✅ Connexion PDO MySQL réussie" . PHP_EOL;
    } catch (PDOException $e) {
        echo "❌ Erreur PDO: " . $e->getMessage() . PHP_EOL;
    }
} else {
    echo "⚠️  Test PDO MySQL sauté (driver non disponible)" . PHP_EOL;
}

echo PHP_EOL . "=== Diagnostic terminé ===" . PHP_EOL;
