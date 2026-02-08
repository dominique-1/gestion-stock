# Déploiement sur Railway - Guide Complet

## 🚀 Étapes pour déployer gratuitement sur Railway

### 1. Prérequis
- Compte GitHub
- Compte Railway (gratuit)
- Code de l'application sur GitHub

### 2. Configuration nécessaire

#### a) Ajouter les fichiers créés :
- `railway.toml` (configuration du déploiement)
- `.env.railway` (variables d'environnement)
- `public/api/health.php` (health check)

#### b) Mettre à jour `.gitignore` :
```
.env
.env.local
.env.railway
node_modules/
storage/logs/*
storage/framework/cache/*
storage/framework/sessions/*
storage/framework/views/*
```

### 3. Étapes de déploiement

#### Étape 1: Push sur GitHub
```bash
git add .
git commit -m "Ready for Railway deployment"
git push origin main
```

#### Étape 2: Créer le projet Railway
1. Allez sur [railway.app](https://railway.app)
2. Connectez-vous avec GitHub
3. Cliquez "New Project" → "Deploy from GitHub repo"
4. Sélectionnez votre repository

#### Étape 3: Configuration
1. Railway détectera automatiquement votre projet Laravel
2. Ajoutez les variables d'environnement depuis `.env.railway`
3. Ajoutez les services MySQL et Redis
4. Générez un `APP_KEY` : `php artisan key:generate --show`

#### Étape 4: Déploiement
1. Cliquez "Deploy"
2. Attendez le build (2-5 minutes)
3. Votre application sera disponible sur une URL Railway

### 4. Configuration après déploiement

#### a) Base de données
```bash
# Exécuter les migrations
php artisan migrate --force

# Seeder les données (optionnel)
php artisan db:seed --force
```

#### b) Storage
```bash
# Créer le lien symbolique pour les fichiers publics
php artisan storage:link
```

#### c) Cache
```bash
# Optimiser le cache
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### 5. Limites du plan gratuit Railway

- ✅ **500 heures** de runtime par mois
- ✅ **1 service** principal
- ✅ **1 base de données** MySQL (512MB)
- ✅ **1 Redis** (256MB)
- ✅ **100GB** de bande passante
- ❌ **Downtime** après les 500h (jusqu'au mois suivant)

### 6. Optimisations recommandées

#### a) Performance
- Activer le cache Redis
- Optimiser les requêtes SQL
- Utiliser CDN pour les assets

#### b) Coûts
- Surveiller l'utilisation des heures
- Optimiser les tâches en arrière-plan
- Utiliser des queues pour les traitements lourds

### 7. Domaine personnalisé (optionnel)

1. Dans Railway → Settings → Domains
2. Ajoutez votre domaine
3. Configurez les DNS vers Railway

### 8. Monitoring

- Railway fournit des logs en temps réel
- Surveillez l'utilisation des ressources
- Configurez les alertes par email

### 9. Backup

- Exportez régulièrement votre base de données
- Sauvegardez vos fichiers uploadés
- Versionnez votre code sur GitHub

## 🎯 Résultat final

Votre application sera disponible sur :
`https://your-app-name.up.railway.app`

Avec ce setup, vous avez une application professionnelle hébergée gratuitement !
