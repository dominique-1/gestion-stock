@echo off
echo ========================================
echo   Deploiement Page d'Accueil Minimaliste
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
echo 📝 Création du commit pour le design minimaliste...
git add .
git commit -m "🎨 Design minimaliste pour la page d'accueil

Nouveau concept StockFlow - Design épuré et moderne:

- Changement de nom: StockApp → StockFlow
- Design minimaliste avec fond blanc épuré
- Navigation simplifiée et élégante
- Hero section avec typographie audacieuse
- Fond géométrique subtil et moderne
- Icônes noires sur fond blanc (high contrast)
- Animations subtiles et élégantes
- Effet de parallaxe sur le hero
- Section fonctionnalités épurée
- Footer minimaliste et professionnel
- Typographie light pour un look moderne
- Boutons avec effets de survol sophistiqués
- Palette monochrome noir et blanc
- Focus sur l'essentiel et la lisibilité
- Responsive design optimisé pour tous appareils
- Animations au scroll fluides
- Accessibilité améliorée avec focus states

Changement radical de direction:
- Moins d'éléments visuels, plus d'impact
- Typographie comme élément principal
- Espaces respirants et aérés
- Interface épurée et professionnelle
- Expérience utilisateur simplifiée"

REM Pousser vers le dépôt distant
echo 📤 Envoi vers le dépôt distant...
git push origin main

echo.
echo ✅ Déploiement terminé !
echo 🌐 Votre site sera mis à jour sur: https://gestion-stock-ol7b.onrender.com
echo ⏱️ Le déploiement peut prendre quelques minutes...
echo.
echo 🎨 Nouveau design minimaliste StockFlow:
echo    • Design épuré et moderne
echo    • Navigation simplifiée
echo    • Typographie audacieuse
echo    • Fond géométrique subtil
echo    • Icônes monochromes
echo    • Animations élégantes
echo    • Interface professionnelle
echo    • Focus sur la lisibilité
echo.
echo 🎯 Concept StockFlow:
echo    • Moins de complexité
echo    • Plus d'efficacité
echo    • Épuré et intuitif
echo    • High contrast accessibility
echo    • Mobile-first design
echo.
pause
