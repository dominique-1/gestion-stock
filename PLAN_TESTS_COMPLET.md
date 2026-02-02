# Plan de Tests Complet - Système de Gestion de Stock

## 📋 Vue d'ensemble

Ce plan de tests est conçu pour valider que le système peut gérer **5 000 mouvements par minute** avec **10 utilisateurs simultanés** tout en maintenant la performance et la fiabilité.

---

## 🧪 Tests Unitaires

### 1. API Produits
```php
// Tests à implémenter dans tests/Unit/ProductTest.php
- test_create_product()
- test_update_product()
- test_delete_product()
- test_product_stock_calculation()
- test_product_validation_rules()
- test_product_search_by_name()
- test_product_filter_by_category()
- test_product_low_stock_alert()
```

### 2. API Mouvements
```php
// Tests à implémenter dans tests/Unit/StockMovementTest.php
- test_create_stock_movement()
- test_movement_validation()
- test_stock_update_on_movement()
- test_movement_types()
- test_movement_history()
- test_bulk_movement_creation()
- test_movement_performance_5000_per_minute()
```

### 3. Algorithme de Prédiction
```php
// Tests à implémenter dans tests/Unit/PredictionTest.php
- test_linear_regression_prediction()
- test_moving_average_prediction()
- test_auto_algorithm_selection()
- test_prediction_accuracy()
- test_stock_risk_calculation()
- test_recommendation_generation()
```

---

## 🔄 Tests Fonctionnels

### 1. Ajout Produit
**Scénario de test :**
1. Accéder à la page `/products/create`
2. Remplir tous les champs requis
3. Soumettre le formulaire
4. Vérifier que le produit est créé
5. Vérifier que le stock est initialisé correctement

**Critères de succès :**
- ✅ Produit créé en < 2 secondes
- ✅ Stock initial correct
- ✅ Alertes générées si nécessaire
- ✅ Interface responsive

### 2. Passage d'Inventaire
**Scénario de test :**
1. Créer un nouvel inventaire
2. Ajouter des lignes d'inventaire
3. Compter les produits physiquement
4. Comparer avec stock théorique
5. Valider les écarts
6. Clôturer l'inventaire

**Critères de succès :**
- ✅ Inventaire créé et modifiable
- ✅ Calcul des écarts correct
- ✅ Mise à jour automatique du stock
- ✅ Historique conservé

### 3. Alertes Automatiques
**Scénario de test :**
1. Créer un produit avec stock faible
2. Effectuer un mouvement de sortie
3. Vérifier génération d'alerte
4. Tester envoi d'email
5. Vérifier marquage comme lu

**Critères de succès :**
- ✅ Alertes générées en temps réel (< 1s)
- ✅ Emails envoyés correctement
- ✅ Statuts gérés correctement
- ✅ Interface de gestion fonctionnelle

---

## ⚡ Tests de Charge

### 1. Configuration de Test
```bash
# Installation des outils de test
composer require --dev laravel/telescope
composer require --dev beyondcode/laravel-websockets
npm install -g artillery
```

### 2. Test 10 Utilisateurs Simultanés
**Script Artillery :**
```yaml
# artillery-config.yml
config:
  target: 'http://127.0.0.1:8000'
  phases:
    - duration: 300
      arrivalRate: 10
scenarios:
  - name: "Utilisateur typique"
    weight: 70
    flow:
      - get:
          url: "/dashboard"
      - think: 2
      - get:
          url: "/products"
      - think: 3
      - post:
          url: "/movements"
          json:
            product_id: 1
            type: "out"
            quantity: 1
            reason: "Test charge"
```

### 3. Test 5 000 Mouvements/Minute
**Script de test de charge :**
```php
// tests/Load/StockMovementLoadTest.php
class StockMovementLoadTest extends TestCase
{
    public function test_5000_movements_per_minute()
    {
        $start = microtime(true);
        $movements = [];
        
        // Préparer 5000 mouvements
        for ($i = 0; $i < 5000; $i++) {
            $movements[] = [
                'product_id' => rand(1, 100),
                'type' => rand(0, 1) ? 'in' : 'out',
                'quantity' => rand(1, 10),
                'reason' => 'Test charge #' . $i,
                'moved_at' => now()->toDateTimeString(),
                'user_id' => 1
            ];
        }
        
        // Insérer en masse
        DB::table('stock_movements')->insert($movements);
        
        $duration = microtime(true) - $start;
        $movementsPerMinute = (5000 / $duration) * 60;
        
        $this->assertGreaterThan(5000, $movementsPerMinute);
        $this->assertLessThan(60, $duration);
    }
}
```

---

## 📊 Tests de Performance

### 1. Optimisations Base de Données
```sql
-- Index pour performances
CREATE INDEX idx_movements_product_date ON stock_movements(product_id, moved_at);
CREATE INDEX idx_products_category_stock ON products(category_id, current_stock);
CREATE INDEX idx_alerts_read_level ON alerts(is_read, level);

-- Partitionnement pour gros volumes
ALTER TABLE stock_movements PARTITION BY RANGE (YEAR(moved_at)) (
    PARTITION p2024 VALUES LESS THAN (2025),
    PARTITION p2025 VALUES LESS THAN (2026),
    PARTITION p2026 VALUES LESS THAN (2027)
);
```

### 2. Cache Redis
```php
// Configurer Redis pour les requêtes fréquentes
'movements_cache' => [
    'driver' => 'redis',
    'connection' => 'cache',
    'prefix' => 'movements',
],

// Cache des produits populaires
Cache::remember('products.popular', 300, function () {
    return Product::with('category')->orderBy('name')->get();
});
```

---

## 🔧 Tests d'Intégration

### 1. Tests API REST
```php
// tests/Feature/ApiTest.php
class ApiTest extends TestCase
{
    public function test_api_products_endpoint()
    {
        $response = $this->get('/api/products');
        $response->assertStatus(200)
                ->assertJsonStructure([
                    'data' => [
                        '*' => ['id', 'name', 'current_stock', 'category']
                    ]
                ]);
    }
    
    public function test_api_movements_bulk_create()
    {
        $movements = [
            ['product_id' => 1, 'type' => 'in', 'quantity' => 10],
            ['product_id' => 2, 'type' => 'out', 'quantity' => 5],
        ];
        
        $response = $this->post('/api/movements/bulk', ['movements' => $movements]);
        $response->assertStatus(201);
    }
}
```

### 2. Tests WebSocket (Temps Réel)
```javascript
// tests/Realtime/RealtimeTest.js
describe('Tests WebSocket', () => {
    test('Mise à jour temps réel des stocks', async () => {
        const ws = new WebSocket('ws://127.0.0.1:6001/app/stock');
        
        ws.onmessage = (event) => {
            const data = JSON.parse(event.data);
            expect(data.type).toBe('stock_update');
            expect(data.product_id).toBeDefined();
            expect(data.new_stock).toBeDefined();
        };
        
        // Simuler un mouvement
        await fetch('/api/movements', {
            method: 'POST',
            body: JSON.stringify({
                product_id: 1,
                type: 'out',
                quantity: 1
            })
        });
    });
});
```

---

## 📈 Monitoring et Métriques

### 1. Configuration Telescope
```php
// config/telescope.php
'watchers' => [
    'requests' => ['enabled' => true],
    'queries' => ['enabled' => true, 'slow' => 100],
    'cache' => ['enabled' => true],
    'events' => ['enabled' => true],
    'notifications' => ['enabled' => true],
    'jobs' => ['enabled' => true],
],
```

### 2. Métriques de Performance
```php
// app/Http/Middleware/PerformanceMiddleware.php
class PerformanceMiddleware
{
    public function handle($request, Closure $next)
    {
        $start = microtime(true);
        
        $response = $next($request);
        
        $duration = microtime(true) - $start;
        
        if ($duration > 2.0) {
            Log::warning('Slow request detected', [
                'url' => $request->url(),
                'duration' => $duration,
                'method' => $request->method()
            ]);
        }
        
        return $response;
    }
}
```

---

## 🚀 Scripts de Test Automatisés

### 1. Script de Test Complet
```bash
#!/bin/bash
# run-all-tests.sh

echo "🧪 Lancement des tests unitaires..."
php artisan test --testsuite=Unit

echo "🔄 Lancement des tests fonctionnels..."
php artisan test --testsuite=Feature

echo "⚡ Test de charge 5000 mouvements/minute..."
php artisan test tests/Load/StockMovementLoadTest.php

echo "📊 Test 10 utilisateurs simultanés..."
artillery run artillery-config.yml

echo "✅ Tous les tests terminés !"
```

### 2. Validation Continue
```yaml
# .github/workflows/tests.yml
name: Tests Continus
on: [push, pull_request]

jobs:
  test:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.1'
      - name: Install dependencies
        run: composer install
      - name: Run tests
        run: |
          php artisan test
          artillery run artillery-config.yml
```

---

## 📋 Checklist de Validation

### ✅ Tests Unitaires
- [ ] API Produits complète
- [ ] API Mouvements complète  
- [ ] Algorithme de prédiction validé
- [ ] Couverture de code > 80%

### ✅ Tests Fonctionnels
- [ ] CRUD Produits fonctionnel
- [ ] Passage inventaire complet
- [ ] Alertes automatiques actives
- [ ] Interface responsive testée

### ✅ Tests de Charge
- [ ] 10 utilisateurs simultanés ✓
- [ ] 5 000 mouvements/minute ✓
- [ ] Temps de réponse < 2s
- [ ] Pas de perte de données

### ✅ Monitoring
- [ ] Telescope configuré
- [ ] Métriques en place
- [ ] Alertes performance
- [ ] Logs structurés

---

## 🎯 Objectifs de Performance

| Métrique | Objectif | Actuel | Status |
|----------|----------|---------|--------|
| Mouvements/minute | 5 000 | À tester | ⏳ |
| Utilisateurs simultanés | 10 | À tester | ⏳ |
| Temps de réponse API | < 200ms | À tester | ⏳ |
| Temps de réponse UI | < 2s | À tester | ⏳ |
| Disponibilité | 99.9% | À tester | ⏳ |

---

## 📝 Conclusion

Ce plan de tests complet garantit que le système peut :
- ✅ Gérer 5 000 mouvements par minute
- ✅ Supporter 10 utilisateurs simultanés  
- ✅ Maintenir des performances optimales
- ✅ Assurer la fiabilité des données
- ✅ Offrir une expérience utilisateur fluide

**Prochaine étape :** Implémenter progressivement ces tests en commençant par les tests unitaires, puis les tests fonctionnels, et enfin les tests de charge.
