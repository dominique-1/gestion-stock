<?php

// Script de création de la base de données SQLite pour Render
try {
    // Créer le répertoire de données s'il n'existe pas
    $dataDir = '/var/data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    
    // Créer le fichier de base de données
    $dbPath = '/var/data/database.sqlite';
    if (!file_exists($dbPath)) {
        touch($dbPath);
        chmod($dbPath, 0644);
    }
    
    // Connexion à la base de données
    $pdo = new PDO('sqlite:' . $dbPath);
    
    // Créer les tables
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        );
        
        CREATE TABLE IF NOT EXISTS products (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            reference VARCHAR(255),
            current_stock INTEGER DEFAULT 0,
            stock_min INTEGER DEFAULT 0,
            stock_optimal INTEGER DEFAULT 0,
            price DECIMAL(10, 2) DEFAULT 0,
            category_id INTEGER,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES categories(id)
        );
        
        CREATE TABLE IF NOT EXISTS stock_movements (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_id INTEGER NOT NULL,
            type VARCHAR(10) NOT NULL,
            quantity INTEGER NOT NULL,
            reason TEXT,
            user_id INTEGER,
            moved_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (product_id) REFERENCES products(id)
        );
    ");
    
    // Insérer des données de test
    $pdo->exec("
        INSERT OR IGNORE INTO categories (id, name, description) VALUES 
        (1, 'Électronique', 'Produits électroniques'),
        (2, 'Alimentation', 'Produits alimentaires'),
        (3, 'Vêtements', 'Vêtements et accessoires');
        
        INSERT OR IGNORE INTO products (id, name, description, reference, current_stock, stock_min, stock_optimal, price, category_id) VALUES 
        (1, 'Laptop Dell', 'Ordinateur portable Dell', 'REF001', 15, 5, 20, 999.99, 1),
        (2, 'Pain frais', 'Pain de qualité', 'REF002', 50, 10, 100, 2.50, 2),
        (3, 'T-shirt', 'T-shirt en coton', 'REF003', 30, 8, 50, 19.99, 3);
        
        INSERT OR IGNORE INTO stock_movements (product_id, type, quantity, reason, moved_at) VALUES 
        (1, 'entrée', 10, 'Réception stock', datetime('now', '-1 day')),
        (2, 'sortie', 5, 'Vente', datetime('now', '-2 days')),
        (3, 'entrée', 20, 'Réapprovisionnement', datetime('now', '-3 days'));
    ");
    
    echo "✅ Base de données SQLite créée avec succès !";
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage();
}
?>
