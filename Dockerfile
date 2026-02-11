FROM php:8.1-cli

WORKDIR /var/www/html

COPY . .

RUN curl -sS https://getcomposer.org/installer | php && mv composer.phar /usr/local/bin/composer

RUN composer install --no-dev --optimize-autoloader --ignore-platform-reqs

RUN cp .env.example .env && php artisan key:generate

EXPOSE 8000

CMD ["php", "artisan", "serve", "--host=0.0.0.0", "--port=8000"]
