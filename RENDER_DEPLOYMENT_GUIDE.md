# 🚀 Guide de Déploiement sur Render.com

## 📋 État Actuel
- ✅ Application Laravel configurée pour MySQL
- ✅ Base de données testée localement
- ✅ Scripts de déploiement prêts
- ✅ Routes produits/categories/predictions fonctionnelles

## 🔧 Configuration Requise sur Render

### 1. Base de Données
- Ajoutez une base de données **MySQL** sur Render
- Notez les informations de connexion

### 2. Variables d'Environnement
Dans votre dashboard Render, ajoutez ces variables :
```
DB_CONNECTION=mysql
DB_HOST=votre-host-render.com
DB_PORT=3306
DB_DATABASE=votre-db-name
DB_USERNAME=votre-user
DB_PASSWORD=votre-password
APP_ENV=production
APP_DEBUG=false
APP_KEY=base64:QgpMsiEgxGyD2d4eB4wwXCpOmR8oo2LUF39yw05cjqY=
```

### 3. Fichiers Modifiés
- ✅ `Procfile` : Ajouté `release: php artisan migrate --force`
- ✅ `deploy.sh` : Script de déploiement automatique
- ✅ `.env` : Configuré pour MySQL

## 🚀 Déploiement

### Commandes Git
```bash
git add .
git commit -m "Fix erreur 500 - MySQL configuration for Render"
git push origin main
```

### Ce qui se passera sur Render
1. **Build** : Installation des dépendances
2. **Release** : Exécution automatique des migrations
3. **Deploy** : Démarrage de l'application

## 🧪 Vérification Post-Déploiement

Testez ces URLs :
- https://votre-app.onrender.com/products
- https://votre-app.onrender.com/categories  
- https://votre-app.onrender.com/predictions

## 🔧 Si Problème Persiste

1. Vérifiez les logs Render
2. Exécutez : `https://votre-app.onrender.com/render_debug.php`
3. Contactez le support Render

## ✅ Résultat Attendu
- Plus d'erreur 500
- Pages produits/categories/predictions fonctionnelles
- Base de données MySQL connectée
