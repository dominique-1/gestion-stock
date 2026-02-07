<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Category;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::withCount('products')->get();
        return view('categories.index', compact('categories'));
    }

    public function create()
    {
        $parentOptions = Category::whereNull('parent_id')->pluck('name', 'id');
        return view('categories.create', compact('parentOptions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        Category::create($validated);

        return redirect()->route('categories.index')->with('success', 'Catégorie créée avec succès.');
    }

    public function show($id)
    {
        $category = Category::with(['parent', 'children', 'products'])->findOrFail($id);
        return view('categories.show', compact('category'));
    }

    public function edit($id)
    {
        $category = Category::findOrFail($id);
        $parentOptions = Category::whereNull('parent_id')->where('id', '!=', $id)->pluck('name', 'id');
        
        return view('categories.edit', compact('category', 'parentOptions'));
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);
        
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'parent_id' => 'nullable|exists:categories,id'
        ]);

        // Empêcher une catégorie de devenir sa propre parent
        if ($validated['parent_id'] == $id) {
            return redirect()->route('categories.edit', $id)
                ->with('error', 'Une catégorie ne peut pas être sa propre parent.')
                ->withInput();
        }

        $category->update($validated);

        return redirect()->route('categories.index')->with('success', 'Catégorie mise à jour avec succès.');
    }

    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        
        // Log pour diagnostic
        \Log::info('Tentative suppression catégorie: ' . $category->name);
        \Log::info('Sous-catégories: ' . $category->children()->count());
        \Log::info('Produits: ' . $category->products()->count());
        
        // Vérifier si la catégorie a des sous-catégories
        if ($category->children()->count() > 0) {
            \Log::info('Blocage: catégorie a des sous-catégories');
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des sous-catégories.')
                ->with('debug', 'Sous-catégories: ' . $category->children()->pluck('name')->join(', '));
        }

        // Vérifier si la catégorie a des produits
        if ($category->products()->count() > 0) {
            \Log::info('Blocage: catégorie a des produits');
            return redirect()->route('categories.index')
                ->with('error', 'Impossible de supprimer cette catégorie car elle contient des produits.')
                ->with('debug', 'Produits: ' . $category->products()->pluck('name')->join(', '));
        }

        $category->delete();
        \Log::info('Catégorie supprimée avec succès: ' . $category->name);

        return redirect()->route('categories.index')->with('success', 'Catégorie supprimée avec succès.');
    }
}
