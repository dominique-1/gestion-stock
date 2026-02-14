@echo off
echo ========================================
echo   Suppression de la Recherche Produits
echo ========================================
echo.

REM Vérifier si nous sommes dans le bon répertoire
if not exist "composer.json" (
    echo ❌ Erreur: Veuillez exécuter ce script depuis la racine du projet Laravel
    pause
    exit /b 1
)

REM Nettoyer le cache
echo 🧹 Nettoyage du cache...
php artisan cache:clear
php artisan config:clear
php artisan view:clear
php artisan route:clear

REM Optimiser pour la production
echo ⚡ Optimisation pour la production...
php artisan config:cache
php artisan route:cache
php artisan view:cache

REM Vérifier les dépendances
echo 📦 Vérification des dépendances...
composer install --no-dev --optimize-autoloader --ignore-platform-reqs

REM Créer un commit pour les changements
echo 📝 Création du commit pour suppression de la recherche...
git add .
git commit -m "🗑️ Suppression de la recherche dans la page produits

Changements appliqués:
- Suppression du champ de recherche dans les filtres
- Maintien des filtres par catégorie et statut
- Mise à jour de la description du header
- Grid passée de 4 colonnes à 3 colonnes
- Interface simplifiée et épurée
- Focus sur les filtres essentiels uniquement

Filtres conservés:
- Filtre par catégorie
- Filtre par statut de stock
- Bouton de filtrage

Améliorations:
- Interface moins encombrée
- Navigation plus claire
- Focus sur l'essentiel
- Maintien de la fonctionnalité complète"

REM Pousser vers le dépôt distant
echo 📤 Envoi vers le dépôt distant...
git push origin main

echo.
echo ✅ Déploiement terminé !
echo 🌐 Votre site sera mis à jour sur: https://gestion-stock-ol7b.onrender.com
echo ⏱️ Le déploiement peut prendre quelques minutes...
echo.
echo 🗑️ Modifications apportées:
echo    • Champ de recherche supprimé
echo    • Filtres essentiels conservés
echo    • Interface simplifiée
echo    • Grid optimisée (3 colonnes)
echo    • Description mise à jour
echo.
echo 🎯 Filtres disponibles:
echo    • Par catégorie
echo    • Par statut de stock
echo    • Bouton de filtrage
echo.
pause
