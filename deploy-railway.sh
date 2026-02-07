#!/bin/bash

echo "🚀 Préparation du déploiement sur Railway..."

# 1. Nettoyer les caches
echo "🧹 Nettoyage des caches..."
php artisan config:clear
php artisan route:clear
php artisan view:clear
php artisan cache:clear

# 2. Optimiser pour la production
echo "⚡ Optimisation pour la production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Générer la clé d'application (si nécessaire)
echo "🔑 Vérification de la clé d'application..."
if [ -z "$APP_KEY" ]; then
    echo "Génération d'une nouvelle clé APP_KEY..."
    php artisan key:generate --force
fi

# 4. Vérifier les fichiers de déploiement
echo "📁 Vérification des fichiers de déploiement..."
if [ ! -f "railway.toml" ]; then
    echo "❌ railway.toml manquant!"
    exit 1
fi

if [ ! -f ".env.example" ]; then
    echo "❌ .env.example manquant!"
    exit 1
fi

# 5. Instructions
echo ""
echo "✅ Préparation terminée!"
echo ""
echo "📋 Prochaines étapes :"
echo "1. Poussez votre code sur GitHub"
echo "2. Connectez Railway à votre repository"
echo "3. Configurez les variables d'environnement Railway:"
echo "   - APP_NAME=Gestion Stock"
echo "   - APP_ENV=production"
echo "   - APP_DEBUG=false"
echo "   - APP_URL=https://votre-app.railway.app"
echo "   - DB_CONNECTION=pgsql"
echo "   - DB_HOST=\${{RAILWAY_PRIVATE_DOMAIN}}"
echo "   - DB_PORT=5432"
echo "   - DB_DATABASE=\${{POSTGRES_DATABASE}}"
echo "   - DB_USERNAME=\${{POSTGRES_USER}}"
echo "   - DB_PASSWORD=\${{POSTGRES_PASSWORD}}"
echo "   - CACHE_DRIVER=redis"
echo "   - SESSION_DRIVER=redis"
echo "   - REDIS_HOST=\${{REDISHOST}}"
echo "   - REDIS_PASSWORD=\${{REDISPASSWORD}}"
echo "   - REDIS_PORT=6379"
echo ""
echo "4. Ajoutez les services PostgreSQL et Redis dans Railway"
echo "5. Déployez!"
echo ""
echo "📚 Documentation complète : RAILWAY_DEPLOYMENT.md"
