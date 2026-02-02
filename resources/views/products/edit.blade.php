@extends('layouts.app')

@section('title', 'Modifier : ' . $product->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center">
        <a href="{{ route('products.show', $product->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 mr-4">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
        <h1 class="text-2xl font-bold">Modifier : {{ $product->name }}</h1>
    </div>
</div>

<form method="POST" action="{{ route('products.update', $product->id) }}" class="bg-white p-6 rounded shadow">
    @csrf
    @method('PUT')
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nom *</label>
            <input type="text" name="name" id="name" class="w-full px-3 py-2 border rounded" required value="{{ old('name', $product->name) }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="barcode">Code-barres</label>
            <input type="text" name="barcode" id="barcode" class="w-full px-3 py-2 border rounded" value="{{ old('barcode', $product->barcode) }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">Catégorie</label>
            <select name="category_id" id="category_id" class="w-full px-3 py-2 border rounded">
                <option value="">Aucune</option>
                <option value="1" {{ old('category_id', $product->category_id) == '1' ? 'selected' : '' }}>Informatique</option>
                <option value="2" {{ old('category_id', $product->category_id) == '2' ? 'selected' : '' }}>Bureau</option>
                <option value="3" {{ old('category_id', $product->category_id) == '3' ? 'selected' : '' }}>Logiciel</option>
                <option value="4" {{ old('category_id', $product->category_id) == '4' ? 'selected' : '' }}>Matériel</option>
            </select>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="supplier">Fournisseur</label>
            <input type="text" name="supplier" id="supplier" class="w-full px-3 py-2 border rounded" value="{{ old('supplier', $product->supplier) }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="price">Prix unitaire (€)</label>
            <input type="number" step="0.01" name="price" id="price" class="w-full px-3 py-2 border rounded" value="{{ old('price', $product->price) }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="expires_at">Date d'expiration</label>
            <input type="date" name="expires_at" id="expires_at" class="w-full px-3 py-2 border rounded" value="{{ old('expires_at', $product->expires_at?->format('Y-m-d')) }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="stock_min">Stock minimum *</label>
            <input type="number" name="stock_min" id="stock_min" class="w-full px-3 py-2 border rounded" required value="{{ old('stock_min', $product->stock_min) }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="stock_optimal">Stock optimal *</label>
            <input type="number" name="stock_optimal" id="stock_optimal" class="w-full px-3 py-2 border rounded" required min="2" value="{{ old('stock_optimal', $product->stock_optimal) }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="current_stock">Stock actuel *</label>
            <input type="number" name="current_stock" id="current_stock" class="w-full px-3 py-2 border rounded" required value="{{ old('current_stock', $product->current_stock) }}">
        </div>
    </div>
    <div class="mt-6">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
        <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border rounded">{{ old('description', $product->description) }}</textarea>
    </div>
    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('products.show', $product->id) }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Annuler</a>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Mettre à jour</button>
    </div>
</form>
@endsection
