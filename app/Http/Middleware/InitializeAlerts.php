<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InitializeAlerts
{
    public function handle(Request $request, Closure $next)
    {
        // Initialiser les alertes par défaut si elles n'existent pas
        if (!session()->has('alerts')) {
            $defaultAlerts = [
                (object)[
                    'id' => 1,
                    'type' => 'min_stock',
                    'level' => 'critical',
                    'message' => 'Stock faible pour Clavier mécanique (1/5)',
                    'product_id' => 1,
                    'product' => (object)[
                        'id' => 1,
                        'name' => 'Clavier mécanique',
                        'stock' => 1,
                        'unit' => 'pièce(s)'
                    ],
                    'is_read' => false,
                    'email_sent_at' => now()->subMinutes(30),
                    'created_at' => now()->subHours(2),
                    'updated_at' => now()->subHour(),
                    'creator' => (object)['name' => 'Admin'],
                ],
                (object)[
                    'id' => 2,
                    'type' => 'overstock',
                    'level' => 'warning',
                    'message' => 'Surstock pour Souris sans fil (25/20)',
                    'product_id' => 2,
                    'product' => (object)[
                        'id' => 2,
                        'name' => 'Souris sans fil',
                        'stock' => 25,
                        'unit' => 'pièce(s)'
                    ],
                    'is_read' => false,
                    'email_sent_at' => now()->subHour(),
                    'created_at' => now()->subHours(3),
                    'updated_at' => now()->subMinutes(30),
                    'creator' => (object)['name' => 'Admin'],
                ],
                (object)[
                    'id' => 3,
                    'type' => 'info',
                    'level' => 'info',
                    'message' => 'Mise à jour des prix terminée',
                    'product_id' => null,
                    'product' => null,
                    'is_read' => true,
                    'email_sent_at' => null,
                    'created_at' => now()->subDays(1),
                    'updated_at' => now()->subDays(1),
                    'creator' => (object)['name' => 'System'],
                ],
            ];
            session()->put('alerts', $defaultAlerts);
        }
        
        return $next($request);
    }
}
