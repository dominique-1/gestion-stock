# Instructions pour rétablir MySQL avec votre application Laravel

## Problème actuel
Les extensions PDO (pdo_sqlite et pdo_mysql) ne sont pas activées dans votre configuration PHP.

## Solution étape par étape

### 1. Activer les extensions PHP dans Laragon
1. Ouvrir Laragon
2. Cliquer sur Menu > PHP > php.ini
3. Chercher les lignes suivantes et décommenter-les (enlever le point-virgule au début) :
   ```ini
   extension=pdo
   extension=pdo_sqlite
   extension=pdo_mysql
   extension=sqlite3
   ```

### 2. Redémarrer les services
- Dans Laragon, cliquer sur "Stop All" puis "Start All"

### 3. Configurer la base de données MySQL
Exécuter le script suivant pour configurer MySQL :
```bash
php fix_mysql_config.php
```

### 4. Créer la base de données si nécessaire
```sql
CREATE DATABASE IF NOT EXISTS stock CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
```

### 5. Exécuter les migrations
```bash
php artisan migrate:fresh --seed
```

## Alternative : Utiliser SQLite (temporaire)
Si vous voulez utiliser l'application immédiatement avec SQLite :
```bash
php fix_sqlite_config.php
php artisan migrate:fresh --seed
```

## Vérification
Pour vérifier que les extensions sont bien chargées :
```bash
php diagnostic_php.php
```

Les extensions suivantes doivent apparaître avec ✓ :
- ✓ PDO
- ✓ PDO_SQLITE  
- ✓ PDO_MYSQL
