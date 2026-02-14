@echo off
echo ========================================
echo   Deploiement Nouvelle Page d'Accueil
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
echo 📝 Création du commit pour la nouvelle page d'accueil...
git add .
git commit -m "🎨 Nouvelle page d'accueil moderne et responsive

- Remplacement de l'ancienne page welcome par une page home moderne
- Design glassmorphism avec animations fluides
- Navigation responsive avec menu hamburger pour mobile
- Support complet mobile-first avec breakpoints optimisés
- Animations de scroll et effets visuels modernes
- Section hero avec statistiques en direct
- Présentation détaillée des fonctionnalités
- Boutons d'action avec effets de survol
- Support des safe areas pour iPhone X+
- Optimisation du touch pour mobile
- Prevention du zoom sur double tap
- Gestion du viewport height pour mobile browsers

Améliorations responsive:
- Menu hamburger pour mobile/tablette
- Navigation adaptative selon la taille d'écran
- Textes et espacements optimisés pour mobile
- Boutons et éléments tactiles améliorés
- Support des orientations portrait/landscape
- Animations désactivées pour les appareils tactiles"

REM Pousser vers le dépôt distant
echo 📤 Envoi vers le dépôt distant...
git push origin main

echo.
echo ✅ Déploiement terminé !
echo 🌐 Votre site sera mis à jour sur: https://gestion-stock-ol7b.onrender.com
echo ⏱️ Le déploiement peut prendre quelques minutes...
echo.
echo 🎨 Nouvelles fonctionnalités de la page d'accueil:
echo    • Design moderne avec glassmorphism
echo    • Navigation responsive avec menu hamburger
echo    • Support complet mobile-first
echo    • Animations fluides et effets visuels
echo    • Section hero avec statistiques
echo    • Présentation des fonctionnalités
echo    • Boutons d'action attractifs
echo    • Optimisation pour tous les appareils
echo.
echo 📱 Responsive Features:
echo    • Menu hamburger pour mobile
echo    • Navigation adaptative
echo    • Textes optimisés pour mobile
echo    • Boutons tactiles améliorés
echo    • Support des orientations
echo    • Safe areas pour iPhone
echo.
pause
