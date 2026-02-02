<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StockMovementController extends Controller
{
    public function index()
    {
        // Utiliser les mouvements de la session s'ils existent, sinon les données par défaut
        $movements = session()->get('movements', [
            (object)[
                'id' => 1,
                'product' => (object)['name' => 'Laptop Pro 15"'],
                'type' => 'out',
                'quantity' => 2,
                'reason' => 'Vente client',
                'moved_at' => now()->subHours(1),
                'user' => (object)['name' => 'Admin'],
            ],
            (object)[
                'id' => 2,
                'product' => (object)['name' => 'Moniteur 27"'],
                'type' => 'in',
                'quantity' => 5,
                'reason' => 'Réception fournisseur',
                'moved_at' => now()->subHours(3),
                'user' => (object)['name' => 'Admin'],
            ],
        ]);

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

        // Simuler la sauvegarde en ajoutant à une session (pour la démo)
        $movements = session()->get('movements', [
            (object)[
                'id' => 1,
                'product' => (object)['name' => 'Laptop Pro 15"'],
                'type' => 'out',
                'quantity' => 2,
                'reason' => 'Vente client',
                'moved_at' => now()->subHours(1),
                'user' => (object)['name' => 'Admin'],
            ],
            (object)[
                'id' => 2,
                'product' => (object)['name' => 'Moniteur 27"'],
                'type' => 'in',
                'quantity' => 5,
                'reason' => 'Réception fournisseur',
                'moved_at' => now()->subHours(3),
                'user' => (object)['name' => 'Admin'],
            ],
        ]);

        // Trouver le produit correspondant
        $products = [
            1 => (object)['name' => 'Laptop Pro 15"'],
            2 => (object)['name' => 'Moniteur 27"'],
            3 => (object)['name' => 'Clavier mécanique'],
        ];

        // Créer le nouveau mouvement
        $newMovement = (object)[
            'id' => count($movements) + 1,
            'product' => $products[$request->product_id] ?? (object)['name' => 'Produit inconnu'],
            'type' => $request->type,
            'quantity' => $request->quantity,
            'reason' => $request->reason,
            'moved_at' => now(),
            'user' => (object)['name' => 'Admin'],
        ];

        // Ajouter le mouvement
        $movements[] = $newMovement;
        
        // Sauvegarder en session pour la démo
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
}
