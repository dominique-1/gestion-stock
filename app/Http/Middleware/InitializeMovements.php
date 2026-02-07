<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class InitializeMovements
{
    public function handle(Request $request, Closure $next)
    {
        // Initialiser les mouvements en session s'ils n'existent pas
        if (!session()->has('movements')) {
            $defaultMovements = [
                (object)[
                    'id' => 1,
                    'product' => (object)[
                        'id' => 1,
                        'name' => 'Laptop Pro 15"', 
                        'barcode' => 'LP15-001'
                    ],
                    'type' => 'out',
                    'quantity' => 2,
                    'reason' => 'Vente client',
                    'moved_at' => now()->subHours(1),
                    'user' => (object)['name' => 'Admin'],
                ],
                (object)[
                    'id' => 2,
                    'product' => (object)[
                        'id' => 2,
                        'name' => 'Moniteur 27"', 
                        'barcode' => 'MON27-001'
                    ],
                    'type' => 'in',
                    'quantity' => 5,
                    'reason' => 'Réception fournisseur',
                    'moved_at' => now()->subHours(3),
                    'user' => (object)['name' => 'Admin'],
                ],
                (object)[
                    'id' => 3,
                    'product' => (object)[
                        'id' => 3,
                        'name' => 'Clavier mécanique', 
                        'barcode' => 'KEY-MEC-001'
                    ],
                    'type' => 'out',
                    'quantity' => 1,
                    'reason' => 'Retour client',
                    'moved_at' => now()->subHours(5),
                    'user' => (object)['name' => 'Admin'],
                ],
            ];
            session()->put('movements', $defaultMovements);
        }

        return $next($request);
    }
}
