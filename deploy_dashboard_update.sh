#!/bin/bash

# Script de déploiement du dashboard moderne sur Render
echo "🚀 Déploiement du dashboard moderne..."

# Vérifier si nous sommes dans le bon répertoire
if [ ! -f "composer.json" ]; then
    echo "❌ Erreur: Veuillez exécuter ce script depuis la racine du projet Laravel"
    exit 1
fi

# Nettoyer le cache
echo "🧹 Nettoyage du cache..."
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

# Optimiser pour la production
echo "⚡ Optimisation pour la production..."
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Vérifier les dépendances
echo "📦 Vérification des dépendances..."
composer install --no-dev --optimize-autoloader --ignore-platform-reqs

# Créer un commit pour les changements
echo "📝 Création du commit pour le nouveau design..."
git add .
git commit -m "✨ Amélioration du dashboard avec design moderne

- Ajout d'animations fluides et d'effets visuels
- Design glassmorphism moderne avec effets de brillance
- Indicateurs avec gradients et animations au survol
- Graphiques avec design amélioré et effets visuels
- Tableau des mouvements avec design moderne
- Actions rapides avec boutons animés
- Fond décoratif avec éléments animés
- Support du mode sombre
- Animations respectueuses de l'accessibilité"

# Pousser vers le dépôt distant
echo "📤 Envoi vers le dépôt distant..."
git push origin main

echo "✅ Déploiement terminé !"
echo "🌐 Votre site sera mis à jour sur: https://gestion-stock-ol7b.onrender.com"
echo "⏱️ Le déploiement peut prendre quelques minutes..."
echo ""
echo "🎨 Nouveautés du design:"
echo "   • Header avec effet glassmorphism"
echo "   • Indicateurs avec animations et gradients"
echo "   • Graphiques avec effets visuels modernes"
echo "   • Tableau avec design amélioré"
echo "   • Actions rapides avec boutons animés"
echo "   • Fond décoratif avec éléments flottants"
echo "   • Animations fluides et transitions"
echo "   • Support responsive amélioré"
