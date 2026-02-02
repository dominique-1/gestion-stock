@extends('layouts.app')

@section('title', 'Catégories - Design Spectaculaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-emerald-900 via-teal-900 to-cyan-900">
    <!-- Header spectaculaire -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-emerald-600/30 via-teal-600/30 to-cyan-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-emerald-400 via-teal-500 to-cyan-600 bg-clip-text text-transparent animate-gradient">
                            Catégories
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Organisation hiérarchique des produits</p>
                </div>
                <div class="flex items-center space-x-4">
                    @if(session('success'))
                    <div class="bg-green-500/20 backdrop-blur-md border border-green-500/30 text-green-300 px-6 py-3 rounded-2xl">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                    @endif
                    @if(session('error'))
                    <div class="bg-red-500/20 backdrop-blur-md border border-red-500/30 text-red-300 px-6 py-3 rounded-2xl">
                        <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('error') }}
                    </div>
                    @endif
                    <a href="{{ route('categories.create') }}" class="group bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-emerald-500/50">
                        <i class="fas fa-folder-plus mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
                        Nouvelle Catégorie
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Éléments décoratifs -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-4 h-4 bg-emerald-400 rounded-full animate-bounce"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-teal-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-10 left-1/4 w-3 h-3 bg-cyan-400 rounded-full animate-pulse"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Vue arborescente spectaculaire -->
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-8 mb-12">
            <!-- Catégories principales -->
            <div class="lg:col-span-2">
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                    <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                        <div class="w-3 h-3 bg-gradient-to-r from-emerald-400 to-teal-400 rounded-full mr-3 animate-pulse"></div>
                        Structure Hiérarchique
                    </h3>
                    
                    <div class="space-y-4">
                        @forelse($categories as $category)
                        <div class="group">
                            <div class="bg-gradient-to-r from-emerald-500/20 to-teal-500/20 rounded-2xl p-6 hover:from-emerald-500/30 hover:to-teal-500/30 transition-all duration-300 border border-emerald-500/30 hover:border-emerald-500/50">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center space-x-4">
                                        <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center shadow-lg">
                                            <i class="fas fa-folder text-white text-xl"></i>
                                        </div>
                                        <div>
                                            <h4 class="text-xl font-black text-white group-hover:text-emerald-300 transition-colors duration-300">
                                                {{ $category->name }}
                                            </h4>
                                            @if($category->description)
                                            <p class="text-gray-400 text-sm">{{ Str::limit($category->description, 60) }}</p>
                                            @endif
                                        </div>
                                    </div>
                                    <div class="flex items-center space-x-3">
                                        <!-- Nombre de produits -->
                                        <div class="bg-white/10 backdrop-blur-md rounded-xl px-4 py-2">
                                            <span class="text-emerald-400 font-bold">{{ $category->products_count ?? 0 }}</span>
                                            <span class="text-gray-400 text-sm ml-1">produits</span>
                                        </div>
                                        
                                        <!-- Actions -->
                                        <div class="flex space-x-2">
                                            <a href="{{ route('categories.edit', $category->id) }}" 
                                               class="w-10 h-10 bg-blue-500/20 hover:bg-blue-500/30 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                                <i class="fas fa-edit text-blue-400"></i>
                                            </a>
                                            <form method="POST" action="{{ route('categories.destroy', $category->id) }}" class="inline" onsubmit="return confirm('Supprimer cette catégorie ?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="w-10 h-10 bg-red-500/20 hover:bg-red-500/30 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                                    <i class="fas fa-trash text-red-400"></i>
                                                </button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                                
                                <!-- Sous-catégories -->
                                @if(isset($category->children) && $category->children->count() > 0)
                                <div class="ml-8 mt-4 space-y-3">
                                    @foreach($category->children as $child)
                                    <div class="bg-white/5 rounded-xl p-4 hover:bg-white/10 transition-all duration-300 border border-white/10 hover:border-white/20">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="w-8 h-8 bg-teal-500/20 rounded-lg flex items-center justify-center">
                                                    <i class="fas fa-folder-open text-teal-400 text-sm"></i>
                                                </div>
                                                <div>
                                                    <h5 class="text-white font-semibold">{{ $child->name }}</h5>
                                                    <p class="text-gray-500 text-xs">{{ $child->products_count ?? 0 }} produits</p>
                                                </div>
                                            </div>
                                            <div class="flex space-x-2">
                                                <a href="{{ route('categories.edit', $child->id) }}" class="text-blue-400 hover:text-blue-300">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form method="POST" action="{{ route('categories.destroy', $child->id) }}" class="inline" onsubmit="return confirm('Supprimer cette sous-catégorie ?')">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit" class="text-red-400 hover:text-red-300">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @endif
                            </div>
                        </div>
                        @empty
                        <div class="text-center py-12">
                            <div class="w-24 h-24 mx-auto mb-6 relative">
                                <div class="absolute inset-0 bg-gradient-to-r from-gray-600 to-gray-700 rounded-full animate-pulse"></div>
                                <div class="absolute inset-2 bg-slate-900 rounded-full flex items-center justify-center">
                                    <i class="fas fa-folder-open text-3xl text-gray-400"></i>
                                </div>
                            </div>
                            <h3 class="text-2xl font-black text-white mb-4">Aucune catégorie</h3>
                            <p class="text-gray-400 mb-6">Commencez par créer votre première catégorie</p>
                            <a href="{{ route('categories.create') }}" class="inline-block bg-gradient-to-r from-emerald-500 to-teal-600 text-white px-6 py-3 rounded-2xl font-bold hover:scale-105 transition-all duration-300">
                                <i class="fas fa-folder-plus mr-2"></i>Créer une catégorie
                            </a>
                        </div>
                        @endforelse
                    </div>
                </div>
            </div>

            <!-- Statistiques -->
            <div class="space-y-6">
                <!-- Carte statistique -->
                <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                    <h3 class="text-xl font-bold text-white mb-6">Statistiques</h3>
                    
                    <div class="space-y-6">
                        <!-- Total catégories -->
                        <div class="bg-gradient-to-r from-emerald-500/20 to-teal-500/20 rounded-2xl p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-emerald-400 text-sm font-bold uppercase tracking-wider mb-1">Total Catégories</p>
                                    <p class="text-3xl font-black text-white">{{ $categories->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-gradient-to-br from-emerald-500 to-teal-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-folder-tree text-white"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Catégories avec produits -->
                        <div class="bg-gradient-to-r from-blue-500/20 to-cyan-500/20 rounded-2xl p-6">
                            <div class="flex items-center justify-between">
                                <div>
                                    <p class="text-blue-400 text-sm font-bold uppercase tracking-wider mb-1">Avec Produits</p>
                                    <p class="text-3xl font-black text-white">{{ $categories->where('products_count', '>', 0)->count() }}</p>
                                </div>
                                <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center">
                                    <i class="fas fa-box text-white"></i>
                                </div>
                            </div>
                        </div>

                        <!-- Distribution -->
                        <div class="bg-white/5 rounded-2xl p-6">
                            <p class="text-gray-400 text-sm font-bold uppercase tracking-wider mb-4">Top Catégories</p>
                            <div class="space-y-3">
                                @foreach($categories->sortByDesc('products_count')->take(3) as $topCategory)
                                <div class="flex items-center justify-between">
                                    <span class="text-white text-sm">{{ $topCategory->name }}</span>
                                    <div class="flex items-center space-x-2">
                                        <div class="w-24 h-2 bg-white/10 rounded-full overflow-hidden">
                                            <div class="h-full bg-gradient-to-r from-emerald-400 to-teal-400 rounded-full" style="width: {{ $topCategory->products_count ? min(100, ($topCategory->products_count / 10) * 100) : 0 }}%"></div>
                                        </div>
                                        <span class="text-emerald-400 text-sm font-bold">{{ $topCategory->products_count ?? 0 }}</span>
                                    </div>
                                </div>
                                @endforeach
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Actions rapides -->
                <div class="bg-gradient-to-r from-purple-600/20 to-pink-600/20 rounded-3xl p-8 border border-white/20">
                    <h3 class="text-xl font-bold text-white mb-6">Actions Rapides</h3>
                    <div class="space-y-4">
                        <a href="{{ route('categories.create') }}" class="w-full bg-white/10 backdrop-blur-xl rounded-2xl p-4 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20 flex items-center">
                            <i class="fas fa-plus-circle text-purple-400 text-xl mr-4"></i>
                            <span class="text-white font-semibold">Nouvelle catégorie</span>
                        </a>
                        <a href="{{ route('products.create') }}" class="w-full bg-white/10 backdrop-blur-xl rounded-2xl p-4 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20 flex items-center">
                            <i class="fas fa-box text-blue-400 text-xl mr-4"></i>
                            <span class="text-white font-semibold">Ajouter un produit</span>
                        </a>
                        <a href="{{ route('dashboard') }}" class="w-full bg-white/10 backdrop-blur-xl rounded-2xl p-4 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20 flex items-center">
                            <i class="fas fa-chart-line text-green-400 text-xl mr-4"></i>
                            <span class="text-white font-semibold">Tableau de bord</span>
                        </a>
                    </div>
                </div>
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
</style>
@endsection
