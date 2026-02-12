#!/bin/bash

# Script de déploiement pour Render.com
echo "🚀 Déploiement automatique pour Render.com"

# Variables d'environnement pour Render
export APP_ENV="production"
export APP_DEBUG="false"
export APP_KEY="base64:QgpMsiEgxGyD2d4eB4wwXCpOmR8oo2LUF39yw05cjqY="

# Configuration de la base de données MySQL sur Render
if [ -n "$DATABASE_URL" ]; then
    echo "🌍 Configuration avec DATABASE_URL de Render"
    export DB_CONNECTION="mysql"
    
    # Parser DATABASE_URL (format: mysql://user:pass@host:port/dbname)
    DB_HOST=$(echo $DATABASE_URL | sed -e 's/^mysql:\/\///' -e 's/@.*$//' -e 's/:.*$//')
    DB_PORT=$(echo $DATABASE_URL | sed -e 's/^mysql:\/\/.*://' -e 's/@.*$//')
    DB_NAME=$(echo $DATABASE_URL | sed -e 's/^mysql:\/\/.*@.*:\///')
    DB_USER=$(echo $DATABASE_URL | sed -e 's/^mysql:\/\///' -e 's/:[^@]*@.*$//' -e 's/:.*$//')
    DB_PASSWORD=$(echo $DATABASE_URL | sed -e 's/^mysql:\/\/[^:]*://' -e 's/@.*$//')
    
    echo "Host: $DB_HOST, Port: $DB_PORT, DB: $DB_NAME, User: $DB_USER"
else
    echo "⚠️ DATABASE_URL non trouvé, utilisation MySQL par défaut"
    export DB_CONNECTION="mysql"
    export DB_HOST="127.0.0.1"
    export DB_PORT="3306"
    export DB_DATABASE="stock"
    export DB_USERNAME="root"
    export DB_PASSWORD=""
fi

# Création des répertoires nécessaires
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
mkdir -p bootstrap/cache

# Permissions
chmod -R 755 storage
chmod -R 755 bootstrap/cache

# Installation des dépendances
composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Création du fichier .env avec les bonnes variables
cat > .env << EOF
APP_NAME="Gestion_Stock"
APP_ENV="production"
APP_DEBUG="false"
APP_KEY="$APP_KEY"
DB_CONNECTION="$DB_CONNECTION"
DB_HOST="$DB_HOST"
DB_PORT="$DB_PORT"
DB_DATABASE="$DB_NAME"
DB_USERNAME="$DB_USER"
DB_PASSWORD="$DB_PASSWORD"
CACHE_DRIVER="file"
SESSION_DRIVER="file"
QUEUE_CONNECTION="sync"
EOF

echo "✅ Fichier .env configuré"

# Nettoyage du cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Exécution des migrations
php artisan migrate --force

# Création de données de test si nécessaire
php artisan tinker --execute="
if (App\Models\Category::count() == 0) {
    App\Models\Category::create(['name' => 'Électronique', 'description' => 'Produits électroniques']);
    App\Models\Category::create(['name' => 'Alimentation', 'description' => 'Produits alimentaires']);
    App\Models\Category::create(['name' => 'Vêtements', 'description' => 'Vêtements et accessoires']);
}
if (App\Models\Product::count() == 0) {
    App\Models\Product::create(['name' => 'Laptop Dell', 'description' => 'Ordinateur portable Dell', 'current_stock' => 15, 'stock_min' => 5, 'stock_optimal' => 20, 'category_id' => 1]);
    App\Models\Product::create(['name' => 'Pain frais', 'description' => 'Pain de qualité', 'current_stock' => 50, 'stock_min' => 10, 'stock_optimal' => 100, 'category_id' => 2]);
    App\Models\Product::create(['name' => 'T-shirt', 'description' => 'T-shirt en coton', 'current_stock' => 30, 'stock_min' => 8, 'stock_optimal' => 50, 'category_id' => 3]);
}
echo '✅ Données de test créées';
"

# Optimisation
php artisan config:cache
php artisan route:cache
php artisan view:cache

echo "✅ Déploiement terminé!"
