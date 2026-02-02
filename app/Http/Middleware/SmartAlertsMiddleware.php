<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SmartAlertsMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // Initialiser les données pour les Smart Alerts si nécessaire
        if (!session()->has('smart_alerts_initialized')) {
            $this->initializeSmartAlerts();
            session()->put('smart_alerts_initialized', true);
        }
        
        return $next($request);
    }
    
    private function initializeSmartAlerts()
    {
        // Initialiser les produits factices pour les alertes
        if (!session()->has('products')) {
            session()->put('products', [
                (object)[
                    'id' => 1,
                    'name' => 'Laptop Pro 15"',
                    'current_stock' => 8,
                    'stock_min' => 10,
                    'expires_at' => now()->addDays(15),
                    'reference' => 'LP15-001'
                ],
                (object)[
                    'id' => 2,
                    'name' => 'Moniteur 27"',
                    'current_stock' => 150,
                    'stock_min' => 20,
                    'expires_at' => now()->addDays(90),
                    'reference' => 'MON27-001'
                ],
                (object)[
                    'id' => 3,
                    'name' => 'Clavier mécanique',
                    'current_stock' => 5,
                    'stock_min' => 15,
                    'expires_at' => now()->addDays(5),
                    'reference' => 'KEY-MEC-001'
                ],
                (object)[
                    'id' => 4,
                    'name' => 'Souris sans fil',
                    'current_stock' => 120,
                    'stock_min' => 25,
                    'expires_at' => now()->addDays(60),
                    'reference' => 'MOU-001'
                ],
            ]);
        }
        
        // Initialiser les mouvements factices
        if (!session()->has('movements')) {
            $movements = [];
            for ($i = 0; $i < 30; $i++) {
                $movements[] = (object)[
                    'id' => $i + 1,
                    'product_id' => rand(1, 4),
                    'type' => rand(0, 1) ? 'in' : 'out',
                    'quantity' => rand(1, 20),
                    'reason' => 'Mouvement test',
                    'created_at' => now()->subDays(rand(0, 30)),
                ];
            }
            session()->put('movements', $movements);
        }
        
        // Initialiser les prédictions factices
        if (!session()->has('predictions')) {
            $predictions = [];
            for ($i = 0; $i < 10; $i++) {
                $predictions[] = (object)[
                    'id' => $i + 1,
                    'product_id' => rand(1, 4),
                    'risk_level' => rand(0, 3) === 0 ? 'critical' : 'normal',
                    'confidence' => rand(70, 95),
                    'predicted_stock' => rand(5, 50),
                    'date' => now()->addDays($i + 1),
                ];
            }
            session()->put('predictions', $predictions);
        }
    }
}
