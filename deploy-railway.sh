#!/bin/bash

# Script de déploiement pour Railway
echo "Début du déploiement sur Railway..."

# Installation des dépendances
composer install --no-dev --optimize-autoloader

# Optimisation de l'application
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migration de la base de données
php artisan migrate --force

# Nettoyage du cache
php artisan cache:clear

echo "Déploiement terminé!"
