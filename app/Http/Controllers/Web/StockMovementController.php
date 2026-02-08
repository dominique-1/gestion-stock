<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index()
    {
        // Récupérer les mouvements de la session
        $movements = session()->get('movements', []);

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
            'moved_at' => 'nullable|date',
            'reason' => 'nullable|string',
        ]);

        // Récupérer les mouvements existants
        $movements = session()->get('movements', []);

        // Produits disponibles
        $products = [
            1 => (object)['id' => 1, 'name' => 'Laptop Pro 15"', 'barcode' => 'LP15-001'],
            2 => (object)['id' => 2, 'name' => 'Moniteur 27"', 'barcode' => 'MON27-001'],
            3 => (object)['id' => 3, 'name' => 'Clavier mécanique', 'barcode' => 'KEY-MEC-001'],
        ];

        // Calculer le nouvel ID
        $newId = 1;
        if (!empty($movements)) {
            $ids = [];
            foreach ($movements as $movement) {
                $ids[] = $movement->id;
            }
            $newId = max($ids) + 1;
        }

        // Créer le mouvement simple
        $newMovement = (object)[
            'id' => $newId,
            'product' => $products[$request->product_id] ?? (object)['id' => 999, 'name' => 'Produit inconnu'],
            'type' => $request->type,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'moved_at' => $request->moved_at ? \Carbon\Carbon::parse($request->moved_at) : now(),
            'user' => (object)['name' => 'Admin'],
        ];

        // Ajouter et sauvegarder
        $movements[] = $newMovement;
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
