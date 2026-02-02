@extends('layouts.app')

@section('title', 'Produits - Design Spectaculaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900">
    <!-- Header époustouflant -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/30 via-purple-600/30 to-pink-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-indigo-400 via-purple-500 to-pink-600 bg-clip-text text-transparent animate-gradient">
                            Gestion des Produits
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Catalogue complet avec recherche intelligente</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('products.create') }}" class="group bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-green-500/50">
                        <i class="fas fa-plus-circle mr-3 group-hover:rotate-90 transition-transform duration-300"></i>
                        Nouveau Produit
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-4 h-4 bg-indigo-400 rounded-full animate-bounce"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-purple-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-10 left-1/4 w-3 h-3 bg-pink-400 rounded-full animate-pulse"></div>
            <div class="absolute top-1/3 right-1/4 w-5 h-5 bg-cyan-400 rounded-full animate-bounce animation-delay-2000"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Filtres spectaculaires -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 mb-8 border border-white/20">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div class="relative">
                    <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Recherche</label>
                    <div class="relative">
                        <input type="text" name="q" value="{{ request('q') }}" 
                               class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white placeholder-white/50 focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300"
                               placeholder="🔍 Rechercher un produit...">
                        <div class="absolute right-4 top-1/2 transform -translate-y-1/2">
                            <i class="fas fa-search text-white/50"></i>
                        </div>
                    </div>
                </div>

                <div>
                    <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Catégorie</label>
                    <select name="category_id" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories ?? [] as $category)
                            <option value="{{ $category->id }}" {{ request('category_id') == $category->id ? 'selected' : '' }}>{{ $category->name }}</option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Statut Stock</label>
                    <select name="stock_status" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                        <option value="">Tous</option>
                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>🔴 Stock faible</option>
                        <option value="normal" {{ request('stock_status') == 'normal' ? 'selected' : '' }}>🟢 Stock normal</option>
                        <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>⚫ Rupture</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-blue-600 to-purple-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-blue-500/50">
                        <i class="fas fa-filter mr-3"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Grille de produits spectaculaire -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8 mb-12">
            @forelse($products as $product)
            <div class="group relative">
                <!-- Fond animé -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 via-purple-600/20 to-pink-600/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                
                <!-- Carte produit -->
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl overflow-hidden border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <!-- Image placeholder -->
                    <div class="h-48 bg-gradient-to-br from-indigo-500/20 to-purple-500/20 relative overflow-hidden">
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-24 h-24 bg-white/10 backdrop-blur-md rounded-2xl flex items-center justify-center">
                                <i class="fas fa-box text-4xl text-white/60"></i>
                            </div>
                        </div>
                        <!-- Badge stock -->
                        <div class="absolute top-4 right-4">
                            @if($product->current_stock <= ($product->stock_min ?? 10))
                                <div class="bg-red-500 text-white px-4 py-2 rounded-full text-xs font-bold animate-pulse">
                                    <i class="fas fa-exclamation-triangle mr-2"></i>Stock faible
                                </div>
                            @else
                                <div class="bg-green-500 text-white px-4 py-2 rounded-full text-xs font-bold">
                                    <i class="fas fa-check-circle mr-2"></i>Disponible
                                </div>
                            @endif
                        </div>
                    </div>

                    <!-- Contenu -->
                    <div class="p-6">
                        <h3 class="text-xl font-black text-white mb-2 group-hover:text-purple-300 transition-colors duration-300">
                            {{ $product->name }}
                        </h3>
                        
                        <div class="text-gray-400 text-sm mb-4">
                            {{ $product->category->name ?? 'Non catégorisé' }}
                        </div>

                        <!-- Informations -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <div class="bg-white/5 rounded-2xl p-3">
                                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Stock Actuel</p>
                                <p class="text-2xl font-black text-white">{{ $product->current_stock }}</p>
                            </div>
                            <div class="bg-white/5 rounded-2xl p-3">
                                <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Prix</p>
                                <p class="text-2xl font-black text-green-400">{{ number_format($product->price, 2) }}€</p>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="flex space-x-3">
                            <a href="{{ route('products.show', $product->id) }}" 
                               class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-3 rounded-2xl font-semibold text-center hover:scale-105 transition-all duration-300 hover:shadow-blue-500/50">
                                <i class="fas fa-eye mr-2"></i>Voir
                            </a>
                            <a href="{{ route('products.edit', $product->id) }}" 
                               class="flex-1 bg-gradient-to-r from-purple-500 to-purple-600 text-white px-4 py-3 rounded-2xl font-semibold text-center hover:scale-105 transition-all duration-300 hover:shadow-purple-500/50">
                                <i class="fas fa-edit mr-2"></i>Modifier
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20">
                <div class="w-32 h-32 mx-auto mb-6 relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-600 to-gray-700 rounded-full animate-pulse"></div>
                    <div class="absolute inset-2 bg-slate-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-box-open text-4xl text-gray-400"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-white mb-4">Aucun produit trouvé</h3>
                <p class="text-gray-400 text-lg mb-8">Essayez de modifier vos filtres ou ajoutez un nouveau produit</p>
                <a href="{{ route('products.create') }}" class="inline-block bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-green-500/50">
                    <i class="fas fa-plus-circle mr-3"></i>Ajouter le premier produit
                </a>
            </div>
            @endforelse
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

.animation-delay-2000 {
    animation-delay: 2s;
}
</style>
@endsection
