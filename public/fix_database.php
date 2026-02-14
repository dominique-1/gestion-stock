<?php

// Script de réparation de base de données pour Render.com
header('Content-Type: text/plain');

echo "🔧 Réparation de la base de données...\n";

try {
    // 1. Créer le répertoire database s'il n'existe pas
    $dbDir = dirname(__DIR__) . '/database';
    if (!is_dir($dbDir)) {
        mkdir($dbDir, 0755, true);
        echo "✅ Répertoire database créé\n";
    } else {
        echo "✅ Répertoire database existe déjà\n";
    }

    // 2. Créer la base de données SQLite
    $dbPath = $dbDir . '/database.sqlite';
    if (!file_exists($dbPath)) {
        touch($dbPath);
        chmod($dbPath, 0755);
        echo "✅ Base de données SQLite créée\n";
    } else {
        echo "✅ Base de données existe déjà\n";
    }

    // 3. Tester la connexion
    $pdo = new PDO('sqlite:' . $dbPath);
    echo "✅ Connexion SQLite réussie\n";

    // 4. Vérifier si les tables existent
    $tables = $pdo->query("SELECT name FROM sqlite_master WHERE type='table'")->fetchAll(PDO::FETCH_COLUMN);
    echo "📋 Tables existantes: " . implode(', ', $tables) . "\n";

    // 5. Si pas de tables, créer les tables de base
    if (empty($tables) || (count($tables) === 1 && in_array('migrations', $tables))) {
        echo "🏗️ Création des tables...\n";

        // Créer les tables de base
        $pdo->exec("
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                email TEXT UNIQUE NOT NULL,
                password TEXT NOT NULL,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS categories (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                name TEXT NOT NULL,
                description TEXT,
                parent_id INTEGER,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $pdo->exec("
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
        ");

        $pdo->exec("
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
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS alerts (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                product_id INTEGER NOT NULL,
                type TEXT NOT NULL,
                message TEXT NOT NULL,
                is_read BOOLEAN DEFAULT 0,
                created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
                updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
            )
        ");

        $pdo->exec("
            CREATE TABLE IF NOT EXISTS migrations (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                migration TEXT NOT NULL,
                batch INTEGER NOT NULL
            )
        ");

        echo "✅ Tables créées\n";

        // 6. Insérer des données de test
        echo "📝 Insertion des données de test...\n";

        $pdo->exec("INSERT INTO categories (name, description) VALUES ('Électronique', 'Produits électroniques')");
        $pdo->exec("INSERT INTO categories (name, description) VALUES ('Alimentation', 'Produits alimentaires')");
        $pdo->exec("INSERT INTO categories (name, description) VALUES ('Vêtements', 'Vêtements et accessoires')");

        $pdo->exec("INSERT INTO products (name, description, current_stock, stock_min, stock_optimal, category_id) VALUES ('Laptop Dell', 'Ordinateur portable Dell', 15, 5, 20, 1)");
        $pdo->exec("INSERT INTO products (name, description, current_stock, stock_min, stock_optimal, category_id) VALUES ('Pain frais', 'Pain de qualité', 50, 10, 100, 2)");
        $pdo->exec("INSERT INTO products (name, description, current_stock, stock_min, stock_optimal, category_id) VALUES ('T-shirt', 'T-shirt en coton', 30, 8, 50, 3)");

        echo "✅ Données de test insérées\n";
    }

    echo "\n🎉 Réparation terminée avec succès!\n";
    echo "🌐 Retour à l'accueil: <a href='/'>Cliquez ici</a>\n";

} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . "\n";
}
?>
