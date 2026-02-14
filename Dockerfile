FROM composer:2.5 as build

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

FROM php:8.1-cli

WORKDIR /var/www/html

COPY --from=build /app /var/www/html

# Install SQLite extension
RUN docker-php-ext-install pdo pdo_sqlite

# Copy deployment script
COPY deploy.sh /tmp/deploy.sh
RUN chmod +x /tmp/deploy.sh

# Run deployment script
RUN /tmp/deploy.sh

# Variables d'environnement pour Render
ENV APP_NAME="Gestion_Stock"
ENV APP_ENV="production"
ENV APP_DEBUG="false"
ENV DB_CONNECTION="sqlite"
ENV DB_DATABASE="/var/www/html/database/database.sqlite"

EXPOSE 8000

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
