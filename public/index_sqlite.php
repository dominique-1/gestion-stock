<?php

// DÉSACTIVER LARAVEL ET UTILISER SQLITE DIRECTEMENT
try {
    // Créer le répertoire de données
    $dataDir = '/var/data';
    if (!is_dir($dataDir)) {
        mkdir($dataDir, 0755, true);
    }
    
    // Créer la base de données
    $dbPath = '/var/data/database.sqlite';
    if (!file_exists($dbPath)) {
        touch($dbPath);
        chmod($dbPath, 0644);
    }
    
    // Connexion directe à SQLite
    $pdo = new PDO('sqlite:' . $dbPath);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier et créer les tables
    $stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('products', $tables)) {
        // Créer toutes les tables
        $pdo->exec("CREATE TABLE categories (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            name TEXT NOT NULL,
            description TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
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
        
        $pdo->exec("CREATE TABLE stock_movements (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            product_id INTEGER,
            type TEXT NOT NULL,
            quantity INTEGER NOT NULL,
            reason TEXT,
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Insérer des données
        $pdo->exec("INSERT INTO categories (name, description) VALUES 
            ('Électronique', 'Produits électroniques'),
            ('Alimentation', 'Produits alimentaires'),
            ('Vêtements', 'Vêtements et accessoires')");
        
        $pdo->exec("INSERT INTO products (name, description, current_stock, stock_min, stock_optimal, price, category_id) VALUES 
            ('Laptop Dell', 'Ordinateur portable Dell', 15, 5, 20, 999.99, 1),
            ('Pain frais', 'Pain de qualité', 50, 10, 100, 2.50, 2),
            ('T-shirt', 'T-shirt en coton', 30, 8, 50, 19.99, 3)");
    }
    
    // Afficher un message de succès
    echo "<!DOCTYPE html>
    <html>
    <head>
        <title>Gestion Stock - Base de données initialisée</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .success { color: #28a745; font-size: 24px; margin-bottom: 20px; }
            .info { color: #6c757d; margin: 10px 0; }
            .nav { margin-top: 30px; }
            .nav a { display: inline-block; margin: 10px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
            .nav a:hover { background: #0056b3; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='success'>✅ Base de données SQLite initialisée avec succès !</div>
            <div class='info'>📊 Tables créées : products, categories, predictions, stock_movements</div>
            <div class='info'>📦 Données de test insérées : 3 produits, 3 catégories</div>
            <div class='info'>🗄️ Base de données : /var/data/database.sqlite</div>
            
            <div class='nav'>
                <a href='/'>🏠 Accueil application</a>
                <a href='/test_init.php'>🔍 Test base de données</a>
            </div>
        </div>
    </body>
    </html>";
    
} catch (Exception $e) {
    echo "<h1>❌ Erreur: " . $e->getMessage() . "</h1>";
}
?>
