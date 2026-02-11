FROM composer:2.5 as build

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

FROM php:8.1-cli

WORKDIR /var/www/html

COPY --from=build /app /var/www/html

RUN cp .env.example .env && php artisan key:generate

EXPOSE 8000

CMD ["sh", "-c", "php artisan serve --host=0.0.0.0 --port=${PORT:-8000}"]
