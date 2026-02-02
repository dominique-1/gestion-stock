@extends('layouts.app')

@section('title', 'Mouvement du ' . $movement->moved_at->format('d/m H:i'))

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center">
        <a href="{{ route('movements.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 mr-4">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
        <h1 class="text-2xl font-bold">Mouvement du {{ $movement->moved_at->format('d/m H:i') }}</h1>
    </div>
</div>

<div class="bg-white p-6 rounded shadow">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><strong>Produit :</strong> <a href="{{ route('products.show', $movement->product->id) }}" class="text-blue-600 hover:underline">{{ $movement->product->name }}</a></div>
        <div><strong>Type :</strong> <span class="px-2 py-1 text-xs rounded {{ $movement->type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">{{ $movement->type === 'in' ? 'Entrée' : 'Sortie' }}</span></div>
        <div><strong>Raison :</strong> {{ $movement->reason }}</div>
        <div><strong>Quantité :</strong> <span class="{{ $movement->type === 'in' ? 'text-green-600' : 'text-red-600' }}">{{ $movement->type === 'in' ? '+' : '-' }}{{ $movement->quantity }}</span></div>
        <div><strong>Date/Heure :</strong> {{ $movement->moved_at->format('d/m/Y H:i') }}</div>
        <div><strong>Utilisateur :</strong> {{ $movement->user->name }}</div>
        @if($movement->note)
            <div class="md:col-span-2"><strong>Note :</strong> {{ $movement->note }}</div>
        @endif
    </div>
</div>
@endsection
