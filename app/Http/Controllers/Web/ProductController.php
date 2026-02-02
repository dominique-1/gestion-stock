<?php

namespace App\Http\Controllers\Web;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductDocument;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with('category');

        // Filtre par recherche
        if ($request->filled('q')) {
            $searchTerm = $request->q;
            $query->where(function($q) use ($searchTerm) {
                $q->where('name', 'like', "%{$searchTerm}%")
                  ->orWhere('barcode', 'like', "%{$searchTerm}%")
                  ->orWhere('supplier', 'like', "%{$searchTerm}%");
            });
        }

        // Filtre par catégorie
        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        // Filtre par statut de stock
        if ($request->filled('stock_status')) {
            switch ($request->stock_status) {
                case 'low':
                    $query->lowStock();
                    break;
                case 'normal':
                    $query->where('current_stock', '>', 'stock_min');
                    break;
                case 'out':
                    $query->where('current_stock', '<=', 0);
                    break;
            }
        }

        $products = $query->get();
        $categories = Category::all();

        return view('products.index', compact('products', 'categories'));
    }

    public function create()
    {
        $categories = Category::all();
        return view('products.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'stock_min' => 'required|integer|min:0',
            'stock_optimal' => 'required|integer|min:2',
            'current_stock' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $product = Product::create($request->all());

        // Gestion des documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $path = $file->store('product_documents', 'public');
                
                ProductDocument::create([
                    'product_id' => $product->id,
                    'filename' => $file->hashName(),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'file_path' => $path,
                    'document_type' => $request->document_types[$index] ?? 'fiche_technique',
                    'description' => $request->document_descriptions[$index] ?? null,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produit créé avec succès.');
    }

    public function show($id)
    {
        $product = Product::with(['category', 'stockMovements', 'alerts', 'documents'])->findOrFail($id);
        return view('products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with('documents')->findOrFail($id);
        $categories = Category::all();
        return view('products.edit', compact('product', 'categories'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'barcode' => 'nullable|string|max:255',
            'supplier' => 'nullable|string|max:255',
            'stock_min' => 'required|integer|min:0',
            'stock_optimal' => 'required|integer|min:2',
            'current_stock' => 'required|integer|min:0',
            'price' => 'nullable|numeric|min:0',
            'category_id' => 'nullable|exists:categories,id',
        ]);

        $product = Product::findOrFail($id);
        $product->update($request->all());

        // Gestion des nouveaux documents
        if ($request->hasFile('documents')) {
            foreach ($request->file('documents') as $index => $file) {
                $path = $file->store('product_documents', 'public');
                
                ProductDocument::create([
                    'product_id' => $product->id,
                    'filename' => $file->hashName(),
                    'original_name' => $file->getClientOriginalName(),
                    'mime_type' => $file->getMimeType(),
                    'file_size' => $file->getSize(),
                    'file_path' => $path,
                    'document_type' => $request->document_types[$index] ?? 'fiche_technique',
                    'description' => $request->document_descriptions[$index] ?? null,
                ]);
            }
        }

        return redirect()->route('products.index')->with('success', 'Produit mis à jour avec succès.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        
        // Supprimer les documents associés
        foreach ($product->documents as $document) {
            Storage::disk('public')->delete($document->file_path);
            $document->delete();
        }
        
        $product->delete();
        
        return redirect()->route('products.index')->with('success', 'Produit supprimé avec succès.');
    }

    public function downloadDocument($productId, $documentId)
    {
        $product = Product::findOrFail($productId);
        $document = $product->documents()->findOrFail($documentId);
        
        return Storage::disk('public')->download($document->file_path, $document->original_name);
    }

    public function deleteDocument($productId, $documentId)
    {
        $product = Product::findOrFail($productId);
        $document = $product->documents()->findOrFail($documentId);
        
        Storage::disk('public')->delete($document->file_path);
        $document->delete();
        
        return redirect()->back()->with('success', 'Document supprimé avec succès.');
    }
}
