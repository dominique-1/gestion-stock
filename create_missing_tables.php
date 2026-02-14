<?php

require_once __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $pdo = DB::connection()->getPdo();
    
    // Create missing tables
    $tables = [
        "stock_movements" => "
            CREATE TABLE IF NOT EXISTS stock_movements (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                user_id INT NULL,
                type ENUM('in', 'out') NOT NULL,
                quantity INT NOT NULL,
                reason VARCHAR(255),
                moved_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                note TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_product_id (product_id),
                INDEX idx_moved_at (moved_at)
            )
        ",
        "alerts" => "
            CREATE TABLE IF NOT EXISTS alerts (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                type VARCHAR(50) NOT NULL,
                message TEXT NOT NULL,
                is_read BOOLEAN DEFAULT FALSE,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_product_id (product_id),
                INDEX idx_is_read (is_read)
            )
        ",
        "product_documents" => "
            CREATE TABLE IF NOT EXISTS product_documents (
                id INT AUTO_INCREMENT PRIMARY KEY,
                product_id INT NOT NULL,
                filename VARCHAR(255) NOT NULL,
                original_name VARCHAR(255) NOT NULL,
                mime_type VARCHAR(100),
                file_size INT,
                file_path VARCHAR(500),
                document_type VARCHAR(50) DEFAULT 'fiche_technique',
                description TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_product_id (product_id)
            )
        ",
        "inventories" => "
            CREATE TABLE IF NOT EXISTS inventories (
                id INT AUTO_INCREMENT PRIMARY KEY,
                reference VARCHAR(100) UNIQUE NOT NULL,
                status ENUM('draft', 'in_progress', 'completed') DEFAULT 'draft',
                created_by INT NULL,
                completed_at TIMESTAMP NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_status (status),
                INDEX idx_reference (reference)
            )
        ",
        "inventory_lines" => "
            CREATE TABLE IF NOT EXISTS inventory_lines (
                id INT AUTO_INCREMENT PRIMARY KEY,
                inventory_id INT NOT NULL,
                product_id INT NOT NULL,
                expected_quantity INT DEFAULT 0,
                actual_quantity INT DEFAULT 0,
                difference INT DEFAULT 0,
                notes TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                INDEX idx_inventory_id (inventory_id),
                INDEX idx_product_id (product_id)
            )
        "
    ];
    
    foreach ($tables as $name => $sql) {
        try {
            $pdo->exec($sql);
            echo "✅ Table '$name' created successfully\n";
        } catch (Exception $e) {
            echo "❌ Error creating table '$name': " . $e->getMessage() . "\n";
        }
    }
    
    // Add foreign key constraints
    $constraints = [
        "ALTER TABLE stock_movements ADD CONSTRAINT fk_stock_movements_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE",
        "ALTER TABLE alerts ADD CONSTRAINT fk_alerts_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE",
        "ALTER TABLE product_documents ADD CONSTRAINT fk_product_documents_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE",
        "ALTER TABLE inventory_lines ADD CONSTRAINT fk_inventory_lines_inventory FOREIGN KEY (inventory_id) REFERENCES inventories(id) ON DELETE CASCADE",
        "ALTER TABLE inventory_lines ADD CONSTRAINT fk_inventory_lines_product FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE"
    ];
    
    foreach ($constraints as $constraint) {
        try {
            $pdo->exec($constraint);
            echo "✅ Constraint added successfully\n";
        } catch (Exception $e) {
            echo "⚠️ Constraint warning: " . $e->getMessage() . "\n";
        }
    }
    
    echo "\n✅ All tables created successfully!\n";
    
} catch (Exception $e) {
    echo "❌ ERROR: " . $e->getMessage() . "\n";
}
