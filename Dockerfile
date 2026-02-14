# --- Étape 1 : Build des dépendances PHP ---
FROM composer:2.5 as build
WORKDIR /app
COPY . .
# On utilise --no-scripts pour éviter que Laravel cherche la DB pendant l'installation
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts

# --- Étape 2 : Image finale ---
FROM php:8.1-cli

WORKDIR /var/www/html

# On récupère le code et les dossiers vendor du build
COPY --from=build /app /var/www/html

# INSTALLATION DES EXTENSIONS POUR POSTGRESQL (Nécessaire pour Render)
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Nettoyage manuel du cache Laravel pour éviter les erreurs de configuration
RUN php artisan cache:clear || true
RUN php artisan config:clear || true

# Configuration des permissions pour Render
RUN chmod -R 775 storage bootstrap/cache
RUN chown -R www-data:www-data /var/www/html

# Variables d'environnement pour la production
ENV APP_ENV="production"
ENV APP_DEBUG="false"

# Render utilise par défaut le port 10000
EXPOSE 10000

# COMMANDE DE DÉMARRAGE :
# 1. On lance la découverte des packages (qu'on a sauté avant)
# 2. On force la migration des tables PostgreSQL
# 3. On démarre le serveur
CMD php artisan package:discover --ansi && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-10000}