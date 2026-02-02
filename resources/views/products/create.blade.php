@extends('layouts.app')

@section('title', 'Ajouter un produit')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Ajouter un produit</h1>
    <a href="{{ route('products.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Retour
    </a>
</div>

<form method="POST" action="{{ route('products.store') }}" enctype="multipart/form-data" class="bg-white p-6 rounded shadow">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nom *</label>
            <input type="text" name="name" id="name" class="w-full px-3 py-2 border rounded" required value="{{ old('name') }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="barcode">Code-barres</label>
            <input type="text" name="barcode" id="barcode" class="w-full px-3 py-2 border rounded" value="{{ old('barcode') }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="category_id">Catégorie</label>
            <select name="category_id" id="category_id" class="w-full px-3 py-2 border rounded">
                <option value="">Aucune</option>
                @foreach($categories as $category)
                <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="supplier">Fournisseur</label>
            <input type="text" name="supplier" id="supplier" class="w-full px-3 py-2 border rounded" value="{{ old('supplier') }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="price">Prix unitaire (€)</label>
            <input type="number" step="0.01" name="price" id="price" class="w-full px-3 py-2 border rounded" value="{{ old('price') }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="expires_at">Date d'expiration</label>
            <input type="date" name="expires_at" id="expires_at" class="w-full px-3 py-2 border rounded" value="{{ old('expires_at') }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="stock_min">Stock minimum *</label>
            <input type="number" name="stock_min" id="stock_min" class="w-full px-3 py-2 border rounded" required value="{{ old('stock_min') ?? 0 }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="stock_optimal">Stock optimal *</label>
            <input type="number" name="stock_optimal" id="stock_optimal" class="w-full px-3 py-2 border rounded" required min="2" value="{{ old('stock_optimal') ?? 0 }}">
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="current_stock">Stock actuel *</label>
            <input type="number" name="current_stock" id="current_stock" class="w-full px-3 py-2 border rounded" required value="{{ old('current_stock') ?? 0 }}">
        </div>
    </div>
    <div class="mt-6">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
        <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border rounded">{{ old('description') }}</textarea>
    </div>

    <!-- Section Documents -->
    <div class="mt-8 border-t pt-6">
        <h3 class="text-lg font-semibold mb-4">Documents joints</h3>
        <div id="documents-container">
            <div class="document-row border rounded p-4 mb-4">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Fichier</label>
                        <input type="file" name="documents[]" class="w-full px-3 py-2 border rounded" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Type de document</label>
                        <select name="document_types[]" class="w-full px-3 py-2 border rounded">
                            <option value="fiche_technique">Fiche technique</option>
                            <option value="manuel">Manuel d'utilisation</option>
                            <option value="certificat">Certificat</option>
                            <option value="autre">Autre</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                        <input type="text" name="document_descriptions[]" class="w-full px-3 py-2 border rounded" placeholder="Optionnel">
                    </div>
                </div>
            </div>
        </div>
        <button type="button" onclick="addDocumentRow()" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700">
            <i class="fas fa-plus mr-2"></i>Ajouter un document
        </button>
    </div>

    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('products.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Annuler</a>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer</button>
    </div>
</form>

<script>
function addDocumentRow() {
    const container = document.getElementById('documents-container');
    const newRow = document.createElement('div');
    newRow.className = 'document-row border rounded p-4 mb-4';
    newRow.innerHTML = `
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Fichier</label>
                <input type="file" name="documents[]" class="w-full px-3 py-2 border rounded" accept=".pdf,.doc,.docx,.xls,.xlsx,.ppt,.pptx,.jpg,.jpeg,.png">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2">Type de document</label>
                <select name="document_types[]" class="w-full px-3 py-2 border rounded">
                    <option value="fiche_technique">Fiche technique</option>
                    <option value="manuel">Manuel d'utilisation</option>
                    <option value="certificat">Certificat</option>
                    <option value="autre">Autre</option>
                </select>
            </div>
            <div class="flex items-end">
                <div class="flex-1">
                    <label class="block text-gray-700 text-sm font-bold mb-2">Description</label>
                    <input type="text" name="document_descriptions[]" class="w-full px-3 py-2 border rounded" placeholder="Optionnel">
                </div>
                <button type="button" onclick="removeDocumentRow(this)" class="ml-2 bg-red-600 text-white px-3 py-2 rounded hover:bg-red-700">
                    <i class="fas fa-trash"></i>
                </button>
            </div>
        </div>
    `;
    container.appendChild(newRow);
}

function removeDocumentRow(button) {
    button.closest('.document-row').remove();
}
</script>
@endsection
