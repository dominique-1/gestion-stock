# Dockerfile ultra-minimaliste pour Laravel
FROM php:8.1-apache

# Installer seulement les dépendances essentielles
RUN apt-get update && apt-get install -y \
    git \
    curl \
    unzip \
    zip \
    && rm -rf /var/lib/apt/lists/*

# Installer seulement les extensions de base
RUN docker-php-ext-install pdo \
    && docker-php-ext-install pdo_mysql \
    && docker-php-ext-install mbstring

# Installer Composer
COPY --from=composer:latest /usr/bin/composer /usr/bin/composer

# Configurer Apache
RUN a2enmod rewrite

# Copier le code
COPY . /var/www/html

# Permissions
RUN chown -R www-data:www-data /var/www/html

# Installer dépendances PHP
RUN composer install --no-dev --optimize-autoloader --no-interaction

# Créer .env
RUN cp .env.example .env

# Optimiser Laravel
RUN php artisan config:cache

EXPOSE 80
CMD ["apache2-foreground"]
