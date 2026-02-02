<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\StockMovement;
use App\Models\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function summary()
    {
        // Indicateurs clés du cahier des charges
        $totalProducts = Product::count();
        $totalStockValue = Product::selectRaw('SUM(price * current_stock) as value')->value('value') ?? 0;
        $lowStockProducts = Product::lowStock()->count();
        
        // Prévision des besoins (produits en dessous du stock optimal)
        $needsForecast = Product::where('current_stock', '<', DB::raw('stock_optimal'))->count();
        
        // Produits proches de la rupture (stock critique)
        $criticalStock = Product::where('current_stock', '<=', DB::raw('stock_min'))->count();

        return response()->json([
            'total_products' => $totalProducts,
            'total_stock_value' => $totalStockValue,
            'low_stock_products' => $lowStockProducts,
            'critical_stock' => $criticalStock,
            'needs_forecast' => $needsForecast,
        ]);
    }

    public function chartsMovements(Request $request)
    {
        // Données factices pour les graphiques
        $labels = [];
        $entries = [];
        $exits = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $labels[] = now()->subDays($i)->format('d/m');
            $entries[] = rand(5, 25);
            $exits[] = rand(3, 20);
        }

        if ($request->input('group') === 'category') {
            return response()->json([
                'categories' => ['Informatique', 'Bureau', 'Logiciel', 'Accessoires'],
                'movements' => [45, 32, 28, 15],
            ]);
        }

        return response()->json([
            'labels' => $labels,
            'entries' => $entries,
            'exits' => $exits,
        ]);
    }

    public function salesChart(Request $request)
    {
        $labels = [];
        $sales = [];
        
        for ($i = 29; $i >= 0; $i--) {
            $labels[] = now()->subDays($i)->format('d/m');
            $sales[] = rand(3, 20);
        }

        return response()->json([
            'labels' => $labels,
            'sales' => $sales,
        ]);
    }

    public function predictions(Request $request)
    {
        $period = $request->input('period', 7);
        $algorithm = $request->input('algorithm', 'auto');
        $movingAverageDays = $request->input('moving_average_days', 7);
        
        // Utiliser le PredictionController pour générer des prédictions réelles
        $predictionController = new \App\Http\Controllers\Web\PredictionController();
        
        // Simuler une requête pour le PredictionController
        $simulatedRequest = new Request([
            'period' => $period,
            'algorithm' => $algorithm,
            'moving_average_days' => $movingAverageDays
        ]);
        
        $response = $predictionController->apiPredictions($simulatedRequest);
        return $response;
    }

    public function topProducts(Request $request)
    {
        // Données factices pour top produits
        $products = [
            ['id' => 1, 'name' => 'Laptop Pro 15"', 'movements' => 45],
            ['id' => 2, 'name' => 'Moniteur 27"', 'movements' => 38],
            ['id' => 3, 'name' => 'Pack stylos', 'movements' => 32],
            ['id' => 4, 'name' => 'Licence Office', 'movements' => 28],
            ['id' => 5, 'name' => 'Clavier mécanique', 'movements' => 25],
            ['id' => 6, 'name' => 'Souris sans fil', 'movements' => 22],
            ['id' => 7, 'name' => 'Webcam HD', 'movements' => 18],
            ['id' => 8, 'name' => 'Casque Bluetooth', 'movements' => 15],
            ['id' => 9, 'name' => 'Hub USB-C', 'movements' => 12],
            ['id' => 10, 'name' => 'Câble HDMI', 'movements' => 10],
        ];

        return response()->json($products);
    }
}
