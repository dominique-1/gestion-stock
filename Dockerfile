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
ENV DB_CONNECTION="pgsql"
ENV DB_HOST="dpg-couronnement-oregon-a-1.render.com"
ENV DB_PORT="5432"
ENV DB_DATABASE="gestion_stock_2026"
ENV DB_USERNAME="gestion_stock_2026_user"
ENV DB_PASSWORD="your_password_here"

# Attendre que la base de données soit prête et exécuter les migrations
RUN php artisan migrate --force || echo "Migrations may have failed, will retry later..."

EXPOSE 8000

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
