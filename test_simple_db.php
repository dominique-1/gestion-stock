<?php

echo "=== Test simple de base de données SQLite ===" . PHP_EOL;

try {
    // Test avec SQLite (plus simple)
    $dbPath = __DIR__ . '/database.sqlite';
    
    if (!file_exists($dbPath)) {
        echo "Création de la base de données SQLite..." . PHP_EOL;
        $pdo = new PDO("sqlite:$dbPath");
        
        // Création d'une table simple
        $pdo->exec("CREATE TABLE IF NOT EXISTS test_table (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        echo "✅ Base de données SQLite créée avec succès" . PHP_EOL;
    } else {
        $pdo = new PDO("sqlite:$dbPath");
        echo "✅ Connexion à la base de données SQLite existante" . PHP_EOL;
    }
    
    // Test d'insertion
    $stmt = $pdo->prepare("INSERT INTO test_table (name) VALUES (?)");
    $stmt->execute(['Test entry ' . date('Y-m-d H:i:s')]);
    echo "✅ Insertion réussie" . PHP_EOL;
    
    // Test de sélection
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM test_table");
    $count = $stmt->fetchColumn();
    echo "✅ Nombre d'enregistrements: $count" . PHP_EOL;
    
    echo PHP_EOL . "=== Test réussi! ===" . PHP_EOL;
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . PHP_EOL;
}
