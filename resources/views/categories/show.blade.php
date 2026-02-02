@extends('layouts.app')

@section('title', $category->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center">
        <a href="{{ route('categories.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 mr-4">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
        <h1 class="text-2xl font-bold">{{ $category->name }}</h1>
    </div>
    <div>
        <a href="{{ route('categories.edit', $category->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Modifier</a>
        <form method="POST" action="{{ route('categories.destroy', $category->id) }}" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 ml-2" onclick="return confirm('Supprimer ?')">Supprimer</button>
        </form>
    </div>
</div>

<div class="bg-white p-6 rounded shadow mb-6">
    <h2 class="text-xl font-bold mb-4">Informations</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><strong>Nom :</strong> {{ $category->name }}</div>
        <div><strong>Parent :</strong> {{ $category->parent_id ? 'Catégorie parente' : '-' }}</div>
        <div><strong>Nombre de produits :</strong> {{ $category->products_count ?? 0 }}</div>
        <div><strong>Date de création :</strong> {{ $category->created_at->format('d/m/Y') }}</div>
        <div class="md:col-span-2"><strong>Description :</strong> {{ $category->description ?? '-' }}</div>
    </div>
</div>

@if($category->children->count())
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-xl font-bold mb-4">Sous-catégories</h2>
        <ul class="list-disc list-inside">
            @foreach($category->children as $child)
                <li><a href="{{ route('categories.show', $child->id) }}" class="text-blue-600 hover:underline">{{ $child->name }}</a></li>
            @endforeach
        </ul>
    </div>
@endif

<div class="bg-white p-6 rounded shadow">
    <h2 class="text-xl font-bold mb-4">Produits ({{ $category->products->count() }})</h2>
    <ul class="space-y-2">
        @forelse($category->products as $product)
            <li class="flex justify-between">
                <a href="{{ route('products.show', $product->id) }}" class="text-blue-600 hover:underline">{{ $product->name }}</a>
                <span class="text-sm text-gray-500">Stock : {{ $product->current_stock }}</span>
            </li>
        @empty
            <li class="text-gray-500">Aucun produit</li>
        @endforelse
    </ul>
</div>
@endsection
