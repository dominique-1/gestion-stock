@extends('layouts.app')

@section('title', 'Créer un mouvement')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-green-900 via-teal-900 to-emerald-900">
    <!-- Header spectaculaire -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-green-600/30 via-teal-600/30 to-emerald-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-green-400 via-teal-500 to-emerald-600 bg-clip-text text-transparent animate-gradient">
                            Nouveau Mouvement
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Enregistrez les entrées et sorties de stock</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('movements.index') }}" class="bg-white/10 backdrop-blur-md text-white px-6 py-3 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-4 h-4 bg-green-400 rounded-full animate-bounce"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-teal-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-10 left-1/4 w-3 h-3 bg-emerald-400 rounded-full animate-pulse"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <div class="max-w-4xl mx-auto">
            <!-- Formulaire spectaculaire -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                <form method="POST" action="{{ route('movements.store') }}" class="space-y-6">
                    @csrf
                    
                    <!-- Messages d'erreur -->
                    @if ($errors->any())
                        <div class="bg-red-500/20 backdrop-blur-md border border-red-500/30 text-red-300 px-6 py-4 rounded-2xl mb-6">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span class="font-bold">Erreurs de validation</span>
                            </div>
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Message de succès -->
                    @if(session()->has('success'))
                        <div class="bg-green-500/20 backdrop-blur-md border border-green-500/30 text-green-300 px-6 py-4 rounded-2xl mb-6">
                            <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                        </div>
                    @endif

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Produit -->
                        <div>
                            <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Produit</label>
                            <select name="product_id" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300" required>
                                <option value="">Sélectionner un produit</option>
                                @foreach($products as $product)
                                    <option value="{{ $product->id }}" class="text-gray-900">{{ $product->name }} (Stock: {{ $product->current_stock ?? 0 }})</option>
                                @endforeach
                            </select>
                        </div>

                        <!-- Type de mouvement -->
                        <div>
                            <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Type de mouvement</label>
                            <div class="flex space-x-6">
                                <label class="flex items-center bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 cursor-pointer hover:bg-white/20 transition-all duration-300">
                                    <input type="radio" name="type" value="in" class="mr-3" required>
                                    <span class="text-white font-semibold">📥 Entrée</span>
                                </label>
                                <label class="flex items-center bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 cursor-pointer hover:bg-white/20 transition-all duration-300">
                                    <input type="radio" name="type" value="out" class="mr-3" required>
                                    <span class="text-white font-semibold">📤 Sortie</span>
                                </label>
                            </div>
                        </div>

                        <!-- Quantité -->
                        <div>
                            <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Quantité</label>
                            <input type="number" name="quantity" min="1" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white placeholder-white/50 focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300" placeholder="Entrez la quantité" required>
                        </div>

                        <!-- Date -->
                        <div>
                            <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Date du mouvement</label>
                            <input type="datetime-local" name="moved_at" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300" value="{{ now()->format('Y-m-d\TH:i') }}">
                        </div>
                    </div>

                    <!-- Motif -->
                    <div>
                        <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Motif</label>
                        <textarea name="reason" rows="3" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white placeholder-white/50 focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300" placeholder="Décrivez le motif du mouvement..."></textarea>
                    </div>

                    <!-- Notes -->
                    <div>
                        <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Notes (optionnel)</label>
                        <textarea name="note" rows="2" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white placeholder-white/50 focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300" placeholder="Informations complémentaires..."></textarea>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4 pt-6">
                        <a href="{{ route('movements.index') }}" class="bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>Annuler
                        </a>
                        <button type="submit" class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-green-500/50">
                            <i class="fas fa-save mr-3"></i>Enregistrer le mouvement
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<style>
@keyframes gradient {
    0%, 100% { background-position: 0% 50%; }
    50% { background-position: 100% 50%; }
}

.animate-gradient {
    background-size: 200% 200%;
    animation: gradient 3s ease infinite;
}

input[type="radio"]:checked + span {
    color: #10b981;
    font-weight: bold;
}
</style>
@endsection