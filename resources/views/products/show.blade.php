@extends('layouts.app')

@section('title', $product->name)

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center">
        <a href="{{ route('products.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 mr-4">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
        <h1 class="text-2xl font-bold">{{ $product->name }}</h1>
    </div>
    <div>
        <a href="{{ route('products.edit', $product->id) }}" class="bg-yellow-600 text-white px-4 py-2 rounded hover:bg-yellow-700">Modifier</a>
        <form method="POST" action="{{ route('products.destroy', $product->id) }}" class="inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700 ml-2" onclick="return confirm('Supprimer ?')">Supprimer</button>
        </form>
    </div>
</div>

<div class="bg-white p-6 rounded shadow mb-6">
    <h2 class="text-xl font-bold mb-4">Informations</h2>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><strong>Référence :</strong> {{ $product->barcode ?? '-' }}</div>
        <div><strong>Catégorie :</strong> {{ $product->category->name ?? '-' }}</div>
        <div><strong>Fournisseur :</strong> {{ $product->supplier ?? '-' }}</div>
        <div><strong>Prix unitaire :</strong> {{ number_format($product->price, 2) }} €</div>
        <div><strong>Stock actuel :</strong> {{ $product->current_stock }}</div>
        <div><strong>Stock minimum :</strong> {{ $product->stock_min }}</div>
        <div><strong>Stock optimal :</strong> {{ $product->stock_optimal }}</div>
        <div><strong>Valeur stock :</strong> {{ number_format($product->current_stock * $product->price, 2) }} €</div>
        <div><strong>Date d'expiration :</strong> {{ $product->expires_at?->format('d/m/Y') ?? '-' }}</div>
        <div class="md:col-span-2"><strong>Description :</strong> {{ $product->description ?? '-' }}</div>
    </div>
</div>

<div class="bg-white p-6 rounded shadow mb-6">
    <h2 class="text-xl font-bold mb-4">Mouvements récents</h2>
    <ul class="space-y-2">
        @forelse($product->stockMovements->take(10) as $m)
            <li class="flex justify-between">
                <span>{{ $m->type }} - {{ $m->reason }} ({{ $m->quantity }})</span>
                <span class="text-sm text-gray-500">{{ $m->moved_at->format('d/m H:i') }} par {{ $m->user->name }}</span>
            </li>
        @empty
            <li class="text-gray-500">Aucun mouvement</li>
        @endforelse
    </ul>
    <a href="{{ route('movements.index', ['product_id' => $product->id]) }}" class="mt-4 inline-block text-blue-600 hover:underline">Voir tout</a>
</div>

@if($product->alerts->count())
    <div class="bg-white p-6 rounded shadow">
        <h2 class="text-xl font-bold mb-4">Alertes</h2>
        <ul class="space-y-2">
            @foreach($product->alerts as $alert)
                <li class="flex justify-between">
                    <span>{{ $alert->message }}</span>
                    <span class="text-sm text-gray-500">{{ $alert->created_at->format('d/m H:i') }}</span>
                </li>
            @endforeach
        </ul>
    </div>
@endif
@endsection
