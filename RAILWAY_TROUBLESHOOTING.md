# 🚨 DÉPANNAGE RAILWAY

## Problèmes courants et solutions

### ❌ Erreur 404 sur `/movements`
**Cause** : Routes protégées par middleware `session`

**Solution** ✅ :
```php
// Dans routes/web.php - déplacer les routes hors du middleware
Route::get('/movements', [StockMovementController::class, 'index'])->name('movements.index');
Route::get('/movements/{movement}', [StockMovementController::class, 'show'])->name('movements.show');
```

### ❌ Erreur de base de données PostgreSQL
**Cause** : Variables d'environnement manquantes

**Solution** ✅ :
Dans Railway, ajoutez ces variables :
```
DB_CONNECTION=pgsql
DB_HOST=${{RAILWAY_PRIVATE_DOMAIN}}
DB_PORT=5432
DB_DATABASE=${{POSTGRES_DATABASE}}
DB_USERNAME=${{POSTGRES_USER}}
DB_PASSWORD=${{POSTGRES_PASSWORD}}
```

### ❌ Erreur 500 Internal Server Error
**Causes possibles** :
1. APP_KEY manquante
2. Permissions incorrectes
3. Cache corrompu

**Solutions** ✅ :
1. Générer APP_KEY :
   ```bash
   php artisan key:generate --show
   ```
   Copiez dans variables Railway

2. Vérifier les logs Railway

3. Vider les caches :
   ```bash
   php artisan cache:clear
   php artisan config:clear
   php artisan route:clear
   php artisan view:clear
   ```

### ❌ Storage non fonctionnel
**Solution** ✅ :
```bash
php artisan storage:link
```

### ❌ Redis non disponible
**Solution** ✅ :
Ajoutez le service Redis dans Railway et configurez :
```
CACHE_DRIVER=redis
SESSION_DRIVER=redis
REDIS_HOST=${{REDISHOST}}
REDIS_PASSWORD=${{REDISPASSWORD}}
REDIS_PORT=6379
```

## 🚀 Test rapide local

Avant de déployer sur Railway, testez localement :

```bash
# 1. Nettoyer les caches
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# 2. Optimiser
php artisan config:cache
php artisan route:cache
php artisan view:cache

# 3. Tester les routes
php artisan route:list | findstr movements
```

## 📋 Checklist déploiement Railway

- [ ] Repository GitHub à jour
- [ ] Fichier `railway.toml` configuré
- [ ] Services PostgreSQL + Redis ajoutés
- [ ] Variables d'environnement configurées
- [ ] APP_KEY générée et ajoutée
- [ ] Routes publiques accessibles sans auth
- [ ] Cache optimisé pour production

## 🔍 Debug Railway

1. **Logs en temps réel** :
   ```bash
   railway logs
   ```

2. **Console Railway** :
   - Vérifiez l'onglet "Logs"
   - Regardez "Build logs"
   - Consultez "Runtime logs"

3. **Variables d'environnement** :
   - Settings → Variables
   - Vérifiez que toutes les variables sont présentes

## 🎯 Résultat attendu

Après correction :
- ✅ `/movements` accessible sans login
- ✅ Base de données PostgreSQL connectée
- ✅ Cache Redis fonctionnel
- ✅ Application en production

## 🆘 Si ça ne marche toujours pas

1. Vérifiez les logs Railway
2. Testez avec une configuration minimale
3. Contactez le support Railway

**Documentation complète : RAILWAY_DEPLOYMENT.md**
