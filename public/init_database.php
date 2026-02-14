<?php

// Script d'initialisation de la base de données
try {
    // Créer le répertoire de données
    $dataDir = '/var/data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
        echo "✅ Répertoire /var/data créé<br>";
    }
    
    // Créer la base de données SQLite
    $dbPath = '/var/data/database.sqlite';
    if (!file_exists($dbPath)) {
        touch($dbPath);
        chmod($dbPath, 0644);
        echo "✅ Base de données SQLite créée<br>";
    }
    
    // Connexion
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    echo "✅ Connexion à la base de données réussie<br>";
    
    // Créer les tables
    $tables = [
        "CREATE TABLE IF NOT EXISTS migrations (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            migration VARCHAR(255) NOT NULL,
            batch INTEGER NOT NULL
        )",
        
        "CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT,
            barcode TEXT UNIQUE,
            supplier TEXT,
            price DECIMAL(10, 2) DEFAULT 0,
            category_id INTEGER,
            stock_min INTEGER DEFAULT 0,
            stock_optimal INTEGER DEFAULT 0,
            current_stock INTEGER DEFAULT 0,
            expires_at DATETIME,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS predictions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_id INTEGER,
            prediction_date DATE,
            predicted_stock INTEGER,
            predicted_movements INTEGER,
            confidence DECIMAL(5, 2),
            algorithm TEXT DEFAULT 'linear',
            historical_data TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )",
        
        "CREATE TABLE IF NOT EXISTS stock_movements (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_id INTEGER,
            type TEXT NOT NULL,
            quantity INTEGER NOT NULL,
            reason TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )"
    ];
    
    foreach ($tables as $sql) {
        $pdo->exec($sql);
        echo "✅ Table créée avec succès<br>";
    }
    
    // Insérer les données de test
    $pdo->exec("INSERT OR IGNORE INTO categories (id, name, description) VALUES 
        (1, 'Électronique', 'Produits électroniques'),
        (2, 'Alimentation', 'Produits alimentaires'),
        (3, 'Vêtements', 'Vêtements et accessoires')");
    
    $pdo->exec("INSERT OR IGNORE INTO products (id, name, description, current_stock, stock_min, stock_optimal, price, category_id) VALUES 
        (1, 'Laptop Dell', 'Ordinateur portable Dell', 15, 5, 20, 999.99, 1),
        (2, 'Pain frais', 'Pain de qualité', 50, 10, 100, 2.50, 2),
        (3, 'T-shirt', 'T-shirt en coton', 30, 8, 50, 19.99, 3)");
    
    $pdo->exec("INSERT OR IGNORE INTO stock_movements (product_id, type, quantity, reason) VALUES 
        (1, 'entrée', 10, 'Réception stock'),
        (2, 'sortie', 5, 'Vente'),
        (3, 'entrée', 20, 'Réapprovisionnement')");
    
    echo "✅ Données de test insérées avec succès<br>";
    echo "🎉 Base de données initialisée correctement !";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage();
}
?>
