# 🚀 Déploiement sur Railway

## Étapes pour déployer l'application de gestion de stock sur Railway

### 1. Prérequis
- Compte Railway (https://railway.app/)
- Repository Git (GitHub, GitLab, etc.)
- Application Laravel prête

### 2. Configuration de l'environnement

#### Variables d'environnement Railway
Dans votre projet Railway, configurez ces variables :

```bash
# Configuration de base
APP_NAME="Gestion Stock"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://votre-app.railway.app

# Base de données (Railway PostgreSQL)
DB_CONNECTION=pgsql
DB_HOST=${{RAILWAY_PRIVATE_DOMAIN}}
DB_PORT=5432
DB_DATABASE=${{POSTGRES_DATABASE}}
DB_USERNAME=${{POSTGRES_USER}}
DB_PASSWORD=${{POSTGRES_PASSWORD}}

# Cache et Session
CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=${{REDISHOST}}
REDIS_PASSWORD=${{REDISPASSWORD}}
REDIS_PORT=6379

# Clé d'application
APP_KEY=base64:votre_clé_générée_ici
```

### 3. Fichiers de configuration

#### railway.toml
Le fichier `railway.toml` est déjà configuré avec :
- Builder Nixpacks
- Installation des dépendances
- Cache des configurations
- Migration automatique
- Lien du storage

#### .gitignore
Assurez-vous que `.env` est bien dans `.gitignore`

### 4. Déploiement

#### Méthode 1: Via GitHub (recommandé)
1. Poussez votre code sur GitHub
2. Connectez Railway à votre repository
3. Railway déploiera automatiquement

#### Méthode 2: Via CLI Railway
```bash
# Installer Railway CLI
npm install -g @railway/cli

# Se connecter
railway login

# Initialiser le projet
railway init

# Ajouter les variables d'environnement
railway variables set APP_NAME="Gestion Stock"
railway variables set APP_ENV=production
# ... etc

# Déployer
railway up
```

### 5. Services à ajouter

Dans Railway, ajoutez ces services :
- **PostgreSQL** : Base de données principale
- **Redis** : Cache et sessions

### 6. Post-déploiement

#### Génération de la clé APP_KEY
```bash
# Dans Railway console ou localement
php artisan key:generate --show
```
Copiez cette clé dans les variables Railway.

#### Vérification
1. Vérifiez que l'application est accessible
2. Testez les fonctionnalités principales
3. Vérifiez les logs Railway en cas d'erreur

### 7. Dépannage

#### Erreurs communes
- **500 Internal Server Error** : Vérifiez les logs et les variables d'environnement
- **Database connection failed** : Vérifiez les identifiants PostgreSQL
- **Storage not working** : Exécutez `php artisan storage:link`

#### Logs Railway
Utilisez les logs Railway pour diagnostiquer :
```bash
railway logs
```

### 8. Domaine personnalisé (optionnel)

1. Dans Railway, allez dans Settings → Domains
2. Ajoutez votre domaine personnalisé
3. Configurez les DNS selon les instructions Railway

### 9. Backup et maintenance

- Les backups PostgreSQL sont automatiques sur Railway
- Surveillez l'utilisation des ressources dans le dashboard Railway
- Mettez à jour régulièrement les dépendances

## 🎯 Résultat final

Votre application sera accessible via :
- URL Railway : `https://votre-projet.railway.app`
- Domaine personnalisé (si configuré)

## 📞 Support

- Documentation Railway : https://docs.railway.app/
- Support Laravel : https://laravel.com/docs
