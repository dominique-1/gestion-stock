# --- Étape 1 : Build ---
FROM composer:2.5 as build
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs --no-scripts

# --- Étape 2 : Image finale ---
FROM php:8.1-cli

WORKDIR /var/www/html

# On installe les dépendances système avant de copier le code
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql \
    && apt-get clean && rm -rf /var/lib/apt/lists/*

# On récupère le code
COPY --from=build /app /var/www/html

# --- CRUCIAL : On ne lance PAS artisan config/cache ici ---
# Cela évite de lire database.php pendant le build

# Configuration des permissions
RUN chmod -R 775 storage bootstrap/cache \
    && chown -R www-data:www-data /var/www/html

ENV APP_ENV="production"
ENV APP_DEBUG="false"

EXPOSE 10000

# On déplace toute la logique de config au démarrage réel (CMD)
CMD php artisan config:clear && \
    php artisan package:discover --ansi && \
    php artisan migrate --force && \
    php artisan serve --host=0.0.0.0 --port=${PORT:-10000}