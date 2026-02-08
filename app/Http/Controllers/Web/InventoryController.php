<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class InventoryController extends Controller
{
    public function index()
    {
        // Données factices pour la démo
        $allInventories = session()->get('inventories', [
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
            (object)[
                'id' => 3,
                'reference' => 'INV-2025-003',
                'status' => 'archived',
                'performed_at' => now()->subDays(14),
                'created_at' => now()->subDays(14),
                'archived_at' => now()->subDays(10),
                'user' => (object)['name' => 'Admin'],
                'lines_count' => 28,
                'note' => 'Ancien inventaire archivé',
            ],
        ]);

        // Gérer le filtre
        $filter = request('filter', 'active');
        
        if ($filter == 'archived') {
            $inventories = collect($allInventories)->where('status', 'archived')->values()->all();
        } elseif ($filter == 'active') {
            $inventories = collect($allInventories)->whereIn('status', ['in_progress', 'completed'])->values()->all();
        } else {
            $inventories = $allInventories;
        }

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

        // Vérifier si l'inventaire doit être archivé
        $shouldArchive = $request->has('archive');

        // Créer le nouvel inventaire
        $newInventory = (object)[
            'id' => count($inventories) + 1,
            'reference' => $request->reference,
            'status' => $shouldArchive ? 'archived' : 'in_progress',
            'performed_at' => \Carbon\Carbon::parse($request->performed_at),
            'created_at' => now(),
            'user' => (object)['name' => 'Admin'],
            'lines_count' => 0,
            'note' => $request->note,
        ];

        // Ajouter la date d'archivage si nécessaire
        if ($shouldArchive) {
            $newInventory->archived_at = now();
        }

        // Ajouter l'inventaire
        $inventories[] = $newInventory;
        
        // Sauvegarder en session pour la démo
        session()->put('inventories', $inventories);

        // Message de succès et redirection appropriée
        $message = $shouldArchive ? 'Inventaire créé et archivé avec succès.' : 'Inventaire créé avec succès.';
        $filter = $shouldArchive ? ['filter' => 'archived'] : [];

        return redirect()->route('inventories.index', $filter)->with('success', $message);
    }

    public function show($id)
    {
        $inventories = session()->get('inventories', []);
        $inventory = collect($inventories)->firstWhere('id', $id);
        
        if (!$inventory) {
            return redirect()->route('inventories.index')->with('error', 'Inventaire non trouvé.');
        }
        
        // Ajouter des lignes factices pour la démo si elles n'existent pas
        if (!isset($inventory->lines)) {
            $inventory->lines = [
                (object)[
                    'id' => 1,
                    'product' => (object)['name' => 'Laptop Pro 15"'],
                    'qty_expected' => 10,
                    'qty_counted' => 8,
                    'qty_diff' => -2,
                    'justification' => 'Stock initial incorrect'
                ],
                (object)[
                    'id' => 2,
                    'product' => (object)['name' => 'Moniteur 27"'],
                    'qty_expected' => 5,
                    'qty_counted' => 5,
                    'qty_diff' => 0,
                    'justification' => 'Stock correct'
                ],
                (object)[
                    'id' => 3,
                    'product' => (object)['name' => 'Clavier mécanique'],
                    'qty_expected' => 20,
                    'qty_counted' => 22,
                    'qty_diff' => 2,
                    'justification' => 'Retour client non enregistré'
                ],
            ];
        }
        
        return view('inventories.show', compact('inventory'));
    }

    public function edit($id)
    {
        $inventories = session()->get('inventories', []);
        $inventory = collect($inventories)->firstWhere('id', $id);
        
        if (!$inventory) {
            return redirect()->route('inventories.index')->with('error', 'Inventaire non trouvé.');
        }
        
        return view('inventories.edit', compact('inventory'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'reference' => 'required|string|max:255',
            'performed_at' => 'required|date',
            'note' => 'nullable|string',
        ]);

        $inventories = session()->get('inventories', []);
        
        foreach ($inventories as $inventory) {
            if ($inventory->id == $id) {
                $inventory->reference = $request->reference;
                $inventory->performed_at = \Carbon\Carbon::parse($request->performed_at);
                $inventory->note = $request->note;
                
                // Vérifier si l'inventaire doit être archivé
                if ($request->has('archive')) {
                    $inventory->status = 'archived';
                    $inventory->archived_at = now();
                }
                
                break;
            }
        }
        
        session()->put('inventories', $inventories);
        
        // Message et redirection appropriés
        $message = $request->has('archive') 
            ? 'Inventaire modifié et archivé avec succès.' 
            : 'Inventaire modifié avec succès.';
            
        $filter = $request->has('archive') ? ['filter' => 'archived'] : [];
        
        return redirect()->route('inventories.index', $filter)->with('success', $message);
    }

    public function archive($id)
    {
        // Debug
        \Log::info('Tentative d\'archivage', ['id' => $id]);
        
        $inventories = session()->get('inventories', []);
        \Log::info('Inventaires avant archivage', ['count' => count($inventories)]);
        
        foreach ($inventories as $inventory) {
            if ($inventory->id == $id) {
                $inventory->status = 'archived';
                $inventory->archived_at = now();
                \Log::info('Inventaire trouvé et archivé', ['id' => $id, 'reference' => $inventory->reference]);
                break;
            }
        }
        
        session()->put('inventories', $inventories);
        \Log::info('Inventaires après archivage', ['count' => count($inventories)]);
        
        return redirect()->route('inventories.index')->with('success', 'Inventaire archivé avec succès.');
    }

    public function restore($id)
    {
        $inventories = session()->get('inventories', []);
        
        foreach ($inventories as $inventory) {
            if ($inventory->id == $id) {
                $inventory->status = 'completed';
                unset($inventory->archived_at);
                break;
            }
        }
        
        session()->put('inventories', $inventories);
        
        return redirect()->route('inventories.index')->with('success', 'Inventaire restauré avec succès.');
    }

    public function close($id)
    {
        $inventories = session()->get('inventories', []);
        
        foreach ($inventories as $inventory) {
            if ($inventory->id == $id) {
                $inventory->status = 'completed';
                $inventory->closed_at = now();
                break;
            }
        }
        
        session()->put('inventories', $inventories);
        
        return redirect()->route('inventories.index')->with('success', 'Inventaire fermé avec succès.');
    }

    public function destroy($id)
    {
        $inventories = session()->get('inventories', []);
        
        // Filtrer pour supprimer l'inventaire avec l'ID correspondant
        $originalCount = count($inventories);
        $inventories = array_filter($inventories, function($inventory) use ($id) {
            return $inventory->id != $id;
        });
        
        // Réindexer le tableau
        $inventories = array_values($inventories);
        
        // Sauvegarder en session
        session()->put('inventories', $inventories);
        
        $message = $originalCount > count($inventories) 
            ? 'Inventaire supprimé avec succès.' 
            : 'Inventaire non trouvé.';
            
        return redirect()->route('inventories.index')->with('success', $message);
    }
}
