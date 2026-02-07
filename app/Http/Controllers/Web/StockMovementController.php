<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index()
    {
        // Utiliser les mouvements de la session (initialisés par le middleware)
        $movements = session()->get('movements', []);
        
        // Si aucun mouvement en session, forcer l'initialisation
        if (empty($movements)) {
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
            $movements = $defaultMovements;
        }

        return view('movements.index', compact('movements'));
    }

    public function create()
    {
        $products = collect([
            (object)['id' => 1, 'name' => 'Laptop Pro 15"', 'reference' => 'LP15-001'],
            (object)['id' => 2, 'name' => 'Moniteur 27"', 'reference' => 'MON27-001'],
            (object)['id' => 3, 'name' => 'Clavier mécanique', 'reference' => 'KEY-MEC-001'],
        ]);
        
        return view('movements.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        // Récupérer les mouvements existants de la session
        $movements = session()->get('movements', []);

        // Trouver le produit correspondant
        $products = [
            1 => (object)['id' => 1, 'name' => 'Laptop Pro 15"', 'barcode' => 'LP15-001'],
            2 => (object)['id' => 2, 'name' => 'Moniteur 27"', 'barcode' => 'MON27-001'],
            3 => (object)['id' => 3, 'name' => 'Clavier mécanique', 'barcode' => 'KEY-MEC-001'],
        ];

        // Créer le nouveau mouvement
        $newMovement = (object)[
            'id' => count($movements) > 0 ? max(array_column($movements, 'id')) + 1 : 1,
            'product' => $products[$request->product_id] ?? (object)[
                'id' => 999, 
                'name' => 'Produit inconnu', 
                'barcode' => 'UNKNOWN'
            ],
            'type' => $request->type,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'moved_at' => now(),
            'user' => (object)['name' => 'Admin'],
        ];

        // Ajouter le mouvement
        $movements[] = $newMovement;
        
        // Sauvegarder en session
        session()->put('movements', $movements);

        return redirect()->route('movements.index')->with('success', 'Mouvement enregistré avec succès.');
    }

    public function show($id)
    {
        $movement = (object)[
            'id' => $id,
            'product' => (object)[
                'id' => 1,
                'name' => 'Laptop Pro 15"'
            ],
            'type' => 'out',
            'quantity' => 2,
            'reason' => 'Vente client',
            'moved_at' => now()->subHours(1),
            'user' => (object)['name' => 'Admin'],
            'note' => 'Vente au client ABC',
        ];

        return view('movements.show', compact('movement'));
    }

    public function edit($id)
    {
        $movement = (object)[
            'id' => $id,
            'product' => (object)[
                'id' => 1,
                'name' => 'Laptop Pro 15"'
            ],
            'type' => 'out',
            'quantity' => 2,
            'reason' => 'Vente client',
            'moved_at' => now()->subHours(1),
            'user' => (object)['name' => 'Admin'],
            'note' => 'Vente au client ABC',
        ];

        $products = collect([
            (object)['id' => 1, 'name' => 'Laptop Pro 15"', 'reference' => 'LP15-001'],
            (object)['id' => 2, 'name' => 'Moniteur 27"', 'reference' => 'MON27-001'],
            (object)['id' => 3, 'name' => 'Clavier mécanique', 'reference' => 'KEY-MEC-001'],
        ]);
        
        return view('movements.edit', compact('movement', 'products'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required',
            'type' => 'required|in:in,out',
            'quantity' => 'required|integer|min:1',
            'reason' => 'nullable|string',
        ]);

        // Simuler la mise à jour (pour la démo)
        return redirect()->route('movements.index')->with('success', 'Mouvement mis à jour avec succès.');
    }

    public function destroy($id)
    {
        // Récupérer les mouvements de la session
        $movements = session()->get('movements', []);
        
        // Debug: afficher les mouvements avant suppression
        \Log::info('Mouvements avant suppression', [
            'id_to_delete' => $id,
            'movements_count' => count($movements),
            'movements_ids' => array_column($movements, 'id')
        ]);
        
        // Filtrer pour supprimer le mouvement avec l'ID correspondant
        $originalCount = count($movements);
        $movements = array_filter($movements, function($movement) use ($id) {
            return $movement->id != $id;
        });
        
        // Réindexer le tableau
        $movements = array_values($movements);
        
        // Debug: afficher les mouvements après suppression
        \Log::info('Mouvements après suppression', [
            'id_deleted' => $id,
            'original_count' => $originalCount,
            'new_count' => count($movements),
            'deleted' => $originalCount > count($movements)
        ]);
        
        // Sauvegarder en session
        session()->put('movements', $movements);
        
        $message = $originalCount > count($movements) 
            ? 'Mouvement supprimé avec succès.' 
            : 'Mouvement non trouvé.';
            
        return redirect()->route('movements.index')->with('success', $message);
    }
}
