<?php

echo "<h1>🔍 TEST SCRIPT D'INITIALISATION</h1>";

try {
    // Test 1: Créer le répertoire
    $dataDir = '/var/data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
        echo "✅ Répertoire /var/data créé<br>";
    } else {
        echo "✅ Répertoire /var/data existe déjà<br>";
    }
    
    // Test 2: Créer la base de données
    $dbPath = '/var/data/database.sqlite';
    if (!file_exists($dbPath)) {
        touch($dbPath);
        chmod($dbPath, 0644);
        echo "✅ Base de données créée<br>";
    } else {
        echo "✅ Base de données existe déjà<br>";
    }
    
    // Test 3: Connexion
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✅ Connexion réussie<br>";
    
    // Test 4: Vérifier les tables
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    echo "<h2>📋 Tables existantes:</h2>";
    if (empty($tables)) {
        echo "❌ Aucune table trouvée<br>";
    } else {
        foreach ($tables as $table) {
            echo "✅ $table<br>";
        }
    }
    
    // Test 5: Créer la table products si elle n'existe pas
    if (!in_array('products', $tables)) {
        $sql = "CREATE TABLE products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT,
            current_stock INTEGER DEFAULT 0,
            stock_min INTEGER DEFAULT 0,
            stock_optimal INTEGER DEFAULT 0,
            price DECIMAL(10, 2) DEFAULT 0,
            category_id INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";
        
        $pdo->exec($sql);
        echo "✅ Table products créée<br>";
        
        // Insérer des données de test
        $pdo->exec("INSERT INTO products (name, description, current_stock, stock_min, stock_optimal, price, category_id) VALUES 
            ('Laptop Dell', 'Ordinateur portable Dell', 15, 5, 20, 999.99, 1),
            ('Pain frais', 'Pain de qualité', 50, 10, 100, 2.50, 2),
            ('T-shirt', 'T-shirt en coton', 30, 8, 50, 19.99, 3)");
        
        echo "✅ Données de test insérées<br>";
    }
    
    // Test 6: Vérifier les produits
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $count = $stmt->fetch(PDO::FETCH_ASSOC);
    echo "<h2>📊 Produits dans la base: {$count['count']}</h2>";
    
    echo "🎉 Test terminé avec succès !";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
