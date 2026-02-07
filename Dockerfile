# Dockerfile simple pour Laravel + MySQL
FROM php:8.1-apache

# Installer dépendances de base
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    libpng-dev \
    libjpeg-dev \
    && rm -rf /var/lib/apt/lists/*

# Installer extensions PHP pour MySQL
RUN docker-php-ext-install pdo \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mbstring \
    && docker-php-ext-install opcache \
    && docker-php-ext-install gd \
    && docker-php-ext-install bcmath

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurer Apache
RUN a2enmod rewrite

# Copier le code
COPY . /var/www/html

# Permissions
RUN chown -R www-data:www-data /var/www/html \
    && chmod -R 755 /var/www/html/storage \
    && chmod -R 755 /var/www/html/bootstrap/cache

# Installer dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Créer .env
RUN cp .env.example .env

# Optimiser Laravel
RUN php artisan config:cache && php artisan route:cache

EXPOSE 80
CMD ["apache2-foreground"]
