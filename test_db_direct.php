<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    // Test direct database connection
    $pdo = DB::connection()->getPdo();
    echo "✅ Database connection successful!\n";
    
    // Test creating tables manually
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255),
            email VARCHAR(255) UNIQUE,
            password VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            parent_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS products (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            barcode VARCHAR(255),
            supplier VARCHAR(255),
            stock_min INT DEFAULT 0,
            stock_optimal INT DEFAULT 2,
            current_stock INT DEFAULT 0,
            price DECIMAL(10,2),
            category_id INT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    echo "✅ Tables created successfully!\n";
    
    // Insert test data
    $pdo->exec("INSERT INTO categories (name, description) VALUES ('Électronique', 'Produits électroniques')");
    $pdo->exec("INSERT INTO categories (name, description) VALUES ('Alimentation', 'Produits alimentaires')");
    $pdo->exec("INSERT INTO products (name, description, current_stock, stock_min, stock_optimal, category_id) VALUES ('Laptop', 'Ordinateur portable', 15, 5, 20, 1)");
    $pdo->exec("INSERT INTO products (name, description, current_stock, stock_min, stock_optimal, category_id) VALUES ('Pain', 'Pain frais', 50, 10, 100, 2)");
    
    echo "✅ Test data inserted!\n";
    
    // Test queries
    $categories = $pdo->query("SELECT COUNT(*) FROM categories")->fetchColumn();
    $products = $pdo->query("SELECT COUNT(*) FROM products")->fetchColumn();
    
    echo "✅ Categories: $categories, Products: $products\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
