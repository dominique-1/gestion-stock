<?php

// Script de réparation pour Render.com
error_reporting(E_ALL);
ini_set('display_errors', 1);

echo "<h1>🔧 Réparation Render.com</h1>";

// 1. Création des répertoires nécessaires
echo "<h2>📁 Création des répertoires</h2>";
$dirs = [
    'storage/app',
    'storage/framework/cache',
    'storage/framework/sessions', 
    'storage/framework/views',
    'storage/logs',
    'bootstrap/cache'
];

foreach ($dirs as $dir) {
    $full_path = base_path($dir);
    if (!file_exists($full_path)) {
        mkdir($full_path, 0755, true);
        echo "<p>✅ Créé: $dir</p>";
    } else {
        echo "<p>✅ Existe déjà: $dir</p>";
    }
}

// 2. Création de la base SQLite
echo "<h2>🗄️ Initialisation SQLite</h2>";
$db_path = database_path('database.sqlite');

if (!file_exists($db_path)) {
    try {
        $pdo = new PDO("sqlite:$db_path");
        
        // Création des tables de base
        $tables = [
            "users" => "
                CREATE TABLE IF NOT EXISTS users (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    email TEXT UNIQUE NOT NULL,
                    password TEXT NOT NULL,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ",
            "categories" => "
                CREATE TABLE IF NOT EXISTS categories (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    description TEXT,
                    parent_id INTEGER,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ",
            "products" => "
                CREATE TABLE IF NOT EXISTS products (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    name TEXT NOT NULL,
                    description TEXT,
                    barcode TEXT,
                    supplier TEXT,
                    stock_min INTEGER DEFAULT 0,
                    stock_optimal INTEGER DEFAULT 2,
                    current_stock INTEGER DEFAULT 0,
                    price DECIMAL(10,2),
                    category_id INTEGER,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ",
            "stock_movements" => "
                CREATE TABLE IF NOT EXISTS stock_movements (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    product_id INTEGER NOT NULL,
                    user_id INTEGER,
                    type TEXT CHECK(type IN ('in', 'out')) NOT NULL,
                    quantity INTEGER NOT NULL,
                    reason TEXT,
                    moved_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    note TEXT,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ",
            "alerts" => "
                CREATE TABLE IF NOT EXISTS alerts (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    product_id INTEGER NOT NULL,
                    type TEXT NOT NULL,
                    message TEXT NOT NULL,
                    is_read BOOLEAN DEFAULT 0,
                    created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                    updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
                )
            ",
            "migrations" => "
                CREATE TABLE IF NOT EXISTS migrations (
                    id INTEGER PRIMARY KEY AUTOINCREMENT,
                    migration TEXT NOT NULL,
                    batch INTEGER NOT NULL
                )
            "
        ];
        
        foreach ($tables as $name => $sql) {
            $pdo->exec($sql);
            echo "<p>✅ Table '$name' créée</p>";
        }
        
        // Insertion des données de base
        $pdo->exec("INSERT INTO categories (name, description) VALUES ('Électronique', 'Produits électroniques')");
        $pdo->exec("INSERT INTO categories (name, description) VALUES ('Alimentation', 'Produits alimentaires')");
        $pdo->exec("INSERT INTO categories (name, description) VALUES ('Vêtements', 'Vêtements et accessoires')");
        
        $pdo->exec("INSERT INTO products (name, description, current_stock, stock_min, stock_optimal, category_id) VALUES ('Laptop Dell', 'Ordinateur portable Dell', 15, 5, 20, 1)");
        $pdo->exec("INSERT INTO products (name, description, current_stock, stock_min, stock_optimal, category_id) VALUES ('Pain frais', 'Pain de qualité', 50, 10, 100, 2)");
        $pdo->exec("INSERT INTO products (name, description, current_stock, stock_min, stock_optimal, category_id) VALUES ('T-shirt', 'T-shirt en coton', 30, 8, 50, 3)");
        
        echo "<p>✅ Données de test insérées</p>";
        
    } catch (Exception $e) {
        echo "<p>❌ Erreur SQLite: " . $e->getMessage() . "</p>";
    }
} else {
    echo "<p>✅ Base SQLite existe déjà</p>";
}

// 3. Nettoyage du cache
echo "<h2>🧹 Nettoyage du cache</h2>";
try {
    $cache_files = [
        storage_path('framework/cache/*'),
        storage_path('framework/views/*'),
        bootstrap_path('cache/*')
    ];
    
    foreach ($cache_files as $pattern) {
        $files = glob($pattern);
        foreach ($files as $file) {
            if (is_file($file)) {
                unlink($file);
            }
        }
    }
    echo "<p>✅ Cache nettoyé</p>";
} catch (Exception $e) {
    echo "<p>⚠️ Erreur cache: " . $e->getMessage() . "</p>";
}

// 4. Optimisation SQLite
echo "<h2>⚡ Optimisation</h2>";
try {
    $pdo = new PDO("sqlite:$db_path");
    $pdo->exec("VACUUM");
    $pdo->exec("ANALYZE");
    echo "<p>✅ Base optimisée</p>";
} catch (Exception $e) {
    echo "<p>⚠️ Erreur optimisation: " . $e->getMessage() . "</p>";
}

echo "<h2>✅ Réparation terminée!</h2>";
echo "<p><a href='/'>Retour à l'accueil</a></p>";

?>
