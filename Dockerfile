FROM composer:2.5 as build
WORKDIR /app
COPY . .
RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

FROM php:8.1-cli
WORKDIR /var/www/html
COPY --from=build /app /var/www/html

# INSTALLATION DES EXTENSIONS POUR POSTGRESQL
RUN apt-get update && apt-get install -y libpq-dev \
    && docker-php-ext-install pdo pdo_pgsql

# Nettoyage et permissions
RUN php artisan cache:clear && chmod -R 775 storage bootstrap/cache

# Variables d'environnement pour Render
ENV APP_ENV="production"
ENV APP_DEBUG="false"

EXPOSE 10000

# Migration automatique + Démarrage
CMD php artisan migrate --force && php artisan serve --host=0.0.0.0 --port=${PORT:-10000}