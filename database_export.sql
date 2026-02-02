-- Système de Gestion de Stock - Export SQL
-- Généré le : 2025-01-07
-- Version : 1.0.0

-- Création de la base de données
CREATE DATABASE IF NOT EXISTS `stock` DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE `stock`;

-- =============================================
-- Table : users
-- =============================================
DROP TABLE IF EXISTS `users`;
CREATE TABLE `users` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `role` enum('admin','manager','viewer') NOT NULL DEFAULT 'viewer',
  `is_active` tinyint(1) NOT NULL DEFAULT '1',
  `email_alerts_enabled` tinyint(1) NOT NULL DEFAULT '1',
  `email_alerts_frequency` enum('immediate','daily','weekly') NOT NULL DEFAULT 'immediate',
  PRIMARY KEY (`id`),
  UNIQUE KEY `users_email_unique` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table : categories
-- =============================================
DROP TABLE IF EXISTS `categories`;
CREATE TABLE `categories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `parent_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `categories_parent_id_foreign` (`parent_id`),
  CONSTRAINT `categories_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table : products
-- =============================================
DROP TABLE IF EXISTS `products`;
CREATE TABLE `products` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(255) NOT NULL,
  `description` text,
  `barcode` varchar(255) DEFAULT NULL,
  `supplier` varchar(255) DEFAULT NULL,
  `price` decimal(10,2) DEFAULT NULL,
  `category_id` bigint unsigned DEFAULT NULL,
  `stock_min` int NOT NULL DEFAULT '0',
  `stock_optimal` int NOT NULL DEFAULT '0',
  `current_stock` int NOT NULL DEFAULT '0',
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `products_category_id_foreign` (`category_id`),
  CONSTRAINT `products_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table : product_documents
-- =============================================
DROP TABLE IF EXISTS `product_documents`;
CREATE TABLE `product_documents` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `original_name` varchar(255) NOT NULL,
  `file_path` varchar(255) NOT NULL,
  `file_size` int DEFAULT NULL,
  `mime_type` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `product_documents_product_id_foreign` (`product_id`),
  CONSTRAINT `product_documents_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table : stock_movements
-- =============================================
DROP TABLE IF EXISTS `stock_movements`;
CREATE TABLE `stock_movements` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `product_id` bigint unsigned NOT NULL,
  `type` enum('in','out') NOT NULL,
  `quantity` int NOT NULL,
  `reason` varchar(255) NOT NULL,
  `moved_at` timestamp NOT NULL,
  `note` text,
  `user_id` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `stock_movements_product_id_foreign` (`product_id`),
  KEY `stock_movements_user_id_foreign` (`user_id`),
  CONSTRAINT `stock_movements_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `stock_movements_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table : inventories
-- =============================================
DROP TABLE IF EXISTS `inventories`;
CREATE TABLE `inventories` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `reference` varchar(255) NOT NULL,
  `status` enum('draft','in_progress','completed','archived') NOT NULL DEFAULT 'draft',
  `started_at` timestamp NULL DEFAULT NULL,
  `completed_at` timestamp NULL DEFAULT NULL,
  `notes` text,
  `user_id` bigint unsigned NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventories_user_id_foreign` (`user_id`),
  CONSTRAINT `inventories_user_id_foreign` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table : inventory_lines
-- =============================================
DROP TABLE IF EXISTS `inventory_lines`;
CREATE TABLE `inventory_lines` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `inventory_id` bigint unsigned NOT NULL,
  `product_id` bigint unsigned NOT NULL,
  `theoretical_stock` int NOT NULL DEFAULT '0',
  `counted_stock` int NOT NULL DEFAULT '0',
  `difference` int NOT NULL DEFAULT '0',
  `notes` text,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `inventory_lines_inventory_id_foreign` (`inventory_id`),
  KEY `inventory_lines_product_id_foreign` (`product_id`),
  CONSTRAINT `inventory_lines_inventory_id_foreign` FOREIGN KEY (`inventory_id`) REFERENCES `inventories` (`id`) ON DELETE CASCADE,
  CONSTRAINT `inventory_lines_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Table : alerts
-- =============================================
DROP TABLE IF EXISTS `alerts`;
CREATE TABLE `alerts` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `type` enum('min_stock','overstock','expiry_soon','critical','warning','info','prediction_risk') NOT NULL,
  `product_id` bigint unsigned DEFAULT NULL,
  `message` text NOT NULL,
  `level` enum('critical','warning','info') NOT NULL,
  `is_read` tinyint(1) NOT NULL DEFAULT '0',
  `created_by` bigint unsigned DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `email_sent_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `alerts_product_id_foreign` (`product_id`),
  KEY `alerts_created_by_foreign` (`created_by`),
  CONSTRAINT `alerts_product_id_foreign` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE CASCADE,
  CONSTRAINT `alerts_created_by_foreign` FOREIGN KEY (`created_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Tables Laravel par défaut
-- =============================================

-- Table : password_reset_tokens
DROP TABLE IF EXISTS `password_reset_tokens`;
CREATE TABLE `password_reset_tokens` (
  `email` varchar(255) NOT NULL,
  `token` varchar(255) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table : failed_jobs
DROP TABLE IF EXISTS `failed_jobs`;
CREATE TABLE `failed_jobs` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `uuid` varchar(255) NOT NULL,
  `connection` text NOT NULL,
  `queue` text NOT NULL,
  `payload` longtext NOT NULL,
  `exception` longtext NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Table : personal_access_tokens
DROP TABLE IF EXISTS `personal_access_tokens`;
CREATE TABLE `personal_access_tokens` (
  `id` bigint unsigned NOT NULL AUTO_INCREMENT,
  `tokenable_type` varchar(255) NOT NULL,
  `tokenable_id` bigint unsigned NOT NULL,
  `name` varchar(255) NOT NULL,
  `token` varchar(64) NOT NULL,
  `abilities` text,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =============================================
-- Données d'exemple (seeders)
-- =============================================

-- Utilisateurs par défaut
INSERT INTO `users` (`name`, `email`, `password`, `role`, `is_active`, `created_at`, `updated_at`) VALUES
('Administrateur', 'admin@stock.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'admin', 1, NOW(), NOW()),
('Gestionnaire', 'manager@stock.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'manager', 1, NOW(), NOW()),
('Utilisateur', 'user@stock.com', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'viewer', 1, NOW(), NOW());

-- Catégories par défaut
INSERT INTO `categories` (`name`, `description`, `created_at`, `updated_at`) VALUES
('Électronique', 'Produits électroniques et informatiques', NOW(), NOW()),
('Alimentation', 'Produits alimentaires et boissons', NOW(), NOW()),
('Vêtements', 'Textiles et vêtements', NOW(), NOW()),
('Mobilier', 'Meubles et décoration', NOW(), NOW()),
('Hygiène', 'Produits d\'hygiène et cosmétiques', NOW(), NOW());

-- Produits d'exemple
INSERT INTO `products` (`name`, `description`, `barcode`, `supplier`, `price`, `category_id`, `stock_min`, `stock_optimal`, `current_stock`, `created_at`, `updated_at`) VALUES
('Laptop Pro 15"', 'Ordinateur portable haute performance', '1234567890123', 'TechSupplier', 999.99, 1, 5, 20, 15, NOW(), NOW()),
('Clavier mécanique', 'Clavier gaming RGB', '2345678901234', 'GearTech', 89.99, 1, 10, 50, 35, NOW(), NOW()),
('Souris sans fil', 'Souris ergonomique Bluetooth', '3456789012345', 'GearTech', 49.99, 1, 15, 60, 45, NOW(), NOW()),
('Café en grains 1kg', 'Café arabica premium', '4567890123456', 'CoffeeCo', 24.99, 2, 20, 100, 75, NOW(), NOW()),
('Thé vert 500g', 'Thé vert bio', '5678901234567', 'TeaMaster', 19.99, 2, 25, 80, 60, NOW(), NOW()),
('T-shirt coton', 'T-shirt 100% coton bio', '6789012345678', 'EcoWear', 29.99, 3, 30, 100, 85, NOW(), NOW()),
('Jean denim', 'Jean slim fit', '7890123456789', 'EcoWear', 79.99, 3, 20, 60, 40, NOW(), NOW()),
('Bureau bureau', 'Bureau en bois massif', '8901234567890', 'FurniPro', 299.99, 4, 3, 15, 8, NOW(), NOW()),
('Chaise de bureau', 'Chaise ergonomique', '9012345678901', 'FurniPro', 149.99, 4, 5, 20, 12, NOW(), NOW()),
('Shampoing 500ml', 'Shampoing doux', '0123456789012', 'BeautyCorp', 12.99, 5, 40, 150, 120, NOW(), NOW());

-- Mouvements de stock d'exemple
INSERT INTO `stock_movements` (`product_id`, `type`, `quantity`, `reason`, `moved_at`, `user_id`, `created_at`, `updated_at`) VALUES
(1, 'in', 20, 'Commande fournisseur TechSupplier', '2025-01-01 10:00:00', 1, NOW(), NOW()),
(2, 'in', 50, 'Commande fournisseur GearTech', '2025-01-02 14:30:00', 1, NOW(), NOW()),
(3, 'in', 45, 'Commande fournisseur GearTech', '2025-01-03 09:15:00', 1, NOW(), NOW()),
(4, 'in', 100, 'Commande fournisseur CoffeeCo', '2025-01-04 11:20:00', 2, NOW(), NOW()),
(5, 'in', 80, 'Commande fournisseur TeaMaster', '2025-01-05 16:45:00', 2, NOW(), NOW()),
(1, 'out', 5, 'Vente client', '2025-01-06 13:00:00', 3, NOW(), NOW()),
(2, 'out', 15, 'Vente client', '2025-01-06 14:30:00', 3, NOW(), NOW()),
(3, 'out', 10, 'Vente client', '2025-01-07 10:15:00', 3, NOW(), NOW());

-- =============================================
-- Index de performance
-- =============================================

-- Index pour les recherches fréquentes
CREATE INDEX idx_products_name ON products(name);
CREATE INDEX idx_products_category_stock ON products(category_id, current_stock);
CREATE INDEX idx_movements_product_date ON stock_movements(product_id, moved_at);
CREATE INDEX idx_alerts_read_level ON alerts(is_read, level);
CREATE INDEX idx_alerts_product_type ON alerts(product_id, type);

-- =============================================
-- Vues utiles
-- =============================================

-- Vue des produits avec stock faible
CREATE VIEW v_low_stock_products AS
SELECT 
    p.*,
    c.name as category_name,
    (p.stock_min - p.current_stock) as shortage
FROM products p
LEFT JOIN categories c ON p.category_id = c.id
WHERE p.current_stock <= p.stock_min;

-- Vue des mouvements récents
CREATE VIEW v_recent_movements AS
SELECT 
    sm.*,
    p.name as product_name,
    u.name as user_name
FROM stock_movements sm
JOIN products p ON sm.product_id = p.id
LEFT JOIN users u ON sm.user_id = u.id
ORDER BY sm.moved_at DESC;

-- Vue des alertes non lues
CREATE VIEW v_unread_alerts AS
SELECT 
    a.*,
    p.name as product_name
FROM alerts a
LEFT JOIN products p ON a.product_id = p.id
WHERE a.is_read = 0;

-- =============================================
-- Procédures stockées utiles
-- =============================================

DELIMITER //

-- Procédure pour mettre à jour le stock d'un produit
CREATE PROCEDURE UpdateProductStock(
    IN p_product_id INT,
    IN p_new_stock INT
)
BEGIN
    UPDATE products 
    SET current_stock = p_new_stock,
        updated_at = NOW()
    WHERE id = p_product_id;
    
    -- Générer une alerte si le stock est faible
    IF p_new_stock <= (SELECT stock_min FROM products WHERE id = p_product_id) THEN
        INSERT INTO alerts (type, product_id, message, level, created_by, created_at)
        VALUES ('min_stock', p_product_id, 
                CONCAT('Stock faible pour le produit: ', (SELECT name FROM products WHERE id = p_product_id)),
                'warning', 1, NOW());
    END IF;
END //

-- Procédure pour générer les alertes automatiques
CREATE PROCEDURE GenerateAutoAlerts()
BEGIN
    -- Alertes de stock faible
    INSERT INTO alerts (type, product_id, message, level, created_by, created_at)
    SELECT 
        'min_stock',
        id,
        CONCAT('Stock faible: ', name, ' (', current_stock, '/', stock_min, ')'),
        CASE 
            WHEN current_stock = 0 THEN 'critical'
            WHEN current_stock <= stock_min/2 THEN 'critical'
            ELSE 'warning'
        END,
        1,
        NOW()
    FROM products 
    WHERE current_stock <= stock_min
    AND id NOT IN (
        SELECT product_id FROM alerts 
        WHERE type = 'min_stock' 
        AND created_at >= DATE_SUB(NOW(), INTERVAL 1 DAY)
    );
    
    -- Alertes de surstock
    INSERT INTO alerts (type, product_id, message, level, created_by, created_at)
    SELECT 
        'overstock',
        id,
        CONCAT('Surstock: ', name, ' (', current_stock, ' > ', stock_optimal * 1.5, ')'),
        'info',
        1,
        NOW()
    FROM products 
    WHERE current_stock > stock_optimal * 1.5
    AND id NOT IN (
        SELECT product_id FROM alerts 
        WHERE type = 'overstock' 
        AND created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)
    );
END //

DELIMITER ;

-- =============================================
-- Triggers pour la maintenance automatique
-- =============================================

DELIMITER //

-- Trigger pour générer une alerte lors d'un mouvement de sorti
CREATE TRIGGER after_stock_movement_out
AFTER INSERT ON stock_movements
FOR EACH ROW
BEGIN
    IF NEW.type = 'out' THEN
        UPDATE products 
        SET current_stock = current_stock - NEW.quantity
        WHERE id = NEW.product_id;
        
        -- Vérifier si le stock devient faible
        IF (SELECT current_stock FROM products WHERE id = NEW.product_id) <= 
           (SELECT stock_min FROM products WHERE id = NEW.product_id) THEN
            INSERT INTO alerts (type, product_id, message, level, created_by, created_at)
            VALUES ('min_stock', NEW.product_id,
                    CONCAT('Stock faible après mouvement: ', (SELECT name FROM products WHERE id = NEW.product_id)),
                    'warning', NEW.user_id, NOW());
        END IF;
    END IF;
END //

-- Trigger pour mettre à jour le stock lors d'un mouvement d'entrée
CREATE TRIGGER after_stock_movement_in
AFTER INSERT ON stock_movements
FOR EACH ROW
BEGIN
    IF NEW.type = 'in' THEN
        UPDATE products 
        SET current_stock = current_stock + NEW.quantity
        WHERE id = NEW.product_id;
    END IF;
END //

DELIMITER ;

-- =============================================
-- Fin de l'export SQL
-- =============================================

-- Statistiques de la base
SELECT 
    'users' as table_name, COUNT(*) as record_count FROM users
UNION ALL
SELECT 
    'categories' as table_name, COUNT(*) as record_count FROM categories
UNION ALL
SELECT 
    'products' as table_name, COUNT(*) as record_count FROM products
UNION ALL
SELECT 
    'stock_movements' as table_name, COUNT(*) as record_count FROM stock_movements
UNION ALL
SELECT 
    'alerts' as table_name, COUNT(*) as record_count FROM alerts;
