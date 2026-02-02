<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Alert;
use App\Models\StockMovement;
use App\Services\AlertService;

class DashboardController extends Controller
{
    public function index()
    {
        // Utiliser les données de session pour éviter les erreurs de base de données
        $products = $this->getMockProducts();
        $movements = $this->getMockMovements();
        $alerts = $this->getMockAlerts();

        // Calculer les indicateurs clés
        $totalProducts = count($products);
        $totalStockValue = array_sum(array_map(fn($p) => $p->price * $p->current_stock, $products));
        $lowStockProducts = count(array_filter($products, fn($p) => $p->current_stock <= $p->stock_min));
        $criticalStockProducts = count(array_filter($products, fn($p) => $p->current_stock == 0));
        $needsForecast = count(array_filter($products, fn($p) => $p->current_stock <= ($p->stock_optimal * 0.8)));

        $data = [
            'total_products' => $totalProducts,
            'total_stock_value' => $totalStockValue,
            'low_stock_products' => $lowStockProducts,
            'critical_stock' => $criticalStockProducts,
            'needs_forecast' => $needsForecast,
        ];

        // Graphiques : évolution stock par produit
        $stockEvolution = $this->getStockEvolutionData();
        
        // Graphiques : ventes/sorties
        $salesData = $this->getSalesData();
        
        // Graphiques : mouvements par catégorie
        $categoryMovements = $this->getCategoryMovementsData();

        return view('dashboard', compact('data', 'movements', 'alerts', 'stockEvolution', 'salesData', 'categoryMovements'));
    }

    public function refresh()
    {
        // Simuler des données en temps réel
        $products = $this->getMockProducts();
        $totalProducts = count($products);
        $totalStockValue = array_sum(array_map(fn($p) => $p->price * $p->current_stock, $products));
        $lowStockProducts = count(array_filter($products, fn($p) => $p->current_stock <= $p->stock_min));
        $needsForecast = count(array_filter($products, fn($p) => $p->current_stock <= ($p->stock_optimal * 0.8)));
        
        $data = [
            'total_products' => $totalProducts,
            'total_stock_value' => $totalStockValue,
            'low_stock_products' => $lowStockProducts,
            'critical_stock' => count(array_filter($products, fn($p) => $p->current_stock == 0)),
            'needs_forecast' => $needsForecast,
            'stock_evolution' => $this->getStockEvolutionData(),
            'sales_data' => $this->getSalesData(),
            'category_movements' => $this->getCategoryMovementsData(),
            'recent_movements' => $this->getMockMovements()
        ];
        
        return response()->json($data);
    }

    private function getMockProducts()
    {
        return [
            (object)['id' => 1, 'name' => 'Laptop Pro 15"', 'price' => 1200, 'current_stock' => 15, 'stock_min' => 5, 'stock_optimal' => 20, 'category' => 'Informatique'],
            (object)['id' => 2, 'name' => 'Moniteur 27"', 'price' => 350, 'current_stock' => 8, 'stock_min' => 3, 'stock_optimal' => 15, 'category' => 'Informatique'],
            (object)['id' => 3, 'name' => 'Clavier mécanique', 'price' => 89, 'current_stock' => 2, 'stock_min' => 5, 'stock_optimal' => 15, 'category' => 'Accessoires'],
            (object)['id' => 4, 'name' => 'Souris sans fil', 'price' => 45, 'current_stock' => 0, 'stock_min' => 3, 'stock_optimal' => 10, 'category' => 'Accessoires'],
            (object)['id' => 5, 'name' => 'Webcam HD', 'price' => 75, 'current_stock' => 12, 'stock_min' => 2, 'stock_optimal' => 8, 'category' => 'Accessoires'],
        ];
    }

    private function getMockMovements()
    {
        return [
            (object)['id' => 1, 'product' => (object)['name' => 'Laptop Pro 15"'], 'type' => 'out', 'quantity' => 2, 'reason' => 'Vente client', 'moved_at' => now()],
            (object)['id' => 2, 'product' => (object)['name' => 'Moniteur 27"'], 'type' => 'in', 'quantity' => 5, 'reason' => 'Réception fournisseur', 'moved_at' => now()->subHours(2)],
            (object)['id' => 3, 'product' => (object)['name' => 'Clavier mécanique'], 'type' => 'out', 'quantity' => 1, 'reason' => 'Vente en ligne', 'moved_at' => now()->subHours(4)],
        ];
    }

    private function getMockAlerts()
    {
        return [
            (object)['id' => 1, 'message' => 'Stock faible pour Clavier mécanique', 'level' => 'warning', 'is_read' => false],
            (object)['id' => 2, 'message' => 'Rupture de stock pour Souris sans fil', 'level' => 'critical', 'is_read' => false],
        ];
    }

    private function getStockEvolutionData()
    {
        return [
            'labels' => ['Jan', 'Fév', 'Mar', 'Avr', 'Mai', 'Juin'],
            'datasets' => [
                ['label' => 'Laptop Pro 15"', 'data' => [20, 18, 22, 19, 17, 15]],
                ['label' => 'Moniteur 27"', 'data' => [12, 10, 13, 11, 9, 8]],
                ['label' => 'Clavier mécanique', 'data' => [8, 6, 4, 3, 2, 2]],
                ['label' => 'Souris sans fil', 'data' => [5, 3, 2, 1, 0, 0]],
            ]
        ];
    }

    private function getSalesData()
    {
        return [
            'labels' => ['Lun', 'Mar', 'Mer', 'Jeu', 'Ven', 'Sam', 'Dim'],
            'sales' => [15, 22, 18, 25, 30, 12, 8],
            'exits' => [12, 19, 15, 22, 28, 10, 6]
        ];
    }

    private function getCategoryMovementsData()
    {
        return [
            'labels' => ['Informatique', 'Accessoires', 'Bureautique', 'Réseau'],
            'entries' => [45, 32, 18, 12],
            'exits' => [38, 28, 15, 8]
        ];
    }
}
