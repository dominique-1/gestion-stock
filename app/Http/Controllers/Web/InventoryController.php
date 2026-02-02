<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index(Request $request)
    {
        // Utiliser les inventaires de la session s'ils existent, sinon les données par défaut
        $inventories = session()->get('inventories', [
            (object)[
                'id' => 1,
                'reference' => 'INV-2025-001',
                'status' => 'completed',
                'performed_at' => now()->subDays(7),
                'created_at' => now()->subDays(7),
                'user' => (object)['name' => 'Admin'],
                'lines_count' => 45,
                'note' => 'Inventaire mensuel',
            ],
            (object)[
                'id' => 2,
                'reference' => 'INV-2025-002',
                'status' => 'in_progress',
                'performed_at' => now()->subDays(2),
                'created_at' => now()->subDays(2),
                'user' => (object)['name' => 'Admin'],
                'lines_count' => 32,
                'note' => 'Inventaire partiel',
            ],
        ]);

        return view('inventories.index', compact('inventories'));
    }

    public function create()
    {
        return view('inventories.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'reference' => 'required|string|max:255',
            'performed_at' => 'required|date',
            'note' => 'nullable|string',
        ]);

        // Simuler la sauvegarde en ajoutant à une session (pour la démo)
        $inventories = session()->get('inventories', [
            (object)[
                'id' => 1,
                'reference' => 'INV-2025-001',
                'status' => 'completed',
                'performed_at' => now()->subDays(7),
                'created_at' => now()->subDays(7),
                'user' => (object)['name' => 'Admin'],
                'lines_count' => 45,
                'note' => 'Inventaire mensuel',
            ],
            (object)[
                'id' => 2,
                'reference' => 'INV-2025-002',
                'status' => 'in_progress',
                'performed_at' => now()->subDays(2),
                'created_at' => now()->subDays(2),
                'user' => (object)['name' => 'Admin'],
                'lines_count' => 32,
                'note' => 'Inventaire partiel',
            ],
        ]);

        // Créer le nouvel inventaire
        $newInventory = (object)[
            'id' => count($inventories) + 1,
            'reference' => $request->reference,
            'status' => 'in_progress',
            'performed_at' => \Carbon\Carbon::parse($request->performed_at),
            'created_at' => now(),
            'user' => (object)['name' => 'Admin'],
            'lines_count' => 0,
            'note' => $request->note,
        ];

        // Ajouter l'inventaire
        $inventories[] = $newInventory;
        
        // Sauvegarder en session pour la démo
        session()->put('inventories', $inventories);

        return redirect()->route('inventories.index')->with('success', 'Inventaire créé avec succès.');
    }

    public function show($id)
    {
        // Données factices pour l'inventaire
        $inventory = (object)[
            'id' => $id,
            'reference' => 'INV-2025-001',
            'status' => 'completed',
            'performed_at' => now()->subDays(7),
            'created_at' => now()->subDays(7),
            'user' => (object)['name' => 'Admin'],
            'note' => 'Inventaire mensuel complet',
            'lines' => collect([
                (object)[
                    'id' => 1,
                    'product' => (object)['name' => 'Laptop Pro 15"'],
                    'qty_expected' => 10,
                    'qty_counted' => 7,
                    'qty_diff' => -3,
                    'justification' => 'Produit vendu non encore comptabilisé'
                ],
                (object)[
                    'id' => 2,
                    'product' => (object)['name' => 'Moniteur 27"'],
                    'qty_expected' => 15,
                    'qty_counted' => 12,
                    'qty_diff' => -3,
                    'justification' => 'Démo en magasin'
                ],
                (object)[
                    'id' => 3,
                    'product' => (object)['name' => 'Pack stylos'],
                    'qty_expected' => 20,
                    'qty_counted' => 25,
                    'qty_diff' => 5,
                    'justification' => 'Erreur de comptage initial'
                ],
                (object)[
                    'id' => 4,
                    'product' => (object)['name' => 'Windows 11 Pro'],
                    'qty_expected' => 50,
                    'qty_counted' => 48,
                    'qty_diff' => -2,
                    'justification' => '2 licences non trouvées'
                ],
            ]),
        ];

        return view('inventories.show', compact('inventory'));
    }

    public function edit($id)
    {
        // Récupérer l'inventaire depuis la session
        $inventories = session()->get('inventories', []);
        
        // Trouver l'inventaire spécifique
        $inventory = null;
        foreach ($inventories as $inv) {
            if ($inv->id == $id) {
                $inventory = $inv;
                break;
            }
        }
        
        if (!$inventory) {
            return redirect()->route('inventories.index')->with('error', 'Inventaire non trouvé.');
        }
        
        // Produits disponibles pour les lignes d'inventaire
        $products = [
            (object)['id' => 1, 'name' => 'Laptop Pro 15"', 'reference' => 'LP15-001'],
            (object)['id' => 2, 'name' => 'Moniteur 27"', 'reference' => 'MON27-001'],
            (object)['id' => 3, 'name' => 'Clavier mécanique', 'reference' => 'KEY-MEC-001'],
            (object)['id' => 4, 'name' => 'Souris sans fil', 'reference' => 'MOU-001'],
            (object)['id' => 5, 'name' => 'Webcam HD', 'reference' => 'WCAM-001'],
        ];
        
        return view('inventories.edit', compact('inventory', 'products'));
    }

    public function update(Request $request, $id)
    {
        // Récupérer les inventaires de la session
        $inventories = session()->get('inventories', []);
        
        // Trouver et mettre à jour l'inventaire
        foreach ($inventories as &$inventory) {
            if ($inventory->id == $id) {
                $inventory->status = $request->status ?? 'in_progress';
                $inventory->note = $request->note;
                $inventory->performed_at = \Carbon\Carbon::parse($request->performed_at);
                break;
            }
        }
        
        // Sauvegarder en session
        session()->put('inventories', $inventories);
        
        return redirect()->route('inventories.index')->with('success', 'Inventaire mis à jour avec succès.');
    }

    public function close($id)
    {
        // Récupérer les inventaires de la session
        $inventories = session()->get('inventories', []);
        
        // Trouver et clôturer l'inventaire
        foreach ($inventories as &$inventory) {
            if ($inventory->id == $id) {
                $inventory->status = 'completed';
                break;
            }
        }
        
        session()->put('inventories', $inventories);
        
        return redirect()->route('inventories.index')->with('success', 'Inventaire clôturé avec succès.');
    }

    public function archive($id)
    {
        // Récupérer les inventaires de la session
        $inventories = session()->get('inventories', []);
        
        // Trouver et archiver l'inventaire
        foreach ($inventories as &$inventory) {
            if ($inventory->id == $id && $inventory->status === 'completed') {
                $inventory->status = 'archived';
                $inventory->archived_at = now();
                break;
            }
        }
        
        session()->put('inventories', $inventories);
        
        return redirect()->route('inventories.index')->with('success', 'Inventaire archivé avec succès.');
    }

    public function restore($id)
    {
        // Récupérer les inventaires de la session
        $inventories = session()->get('inventories', []);
        
        // Trouver et restaurer l'inventaire
        foreach ($inventories as &$inventory) {
            if ($inventory->id == $id && $inventory->status === 'archived') {
                $inventory->status = 'completed';
                unset($inventory->archived_at);
                break;
            }
        }
        
        session()->put('inventories', $inventories);
        
        return redirect()->route('inventories.index')->with('success', 'Inventaire restauré avec succès.');
    }

    public function destroy($id)
    {
        // Récupérer les inventaires de la session
        $inventories = session()->get('inventories', []);
        
        // Trouver et supprimer l'inventaire
        $found = false;
        foreach ($inventories as $key => $inventory) {
            if ($inventory->id == $id) {
                unset($inventories[$key]);
                $found = true;
                break;
            }
        }
        
        if ($found) {
            // Sauvegarder la liste mise à jour en session
            session()->put('inventories', $inventories);
            return redirect()->route('inventories.index')->with('success', 'Inventaire supprimé avec succès.');
        } else {
            return redirect()->route('inventories.index')->with('error', 'Inventaire non trouvé.');
        }
    }
}
