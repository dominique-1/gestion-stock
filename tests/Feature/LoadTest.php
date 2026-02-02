<?php

namespace Tests\Feature;

use App\Models\Product;
use App\Models\Category;
use App\Models\User;
use App\Models\StockMovement;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Cache;

class LoadTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->create([
            'email' => 'loadtest@example.com',
            'name' => 'Load Test User'
        ]);
        Sanctum::actingAs($this->user);

        $this->category = Category::factory()->create(['name' => 'Load Test Category']);
        
        // Créer des produits pour les tests de charge
        $this->products = Product::factory()->count(100)->create([
            'category_id' => $this->category->id,
            'current_stock' => 50,
            'stock_min' => 10,
            'stock_optimal' => 100
        ]);
    }

    /**
     * Test de charge: 10 utilisateurs simultanés
     */
    public function test_concurrent_users_load()
    {
        $startTime = microtime(true);
        $concurrentRequests = 10;
        $responses = [];

        // Simuler 10 requêtes simultanées
        for ($i = 0; $i < $concurrentRequests; $i++) {
            $responses[] = $this->getJson('/api/v1/products');
        }

        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;

        // Vérifications
        foreach ($responses as $response) {
            $response->assertStatus(200);
        }

        // Le temps total devrait être raisonnable (< 5 secondes pour 10 requêtes)
        $this->assertLessThan(5.0, $totalTime, 
            "Le temps de réponse total ({$totalTime}s) dépasse la limite acceptable pour 10 requêtes simultanées");

        // Vérifier que toutes les requêtes ont réussi
        $this->assertCount($concurrentRequests, $responses);
    }

    /**
     * Test de charge: 5,000 mouvements/minute (simulation)
     */
    public function test_high_volume_movements_simulation()
    {
        $startTime = microtime(true);
        $targetMovementsPerMinute = 5000;
        $movementsToCreate = 100; // Simulation réduite pour les tests
        
        $createdMovements = [];
        
        for ($i = 0; $i < $movementsToCreate; $i++) {
            $movementData = [
                'product_id' => $this->products[$i % count($this->products)]->id,
                'type' => $i % 2 === 0 ? 'in' : 'out',
                'reason' => 'Test mouvement ' . $i,
                'quantity' => rand(1, 10),
                'moved_at' => now()->toISOString(),
                'user_id' => $this->user->id
            ];

            $response = $this->postJson('/api/v1/movements', $movementData);
            
            if ($response->status() === 201) {
                $createdMovements[] = $response->json();
            }
        }

        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;
        
        // Calculer le taux de mouvements par minute
        $movementsPerMinute = (count($createdMovements) / $totalTime) * 60;

        // Vérifications
        $this->assertEquals($movementsToCreate, count($createdMovements));
        $this->assertGreaterThan(0, $movementsPerMinute);

        // Pour le test, nous vérifions que le système peut gérer au moins un certain taux
        $this->assertGreaterThan(100, $movementsPerMinute, 
            "Le taux de mouvements ({$movementsPerMinute}/min) est trop faible");
    }

    /**
     * Test de charge: Requêtes API multiples
     */
    public function test_multiple_api_endpoints_load()
    {
        $startTime = microtime(true);
        $endpoints = [
            '/api/v1/products',
            '/api/v1/categories',
            '/api/v1/movements',
            '/api/v1/alerts',
            '/api/v1/dashboard/summary'
        ];

        $responses = [];
        $requestsPerEndpoint = 5;

        foreach ($endpoints as $endpoint) {
            for ($i = 0; $i < $requestsPerEndpoint; $i++) {
                $responses[] = $this->getJson($endpoint);
            }
        }

        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;

        // Vérifications
        foreach ($responses as $response) {
            $response->assertStatus(200);
        }

        $totalRequests = count($responses);
        $averageResponseTime = $totalTime / $totalRequests;

        $this->assertLessThan(2.0, $averageResponseTime, 
            "Le temps de réponse moyen ({$averageResponseTime}s) est trop élevé");
    }

    /**
     * Test de charge: Filtrage et pagination
     */
    public function test_filtering_pagination_load()
    {
        $startTime = microtime(true);

        // Test de filtrage complexe
        $filterTests = [
            '/api/v1/products?low_stock=true',
            '/api/v1/products?overstock=true',
            '/api/v1/products?expiring_soon=true',
            '/api/v1/products?q=test&category_id=' . $this->category->id,
            '/api/v1/movements?type=in&from=2024-01-01&to=2024-12-31',
            '/api/v1/alerts?unread=true&level=warning',
            '/api/v1/alerts?type=low_stock&level=critical'
        ];

        $responses = [];
        foreach ($filterTests as $url) {
            $responses[] = $this->getJson($url);
        }

        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;

        // Vérifications
        foreach ($responses as $response) {
            $response->assertStatus(200);
        }

        $this->assertLessThan(3.0, $totalTime, 
            "Le temps de filtrage total ({$totalTime}s) est trop élevé");
    }

    /**
     * Test de charge: Création massive de produits
     */
    public function test_mass_product_creation_load()
    {
        $startTime = microtime(true);
        $productsToCreate = 50;
        $createdProducts = [];

        for ($i = 0; $i < $productsToCreate; $i++) {
            $productData = [
                'name' => "Load Test Product {$i}",
                'description' => "Description for product {$i}",
                'barcode' => str_pad($i, 13, '0', STR_PAD_LEFT),
                'supplier' => 'Load Test Supplier',
                'price' => rand(10, 1000) + (rand(0, 99) / 100),
                'category_id' => $this->category->id,
                'stock_min' => 5,
                'stock_optimal' => 50,
                'current_stock' => rand(10, 100)
            ];

            $response = $this->postJson('/api/v1/products', $productData);
            
            if ($response->status() === 201) {
                $createdProducts[] = $response->json();
            }
        }

        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;
        $creationRate = (count($createdProducts) / $totalTime) * 60; // produits par minute

        // Vérifications
        $this->assertEquals($productsToCreate, count($createdProducts));
        $this->assertGreaterThan(100, $creationRate, 
            "Le taux de création ({$creationRate}/min) est trop faible");

        // Vérifier que tous les produits ont été correctement créés
        foreach ($createdProducts as $product) {
            $this->assertArrayHasKey('id', $product);
            $this->assertArrayHasKey('name', $product);
            $this->assertArrayHasKey('price', $product);
        }
    }

    /**
     * Test de charge: Mise à jour concurrente
     */
    public function test_concurrent_updates_load()
    {
        // Créer un produit de test
        $product = Product::factory()->create([
            'category_id' => $this->category->id,
            'current_stock' => 50
        ]);

        $startTime = microtime(true);
        $concurrentUpdates = 10;
        $responses = [];

        // Simuler des mises à jour concurrentes
        for ($i = 0; $i < $concurrentUpdates; $i++) {
            $updateData = [
                'current_stock' => 50 + $i,
                'stock_min' => 5 + $i,
                'stock_optimal' => 100 + $i
            ];

            $responses[] = $this->putJson("/api/v1/products/{$product->id}", $updateData);
        }

        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;

        // Vérifications
        $successfulUpdates = 0;
        foreach ($responses as $response) {
            if ($response->status() === 200) {
                $successfulUpdates++;
            }
        }

        $this->assertGreaterThan(0, $successfulUpdates);
        $this->assertLessThan(2.0, $totalTime, 
            "Le temps de mise à jour concurrente ({$totalTime}s) est trop élevé");
    }

    /**
     * Test de charge: Performance du cache
     */
    public function test_cache_performance_load()
    {
        $startTime = microtime(true);

        // Première requête (sans cache)
        $response1 = $this->getJson('/api/v1/products');
        $firstRequestTime = microtime(true) - $startTime;

        // Deuxième requête (avec cache potentiel)
        $cacheStartTime = microtime(true);
        $response2 = $this->getJson('/api/v1/products');
        $secondRequestTime = microtime(true) - $cacheStartTime;

        // Vérifications
        $response1->assertStatus(200);
        $response2->assertStatus(200);

        // La deuxième requête devrait être plus rapide (cache)
        if ($secondRequestTime > 0) {
            $speedImprovement = ($firstRequestTime - $secondRequestTime) / $firstRequestTime * 100;
            $this->assertGreaterThan(0, $speedImprovement, 
                "Le cache n'améliore pas les performances");
        }
    }

    /**
     * Test de charge: Requêtes de recherche
     */
    public function test_search_performance_load()
    {
        $startTime = microtime(true);
        $searchQueries = [
            'laptop',
            'monitor',
            'keyboard',
            'mouse',
            'desk',
            'chair',
            'cable',
            'adapter',
            'router',
            'switch'
        ];

        $responses = [];
        foreach ($searchQueries as $query) {
            $responses[] = $this->getJson("/api/v1/products?q={$query}");
        }

        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;
        $averageSearchTime = $totalTime / count($searchQueries);

        // Vérifications
        foreach ($responses as $response) {
            $response->assertStatus(200);
        }

        $this->assertLessThan(0.5, $averageSearchTime, 
            "Le temps de recherche moyen ({$averageSearchTime}s) est trop élevé");
    }

    /**
     * Test de charge: Export de données
     */
    public function test_export_performance_load()
    {
        $startTime = microtime(true);

        // Test d'export CSV
        $response = $this->getJson('/api/v1/exports/stock.csv');

        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;

        // Vérifications
        $response->assertStatus(200);

        $this->assertLessThan(5.0, $totalTime, 
            "Le temps d'export ({$totalTime}s) est trop élevé");
    }

    /**
     * Test de charge: Dashboard complexe
     */
    public function test_dashboard_complex_load()
    {
        $startTime = microtime(true);

        $dashboardEndpoints = [
            '/api/v1/dashboard/summary',
            '/api/v1/dashboard/charts/movements',
            '/api/v1/dashboard/charts/sales',
            '/api/v1/dashboard/predictions',
            '/api/v1/dashboard/top-products'
        ];

        $responses = [];
        foreach ($dashboardEndpoints as $endpoint) {
            $responses[] = $this->getJson($endpoint);
        }

        $endTime = microtime(true);
        $totalTime = $endTime - $startTime;
        $averageDashboardTime = $totalTime / count($dashboardEndpoints);

        // Vérifications
        foreach ($responses as $response) {
            $response->assertStatus(200);
        }

        $this->assertLessThan(1.0, $averageDashboardTime, 
            "Le temps de réponse moyen du dashboard ({$averageDashboardTime}s) est trop élevé");
    }

    /**
     * Test de charge: Simulation de pic de trafic
     */
    public function test_traffic_spike_simulation()
    {
        $startTime = microtime(true);
        $spikeDuration = 2; // secondes
        $requestsPerSecond = 20;
        $totalRequests = $spikeDuration * $requestsPerSecond;

        $responses = [];
        $requestInterval = 1.0 / $requestsPerSecond;

        for ($i = 0; $i < $totalRequests; $i++) {
            $requestStart = microtime(true);
            
            $endpoint = $this->getRandomEndpoint();
            $responses[] = $this->getJson($endpoint);
            
            // Attendre pour maintenir le rythme
            $requestTime = microtime(true) - $requestStart;
            if ($requestTime < $requestInterval) {
                usleep(($requestInterval - $requestTime) * 1000000);
            }
        }

        $endTime = microtime(true);
        $actualDuration = $endTime - $startTime;

        // Vérifications
        $successfulRequests = 0;
        foreach ($responses as $response) {
            if ($response->status() === 200) {
                $successfulRequests++;
            }
        }

        $successRate = ($successfulRequests / $totalRequests) * 100;
        $this->assertGreaterThan(90, $successRate, 
            "Le taux de succès ({$successRate}%) est trop faible pendant le pic de trafic");

        $this->assertLessThan($spikeDuration + 1, $actualDuration, 
            "La durée réelle ({$actualDuration}s) dépasse la durée attendue");
    }

    private function getRandomEndpoint()
    {
        $endpoints = [
            '/api/v1/products',
            '/api/v1/movements',
            '/api/v1/alerts',
            '/api/v1/dashboard/summary'
        ];

        return $endpoints[array_rand($endpoints)];
    }
}
