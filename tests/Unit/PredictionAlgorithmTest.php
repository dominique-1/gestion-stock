<?php

namespace Tests\Unit;

use App\Http\Controllers\Web\PredictionController;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Illuminate\Http\Request;

class PredictionAlgorithmTest extends TestCase
{
    use RefreshDatabase;

    private PredictionController $controller;

    protected function setUp(): void
    {
        parent::setUp();
        $this->controller = new PredictionController();
    }

    public function test_linear_regression_algorithm()
    {
        // Utiliser reflection pour accéder aux méthodes privées
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('linearRegression');
        $method->setAccessible(true);

        // Créer des données historiques factices
        $historicalData = collect([
            ['date' => '2024-01-01', 'stock' => 100, 'movements' => -5],
            ['date' => '2024-01-02', 'stock' => 95, 'movements' => -3],
            ['date' => '2024-01-03', 'stock' => 92, 'movements' => -2],
            ['date' => '2024-01-04', 'stock' => 90, 'movements' => -1],
            ['date' => '2024-01-05', 'stock' => 89, 'movements' => -4],
        ]);

        $predictions = $method->invoke($this->controller, $historicalData, 7);

        $this->assertIsArray($predictions);
        $this->assertCount(7, $predictions);
        
        foreach ($predictions as $prediction) {
            $this->assertArrayHasKey('date', $prediction);
            $this->assertArrayHasKey('predicted_stock', $prediction);
            $this->assertArrayHasKey('predicted_movements', $prediction);
            $this->assertArrayHasKey('confidence', $prediction);
            
            $this->assertGreaterThanOrEqual(0, $prediction['predicted_stock']);
            $this->assertGreaterThanOrEqual(0, $prediction['confidence']);
            $this->assertLessThanOrEqual(1, $prediction['confidence']);
        }
    }

    public function test_moving_average_algorithm()
    {
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('movingAverage');
        $method->setAccessible(true);

        $historicalData = collect([
            ['date' => '2024-01-01', 'stock' => 50, 'movements' => -5],
            ['date' => '2024-01-02', 'stock' => 45, 'movements' => -3],
            ['date' => '2024-01-03', 'stock' => 42, 'movements' => -2],
            ['date' => '2024-01-04', 'stock' => 40, 'movements' => -1],
            ['date' => '2024-01-05', 'stock' => 39, 'movements' => -4],
            ['date' => '2024-01-06', 'stock' => 35, 'movements' => -6],
            ['date' => '2024-01-07', 'stock' => 29, 'movements' => -8],
        ]);

        $predictions = $method->invoke($this->controller, $historicalData, 5, 7);

        $this->assertIsArray($predictions);
        $this->assertCount(5, $predictions);
        
        // Vérifier que les prédictions sont cohérentes
        foreach ($predictions as $prediction) {
            $this->assertGreaterThanOrEqual(0, $prediction['predicted_stock']);
            $this->assertIsInt($prediction['predicted_movements']);
            $this->assertGreaterThanOrEqual(0, $prediction['confidence']);
        }
    }

    public function test_ml_prediction_algorithm()
    {
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('mlPrediction');
        $method->setAccessible(true);

        $historicalData = collect([
            ['date' => '2024-01-01', 'stock' => 100, 'movements' => -5, 'day_of_week' => 1],
            ['date' => '2024-01-02', 'stock' => 95, 'movements' => -3, 'day_of_week' => 2],
            ['date' => '2024-01-03', 'stock' => 92, 'movements' => -2, 'day_of_week' => 3],
            ['date' => '2024-01-04', 'stock' => 90, 'movements' => -1, 'day_of_week' => 4],
            ['date' => '2024-01-05', 'stock' => 89, 'movements' => -4, 'day_of_week' => 5],
        ]);

        $predictions = $method->invoke($this->controller, $historicalData, 7);

        $this->assertIsArray($predictions);
        $this->assertCount(7, $predictions);
        
        foreach ($predictions as $prediction) {
            $this->assertArrayHasKey('date', $prediction);
            $this->assertArrayHasKey('predicted_stock', $prediction);
            $this->assertArrayHasKey('predicted_movements', $prediction);
            $this->assertArrayHasKey('confidence', $prediction);
            
            $this->assertGreaterThanOrEqual(0, $prediction['predicted_stock']);
            $this->assertGreaterThanOrEqual(0.6, $prediction['confidence']); // ML a une confiance minimum plus élevée
        }
    }

    public function test_algorithm_selection_logic()
    {
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('chooseAlgorithm');
        $method->setAccessible(true);

        // Test avec peu de données (<30)
        $smallData = collect(range(1, 20));
        $algorithm = $method->invoke($this->controller, $smallData);
        $this->assertEquals('moving_average', $algorithm);

        // Test avec données moyennes (30-100)
        $mediumData = collect(range(1, 50));
        $algorithm = $method->invoke($this->controller, $mediumData);
        $this->assertEquals('linear', $algorithm);

        // Test avec beaucoup de données (>100)
        $largeData = collect(range(1, 150));
        $algorithm = $method->invoke($this->controller, $largeData);
        $this->assertEquals('ml', $algorithm);
    }

    public function test_stock_risk_calculation()
    {
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('calculateStockRisks');
        $method->setAccessible(true);

        $historicalData = collect([
            ['date' => '2024-01-05', 'stock' => 50, 'movements' => -5]
        ]);

        $predictions = [
            ['date' => '2024-01-06', 'predicted_stock' => 45],
            ['date' => '2024-01-07', 'predicted_stock' => 40],
            ['date' => '2024-01-08', 'predicted_stock' => 20], // Critical (<= 10*2)
            ['date' => '2024-01-09', 'predicted_stock' => 8],  // Critical (<= 10)
            ['date' => '2024-01-10', 'predicted_stock' => 5],  // Critical
        ];

        $risks = $method->invoke($this->controller, $historicalData, $predictions);

        $this->assertIsArray($risks);
        $this->assertCount(5, $risks);

        // Vérifier les niveaux de risque
        $criticalRisks = array_filter($risks, fn($r) => $r['risk_level'] === 'critical');
        $this->assertGreaterThanOrEqual(2, count($criticalRisks));

        foreach ($risks as $risk) {
            $this->assertArrayHasKey('date', $risk);
            $this->assertArrayHasKey('predicted_stock', $risk);
            $this->assertArrayHasKey('risk_level', $risk);
            $this->assertContains($risk['risk_level'], ['low', 'medium', 'critical']);
        }
    }

    public function test_recommendation_generation()
    {
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('generateRecommendations');
        $method->setAccessible(true);

        $stockRisks = [
            ['date' => '2024-01-08', 'predicted_stock' => 8, 'risk_level' => 'critical', 'days_to_rupture' => 2],
            ['date' => '2024-01-09', 'predicted_stock' => 5, 'risk_level' => 'critical', 'days_to_rupture' => 1],
        ];

        $predictions = [
            ['date' => '2024-01-06', 'predicted_stock' => 45],
            ['date' => '2024-01-07', 'predicted_stock' => 40],
        ];

        $recommendations = $method->invoke($this->controller, $stockRisks, $predictions);

        $this->assertIsArray($recommendations);
        $this->assertNotEmpty($recommendations);

        foreach ($recommendations as $rec) {
            $this->assertArrayHasKey('type', $rec);
            $this->assertArrayHasKey('message', $rec);
            $this->assertArrayHasKey('priority', $rec);
            $this->assertArrayHasKey('action', $rec);
            
            $this->assertContains($rec['type'], ['critical', 'warning', 'trend', 'success']);
            $this->assertContains($rec['priority'], ['high', 'medium', 'low']);
        }

        // Devrait avoir une recommandation critique
        $criticalRecs = array_filter($recommendations, fn($r) => $r['type'] === 'critical');
        $this->assertNotEmpty($criticalRecs);
    }

    public function test_standard_deviation_calculation()
    {
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('calculateStandardDeviation');
        $method->setAccessible(true);

        $values = [2, 4, 4, 4, 5, 5, 7, 9];
        $stdDev = $method->invoke($this->controller, $values);

        $this->assertIsFloat($stdDev);
        $this->assertGreaterThan(0, $stdDev);
        
        // La déviation standard de [2,4,4,4,5,5,7,9] est environ 2
        $this->assertEqualsWithDelta(2.0, $stdDev, 0.5);
    }

    public function test_gaussian_random_generation()
    {
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('gaussianRandom');
        $method->setAccessible(true);

        // Générer plusieurs valeurs et vérifier la distribution
        $values = [];
        for ($i = 0; $i < 1000; $i++) {
            $values[] = $method->invoke($this->controller);
        }

        $mean = array_sum($values) / count($values);
        $stdDev = sqrt(array_sum(array_map(fn($v) => pow($v - $mean, 2), $values)) / count($values));

        // La moyenne devrait être proche de 0 et l'écart-type proche de 1
        $this->assertEqualsWithDelta(0, $mean, 0.1);
        $this->assertEqualsWithDelta(1, $stdDev, 0.2);
    }

    public function test_trend_calculation()
    {
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('calculateTrend');
        $method->setAccessible(true);

        // Test tendance à la baisse
        $decreasingData = collect([
            ['stock' => 100],
            ['stock' => 90],
            ['stock' => 80],
            ['stock' => 70],
        ]);
        $trend = $method->invoke($this->controller, $decreasingData);
        $this->assertLessThan(0, $trend);

        // Test tendance à la hausse
        $increasingData = collect([
            ['stock' => 70],
            ['stock' => 80],
            ['stock' => 90],
            ['stock' => 100],
        ]);
        $trend = $method->invoke($this->controller, $increasingData);
        $this->assertGreaterThan(0, $trend);

        // Test tendance stable
        $stableData = collect([
            ['stock' => 100],
            ['stock' => 100],
            ['stock' => 100],
            ['stock' => 100],
        ]);
        $trend = $method->invoke($this->controller, $stableData);
        $this->assertEqualsWithDelta(0, $trend, 0.01);
    }

    public function test_monthly_factors()
    {
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('getMonthlyFactor');
        $method->setAccessible(true);

        // Mois avec forte activité
        $decemberFactor = $method->invoke($this->controller, 12);
        $this->assertGreaterThan(1, $decemberFactor);

        $novemberFactor = $method->invoke($this->controller, 11);
        $this->assertGreaterThan(1, $novemberFactor);

        // Mois avec faible activité
        $augustFactor = $method->invoke($this->controller, 8);
        $this->assertLessThan(1, $augustFactor);

        $januaryFactor = $method->invoke($this->controller, 1);
        $this->assertLessThan(1, $januaryFactor);

        // Mois normal
        $marchFactor = $method->invoke($this->controller, 3);
        $this->assertEqualsWithDelta(1.0, $marchFactor, 0.1);
    }

    public function test_api_predictions_endpoint()
    {
        $request = Request::create('/api/predictions', 'GET', [
            'period' => 7,
            'algorithm' => 'linear',
            'moving_average_days' => 5
        ]);

        $response = $this->controller->apiPredictions($request);

        $this->assertEquals(200, $response->getStatusCode());
        
        $data = json_decode($response->getContent(), true);
        $this->assertArrayHasKey('algorithm', $data);
        $this->assertArrayHasKey('predictions', $data);
        $this->assertArrayHasKey('stock_risks', $data);
        $this->assertArrayHasKey('recommendations', $data);

        $this->assertEquals('linear', $data['algorithm']);
        $this->assertIsArray($data['predictions']);
        $this->assertCount(7, $data['predictions']);
    }

    public function test_prediction_confidence_degradation()
    {
        $reflection = new \ReflectionClass($this->controller);
        $method = $reflection->getMethod('linearRegression');
        $method->setAccessible(true);

        $historicalData = collect([
            ['date' => '2024-01-01', 'stock' => 100, 'movements' => -5],
            ['date' => '2024-01-02', 'stock' => 95, 'movements' => -3],
            ['date' => '2024-01-03', 'stock' => 92, 'movements' => -2],
        ]);

        $predictions = $method->invoke($this->controller, $historicalData, 10);

        // La confiance devrait diminuer avec le temps
        $firstConfidence = $predictions[0]['confidence'];
        $lastConfidence = $predictions[count($predictions) - 1]['confidence'];

        $this->assertGreaterThan($lastConfidence, $firstConfidence);
        $this->assertGreaterThanOrEqual(0.5, $lastConfidence); // Confiance minimum
    }
}
