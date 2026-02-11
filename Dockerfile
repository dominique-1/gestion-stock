FROM composer:2.5 as build

WORKDIR /app

COPY . .

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

FROM php:8.1-fpm

COPY --from=build /app /var/www/html

WORKDIR /var/www/html

RUN cp .env.example .env

RUN php artisan key:generate

RUN chown -R www-data:www-data /var/www/html

EXPOSE 9000

CMD ["php-fpm"]
