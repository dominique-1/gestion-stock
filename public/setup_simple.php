<?php

// Setup ultra-simple pour Render
try {
    // Créer le répertoire de données
    $dataDir = '/var/data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    
    // Créer la base de données SQLite
    $dbPath = '/var/data/database.sqlite';
    if (!file_exists($dbPath)) {
        touch($dbPath);
        chmod($dbPath, 0644);
    }
    
    // Connexion et création des tables
    $pdo = new PDO('sqlite:' . $dbPath);
    
    // Tables de base
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS products (
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
        );
        
        CREATE TABLE IF NOT EXISTS predictions (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_id INTEGER,
            prediction_date DATE,
            predicted_stock INTEGER,
            predicted_movements INTEGER,
            confidence DECIMAL(5, 2),
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
    ");
    
    // Données de test
    $pdo->exec("
        INSERT OR IGNORE INTO categories (id, name, description) VALUES 
        (1, 'Électronique', 'Produits électroniques'),
        (2, 'Alimentation', 'Produits alimentaires'),
        (3, 'Vêtements', 'Vêtements et accessoires');
        
        INSERT OR IGNORE INTO products (id, name, description, current_stock, stock_min, stock_optimal, price, category_id) VALUES 
        (1, 'Laptop Dell', 'Ordinateur portable Dell', 15, 5, 20, 999.99, 1),
        (2, 'Pain frais', 'Pain de qualité', 50, 10, 100, 2.50, 2),
        (3, 'T-shirt', 'T-shirt en coton', 30, 8, 50, 19.99, 3);
    ");
    
    echo "✅ Setup terminé avec succès";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage();
}
?>
