# 🚀 Configuration Laragon pour Gestion Stock

## 🔧 Problème Actuel
Laragon n'utilise pas la bonne configuration MySQL

## ✅ Solution Étape par Étape

### 1. Ouvrir Laragon
- Démarrez Laragon
- Allez dans **Menu** → **Preferences** → **Services & Ports**

### 2. Configurer MySQL
- Assurez-vous que **MySQL** est coché et démarré
- Port MySQL : **3306**
- User : **root**
- Password : **vide**

### 3. Configurer Apache/Nginx
- Cliquez sur votre projet "stock"
- **Right-click** → **Quick create** → **Laravel**
- Ou configurez manuellement :
  - Document Root : `c:\laragon\www\stock\public`
  - URL : `http://stock.test`

### 4. Vérifier la Base de Données
- Ouvrez **phpMyAdmin** (dans Laragon)
- Créez la base `stock` si elle n'existe pas
- Importez les tables si nécessaire

### 5. Redémarrer Tout
- Cliquez **Stop All**
- Puis **Start All**

### 6. Accéder à l'Application
- URL : `http://stock.test`
- Ou : `http://localhost/stock`

## 🎯 Si Ça Marche Toujours Pas

### Option A : Utiliser Laragon avec PHP CLI
```bash
cd c:\laragon\www\stock
c:\laragon\bin\php\php-8.1.10-Win32-vs16-x64\php.exe artisan serve --host=127.0.0.1 --port=8080
```

### Option B : Configurer Virtual Host
Dans `C:\laragon\etc\apache2\sites-enabled\00-stock.conf` :
```apache
<VirtualHost *:80>
    DocumentRoot "c:/laragon/www/stock/public"
    ServerName stock.test
    ServerAlias *.stock.test
</VirtualHost>
```

## 🔍 Vérification
- MySQL : ✅ Actif sur port 3306
- Apache : ✅ Actif sur port 80
- URL : ✅ http://stock.test accessible
