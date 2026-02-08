# Déploiement sur Railway

## Étapes pour déployer votre application Laravel sur Railway

### 1. Prérequis
- Compte Railway (https://railway.app)
- Git installé
- Repository GitHub/GitLab/Bitbucket

### 2. Configuration des fichiers
Les fichiers suivants ont été créés pour Railway:
- `railway.json` - Configuration du build et déploiement
- `Procfile` - Commande de démarrage
- `.env.example` - Variables d'environnement pour production

### 3. Déploiement

#### Méthode 1: Via GitHub (recommandé)
1. Poussez votre code sur GitHub
2. Connectez-vous à Railway
3. Cliquez sur "New Project"
4. Choisissez "Deploy from GitHub repo"
5. Sélectionnez votre repository
6. Railway détectera automatiquement Laravel

#### Méthode 2: Via CLI Railway
1. Installez Railway CLI:
   ```bash
   npm install -g @railway/cli
   ```
2. Connectez-vous:
   ```bash
   railway login
   ```
3. Initialisez le projet:
   ```bash
   railway init
   ```
4. Déployez:
   ```bash
   railway up
   ```

### 4. Configuration de la base de données
1. Dans Railway, ajoutez un service MySQL
2. Railway générera automatiquement les variables d'environnement:
   - `RAILWAY_PRIVATE_MYSQL_HOST`
   - `RAILWAY_PRIVATE_MYSQL_PORT`
   - `RAILWAY_PRIVATE_MYSQL_DATABASE`
   - `RAILWAY_PRIVATE_MYSQL_USER`
   - `RAILWAY_PRIVATE_MYSQL_PASSWORD`

### 5. Variables d'environnement supplémentaires
Dans Railway, ajoutez ces variables:
- `APP_KEY`: Générez avec `php artisan key:generate --show`
- `APP_URL`: URL de votre application Railway
- `APP_ENV`: `production`
- `APP_DEBUG`: `false`

### 6. Déploiement automatique
- Chaque push sur votre branche principale déclenchera un déploiement
- Les migrations s'exécuteront automatiquement

### 7. Vérification
- Accédez à votre URL Railway
- Vérifiez que l'application fonctionne
- Testez les fonctionnalités principales

### 8. Dépannage
- Logs: Vérifiez les logs dans Railway
- Variables: Confirmez que toutes les variables d'environnement sont configurées
- Base de données: Assurez-vous que la connexion MySQL fonctionne

### 9. Domaine personnalisé (optionnel)
1. Dans Railway, allez dans Settings
2. Ajoutez votre domaine personnalisé
3. Configurez les DNS selon les instructions Railway

## Notes importantes
- L'application utilise le port `$PORT` fourni par Railway
- Le health check est configuré sur `/api/health`
- Les caches sont optimisés pour la production
