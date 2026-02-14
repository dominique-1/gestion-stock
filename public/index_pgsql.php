<?php

// CONNEXION DIRECTE À POSTGRESQL SANS LARAVEL
try {
    // Connexion PostgreSQL directe
    $pdo = new PDO('pgsql:host=dpg-d668asmsb7us73ckg96g-a;port=5432;dbname=gestion_stock_2026', 'gestion_stock_2026_user', 'D8gXzuYh4Luitly3Ly9Kv0Rkwpk64nm2');
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Vérifier si les tables existent
    $stmt = $pdo->query("SELECT table_name FROM information_schema.tables WHERE table_schema = 'public'");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (!in_array('products', $tables)) {
        // Créer les tables essentielles
        $pdo->exec("CREATE TABLE categories (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        $pdo->exec("CREATE TABLE products (
            id SERIAL PRIMARY KEY,
            name VARCHAR(255) NOT NULL,
            description TEXT,
            barcode VARCHAR(255) UNIQUE,
            supplier VARCHAR(255),
            price DECIMAL(10, 2) DEFAULT 0,
            category_id INTEGER REFERENCES categories(id),
            stock_min INTEGER DEFAULT 0,
            stock_optimal INTEGER DEFAULT 0,
            current_stock INTEGER DEFAULT 0,
            expires_at TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        $pdo->exec("CREATE TABLE predictions (
            id SERIAL PRIMARY KEY,
            product_id INTEGER REFERENCES products(id),
            prediction_date DATE,
            predicted_stock INTEGER,
            predicted_movements INTEGER,
            confidence DECIMAL(5, 2),
            algorithm VARCHAR(50) DEFAULT 'linear',
            historical_data JSONB,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Insérer des données de test
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
        <title>Gestion Stock - PostgreSQL Connecté</title>
        <style>
            body { font-family: Arial, sans-serif; margin: 40px; background: #f5f5f5; }
            .container { max-width: 800px; margin: 0 auto; background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
            .success { color: #28a745; font-size: 24px; margin-bottom: 20px; }
            .info { color: #6c757d; margin: 10px 0; }
            .nav { margin-top: 30px; }
            .nav a { display: inline-block; margin: 10px; padding: 10px 20px; background: #007bff; color: white; text-decoration: none; border-radius: 5px; }
            .nav a:hover { background: #0056b3; }
            .data { background: #f8f9fa; padding: 15px; border-radius: 5px; margin: 10px 0; }
        </style>
    </head>
    <body>
        <div class='container'>
            <div class='success'>✅ PostgreSQL connecté avec succès !</div>
            <div class='info'>🗄️ Base de données : gestion_stock_2026</div>
            <div class='info'>🌐 Serveur : dpg-d668asmsb7us73ckg96g-a:5432</div>
            <div class='info'>👤 Utilisateur : gestion_stock_2026_user</div>
            
            <div class='data'>
                <h3>📊 Tables trouvées :</h3>";
                foreach ($tables as $table) {
                    echo "<div style='margin: 5px 0;'>✅ $table</div>";
                }
                echo "</div>
            
            <div class='nav'>
                <a href='/'>🏠 Accueil application</a>
                <a href='/test_init.php'>🔍 Test base de données</a>
            </div>
        </div>
    </body>
    </html>";
    
} catch (Exception $e) {
    echo "<h1>❌ Erreur PostgreSQL: " . $e->getMessage() . "</h1>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
}
?>
