FROM composer:2.5 as build

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

FROM php:8.1-cli

WORKDIR /var/www/html

COPY --from=build /app /var/www/html

RUN cp .env.example .env && php artisan key:generate

# Variables d'environnement pour Render
ENV APP_NAME="Gestion_Stock"
ENV APP_ENV="production"
ENV APP_DEBUG="false"
ENV DB_CONNECTION="sqlite"
ENV DB_DATABASE="/tmp/app.sqlite"

# Créer la base de données SQLite et les tables automatiquement
RUN mkdir -p /tmp && \
    touch /tmp/app.sqlite && \
    chmod 666 /tmp/app.sqlite && \
    php artisan migrate --force || echo "Migrations may have failed, continuing..."

EXPOSE 8000

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
