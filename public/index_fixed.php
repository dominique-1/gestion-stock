<?php

define('LARAVEL_START', microtime(true));

// FORCER SQLITE DANS LES VARIABLES D'ENVIRONNEMENT
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = '/var/data/database.sqlite';
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=/var/data/database.sqlite');

// INITIALISATION DE LA BASE DE DONNÉES AVANT LARAVEL
try {
    $dataDir = '/var/data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    
    $dbPath = '/var/data/database.sqlite';
    if (!file_exists($dbPath)) {
        touch($dbPath);
        chmod($dbPath, 0644);
    }
    
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si la table products existe
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name='products'");
    if (!$stmt->fetch()) {
        // Créer les tables essentielles
        $pdo->exec("CREATE TABLE products (
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
        )");
        
        $pdo->exec("CREATE TABLE categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        $pdo->exec("CREATE TABLE predictions (
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
        )");
        
        // Insérer des données de base
        $pdo->exec("INSERT INTO categories (name, description) VALUES 
            ('Électronique', 'Produits électroniques'),
            ('Alimentation', 'Produits alimentaires'),
            ('Vêtements', 'Vêtements et accessoires')");
        
        $pdo->exec("INSERT INTO products (name, description, current_stock, stock_min, stock_optimal, price, category_id) VALUES 
            ('Laptop Dell', 'Ordinateur portable Dell', 15, 5, 20, 999.99, 1),
            ('Pain frais', 'Pain de qualité', 50, 10, 100, 2.50, 2),
            ('T-shirt', 'T-shirt en coton', 30, 8, 50, 19.99, 3)");
    }
} catch (Exception $e) {
    // Continuer même si l'initialisation échoue
}

// Charger Laravel normalement
require_once __DIR__ . '/../index.php';
