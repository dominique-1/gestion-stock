<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Models\Product;

class PredictionController extends Controller
{
    /**
     * Affiche la page de prédictions avec tous les algorithmes
     */
    public function index()
    {
        // Récupérer les paramètres
        $period = request('period', 7); // 7, 30, 90 jours
        $algorithm = request('algorithm', 'auto'); // linear, moving_average, auto
        $movingAverageDays = request('moving_average_days', 7);
        $productId = request('product_id');
        
        // Récupérer les produits
        $products = Product::orderBy('name')->get();
        
        // Générer les données historiques factices
        $historicalData = $this->generateHistoricalData(90);
        
        // Choisir l'algorithme
        $chosenAlgorithm = $algorithm === 'auto' ? $this->chooseAlgorithm($historicalData) : $algorithm;
        
        // Générer les prédictions
        $predictions = $this->generatePredictions($historicalData, $period, $chosenAlgorithm, $movingAverageDays);
        
        // Calculer les estimations de rupture
        $stockRisks = $this->calculateStockRisks($historicalData, $predictions);
        
        // Générer les recommandations
        $recommendations = $this->generateRecommendations($stockRisks, $predictions);
        
        // Debug
        \Log::info('Historical data count: ' . $historicalData->count());
        \Log::info('Predictions count: ' . count($predictions));
        \Log::info('First historical data: ' . json_encode($historicalData->first()));
        \Log::info('First prediction: ' . json_encode($predictions[0] ?? 'none'));
        
        return view('predictions.index', compact(
            'period',
            'algorithm',
            'movingAverageDays',
            'chosenAlgorithm',
            'historicalData',
            'predictions',
            'stockRisks',
            'recommendations',
            'products'
        ));
    }
    
    /**
     * API pour les prédictions (pour le dashboard)
     */
    public function apiPredictions(Request $request)
    {
        $period = $request->input('period', 7);
        $algorithm = $request->input('algorithm', 'auto');
        $movingAverageDays = $request->input('moving_average_days', 7);
        
        $historicalData = $this->generateHistoricalData(90);
        $chosenAlgorithm = $algorithm === 'auto' ? $this->chooseAlgorithm($historicalData) : $algorithm;
        $predictions = $this->generatePredictions($historicalData, $period, $chosenAlgorithm, $movingAverageDays);
        
        return response()->json([
            'algorithm' => $chosenAlgorithm,
            'predictions' => $predictions,
            'stock_risks' => $this->calculateStockRisks($historicalData, $predictions),
            'recommendations' => $this->generateRecommendations(
                $this->calculateStockRisks($historicalData, $predictions), 
                $predictions
            )
        ]);
    }
    
    /**
     * Génère les données historiques factices
     */
    private function generateHistoricalData($days)
    {
        $data = [];
        $currentStock = 50;
        
        for ($i = $days; $i >= 0; $i--) {
            $date = now()->subDays($i);
            $movements = rand(-15, 10);
            $currentStock = max(0, $currentStock + $movements);
            
            $data[] = [
                'date' => $date->format('Y-m-d'),
                'stock' => $currentStock,
                'movements' => $movements,
                'day_of_week' => $date->dayOfWeek,
                'day_of_month' => $date->day,
                'month' => $date->month
            ];
        }
        
        return collect($data);
    }
    
    /**
     * Choisit le meilleur algorithme selon les données
     */
    private function chooseAlgorithm($data)
    {
        $dataCount = $data->count();
        
        if ($dataCount > 100) {
            return 'ml'; // Algorithme ML si >100 données
        } elseif ($dataCount > 30) {
            return 'linear'; // Régression linéaire si >30 données
        } else {
            return 'moving_average'; // Moyenne mobile sinon
        }
    }
    
    /**
     * Génère les prédictions selon l'algorithme choisi
     */
    private function generatePredictions($historicalData, $period, $algorithm, $movingAverageDays)
    {
        $predictions = [];
        
        switch ($algorithm) {
            case 'linear':
                $predictions = $this->linearRegression($historicalData, $period);
                break;
            case 'moving_average':
                $predictions = $this->movingAverage($historicalData, $period, $movingAverageDays);
                break;
            case 'ml':
                $predictions = $this->mlPrediction($historicalData, $period);
                break;
            default:
                $predictions = $this->movingAverage($historicalData, $period, $movingAverageDays);
        }
        
        return $predictions;
    }
    
    /**
     * Régression linéaire simple
     */
    private function linearRegression($data, $period)
    {
        $n = $data->count();
        if ($n < 2) return [];
        
        // Calcul des coefficients de régression
        $x = range(0, $n - 1);
        $y = $data->pluck('stock')->toArray();
        
        $sumX = array_sum($x);
        $sumY = array_sum($y);
        $sumXY = 0;
        $sumX2 = 0;
        
        for ($i = 0; $i < $n; $i++) {
            $sumXY += $x[$i] * $y[$i];
            $sumX2 += $x[$i] * $x[$i];
        }
        
        $slope = ($n * $sumXY - $sumX * $sumY) / ($n * $sumX2 - $sumX * $sumX);
        $intercept = ($sumY - $slope * $sumX) / $n;
        
        // Générer les prédictions
        $predictions = [];
        $lastStock = $data->last()['stock'];
        
        for ($i = 1; $i <= $period; $i++) {
            $predictedStock = $intercept + $slope * ($n + $i - 1);
            $predictedStock = max(0, $predictedStock);
            
            $movements = rand(-8, 5);
            $predictedStock = max(0, $predictedStock + $movements);
            
            $predictions[] = [
                'date' => now()->addDays($i)->format('Y-m-d'),
                'predicted_stock' => round($predictedStock, 2),
                'predicted_movements' => $movements,
                'confidence' => max(0.5, 1 - ($i / $period) * 0.3)
            ];
        }
        
        return $predictions;
    }
    
    /**
     * Moyenne mobile sur N jours
     */
    private function movingAverage($data, $period, $days)
    {
        $predictions = [];
        $recentData = $data->take($days);
        $lastStock = $data->last()['stock'];
        
        if ($recentData->count() === 0) return [];
        
        // Calculer la moyenne mobile des mouvements
        $avgMovements = $recentData->avg('movements');
        $stdDeviation = $this->calculateStandardDeviation($recentData->pluck('movements')->toArray());
        
        for ($i = 1; $i <= $period; $i++) {
            // Ajouter de l'aléa basé sur la déviation standard
            $randomFactor = $this->gaussianRandom() * $stdDeviation * 0.5;
            $predictedMovements = round($avgMovements + $randomFactor);
            
            $predictedStock = max(0, $lastStock + $predictedMovements);
            $lastStock = $predictedStock;
            
            $predictions[] = [
                'date' => now()->addDays($i)->format('Y-m-d'),
                'predicted_stock' => round($predictedStock, 2),
                'predicted_movements' => $predictedMovements,
                'confidence' => max(0.4, 1 - ($i / $period) * 0.4)
            ];
        }
        
        return $predictions;
    }
    
    /**
     * Algorithme ML léger (simplifié)
     */
    private function mlPrediction($data, $period)
    {
        $predictions = [];
        $lastStock = $data->last()['stock'];
        
        // Features: jour de la semaine, jour du mois, mois, tendance récente
        for ($i = 1; $i <= $period; $i++) {
            $futureDate = now()->addDays($i);
            $dayOfWeek = $futureDate->dayOfWeek;
            $dayOfMonth = $futureDate->day;
            $month = $futureDate->month;
            
            // Calcul de la tendance récente (7 derniers jours)
            $recentTrend = $this->calculateTrend($data->take(7));
            
            // Facteurs saisonniers simplifiés
            $weekendFactor = ($dayOfWeek == 0 || $dayOfWeek == 6) ? 0.7 : 1.0;
            $monthFactor = $this->getMonthlyFactor($month);
            
            // Prédiction ML simplifiée
            $basePrediction = $lastStock * (1 + $recentTrend * 0.1) * $weekendFactor * $monthFactor;
            $noise = $this->gaussianRandom() * 5;
            
            $predictedStock = max(0, $basePrediction + $noise);
            $predictedMovements = round($predictedStock - $lastStock);
            
            $predictions[] = [
                'date' => $futureDate->format('Y-m-d'),
                'predicted_stock' => round($predictedStock, 2),
                'predicted_movements' => $predictedMovements,
                'confidence' => max(0.6, 1 - ($i / $period) * 0.2)
            ];
            
            $lastStock = $predictedStock;
        }
        
        return $predictions;
    }
    
    /**
     * Calcule les risques de rupture de stock
     */
    private function calculateStockRisks($historicalData, $predictions)
    {
        $currentStock = $historicalData->last()['stock'];
        $stockMin = 10; // Stock minimum fictif
        $risks = [];
        
        foreach ($predictions as $prediction) {
            $riskLevel = 'low';
            $daysToRupture = null;
            
            if ($prediction['predicted_stock'] <= $stockMin) {
                $riskLevel = 'critical';
                $daysToRupture = now()->diffInDays(Carbon::parse($prediction['date']));
            } elseif ($prediction['predicted_stock'] <= $stockMin * 2) {
                $riskLevel = 'medium';
            }
            
            $risks[] = [
                'date' => $prediction['date'],
                'predicted_stock' => $prediction['predicted_stock'],
                'risk_level' => $riskLevel,
                'days_to_rupture' => $daysToRupture
            ];
        }
        
        return $risks;
    }
    
    /**
     * Génère les recommandations intelligentes
     */
    private function generateRecommendations($stockRisks, $predictions)
    {
        $recommendations = [];
        $currentStock = collect($predictions)->first()['predicted_stock'] ?? 0;
        
        // Analyser les risques
        $criticalRisks = collect($stockRisks)->filter(fn($r) => $r['risk_level'] === 'critical');
        $mediumRisks = collect($stockRisks)->filter(fn($r) => $r['risk_level'] === 'medium');
        
        if ($criticalRisks->isNotEmpty()) {
            $firstCritical = $criticalRisks->first();
            $daysToRupture = $firstCritical['days_to_rupture'];
            $urgency = $daysToRupture <= 3 ? 'immédiate' : 'dans ' . $daysToRupture . ' jours';
            
            $recommendations[] = [
                'type' => 'critical',
                'message' => "⚠️ Risque de rupture $urgency! Commandez immédiatement au moins " . round($firstCritical['predicted_stock'] * 0.5) . " unités.",
                'priority' => 'high',
                'action' => 'order_now'
            ];
        }
        
        if ($mediumRisks->count() >= 3) {
            $recommendations[] = [
                'type' => 'warning',
                'message' => "📉 Plusieurs jours avec stock faible prévu. Envisagez une commande de " . round($currentStock * 0.3) . " unités.",
                'priority' => 'medium',
                'action' => 'plan_order'
            ];
        }
        
        // Tendance générale
        $avgStock = collect($predictions)->avg('predicted_stock');
        if ($avgStock < $currentStock * 0.8) {
            $recommendations[] = [
                'type' => 'trend',
                'message' => "📊 Tendance à la baisse détectée. Surveillez attentivement les ventes.",
                'priority' => 'medium',
                'action' => 'monitor'
            ];
        }
        
        if (empty($recommendations)) {
            $recommendations[] = [
                'type' => 'success',
                'message' => "✅ Aucun risque de rupture prévu. Stock optimal pour les " . count($predictions) . " prochains jours.",
                'priority' => 'low',
                'action' => 'no_action'
            ];
        }
        
        return $recommendations;
    }
    
    /**
     * Calcule la déviation standard
     */
    private function calculateStandardDeviation($values)
    {
        $mean = array_sum($values) / count($values);
        $variance = 0;
        
        foreach ($values as $value) {
            $variance += pow($value - $mean, 2);
        }
        
        return sqrt($variance / count($values));
    }
    
    /**
     * Génère un nombre aléatoire selon distribution gaussienne
     */
    private function gaussianRandom()
    {
        $u1 = mt_rand() / mt_getrandmax();
        $u2 = mt_rand() / mt_getrandmax();
        return sqrt(-2 * log($u1)) * cos(2 * pi() * $u2);
    }
    
    /**
     * Calcule la tendance récente
     */
    private function calculateTrend($data)
    {
        if ($data->count() < 2) return 0;
        
        $first = $data->first()['stock'];
        $last = $data->last()['stock'];
        
        return ($last - $first) / $first;
    }
    
    /**
     * Facteurs saisonniers mensuels (simplifiés)
     */
    private function getMonthlyFactor($month)
    {
        $factors = [
            1 => 0.8,  // Janvier - faible
            2 => 0.9,  // Février - faible
            3 => 1.0,  // Mars - normal
            4 => 1.1,  // Avril - légèrement élevé
            5 => 1.2,  // Mai - élevé
            6 => 1.1,  // Juin - légèrement élevé
            7 => 0.9,  // Juillet - faible (vacances)
            8 => 0.8,  // Août - très faible
            9 => 1.0,  // Septembre - normal
            10 => 1.1, // Octobre - légèrement élevé
            11 => 1.3, // Novembre - très élevé (soldes)
            12 => 1.4, // Décembre - très élevé (fêtes)
        ];
        
        return $factors[$month] ?? 1.0;
    }
}
