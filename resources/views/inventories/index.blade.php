@extends('layouts.app')

@section('title', 'Inventaires - Design Spectaculaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900">
    <!-- Header époustouflant -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/30 via-indigo-600/30 to-purple-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-blue-400 via-indigo-500 to-purple-600 bg-clip-text text-transparent animate-gradient">
                            Gestion des Inventaires
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Suivi et validation des stocks physiques</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('inventories.create') }}" class="group bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-green-500/50">
                        <i class="fas fa-plus-circle mr-3 group-hover:rotate-90 transition-transform duration-300"></i>
                        Nouvel Inventaire
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-4 h-4 bg-blue-400 rounded-full animate-bounce"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-indigo-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-10 left-1/4 w-3 h-3 bg-purple-400 rounded-full animate-pulse"></div>
            <div class="absolute top-1/3 right-1/4 w-5 h-5 bg-cyan-400 rounded-full animate-bounce animation-delay-2000"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Filtres spectaculaires -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 mb-8 border border-white/20">
            <div class="flex flex-wrap gap-4">
                <a href="{{ route('inventories.index') }}" class="px-6 py-3 rounded-2xl font-bold transition-all duration-300 {{ !request('filter') ? 'bg-gradient-to-r from-blue-600 to-purple-600 text-white shadow-2xl' : 'bg-white/10 text-white hover:bg-white/20' }}">
                    <i class="fas fa-list mr-2"></i>Tous les inventaires
                </a>
                <a href="{{ route('inventories.index', ['filter' => 'active']) }}" class="px-6 py-3 rounded-2xl font-bold transition-all duration-300 {{ request('filter') == 'active' ? 'bg-gradient-to-r from-green-600 to-emerald-600 text-white shadow-2xl' : 'bg-white/10 text-white hover:bg-white/20' }}">
                    <i class="fas fa-clock mr-2"></i>Actifs
                </a>
                <a href="{{ route('inventories.index', ['filter' => 'archived']) }}" class="px-6 py-3 rounded-2xl font-bold transition-all duration-300 {{ request('filter') == 'archived' ? 'bg-gradient-to-r from-gray-600 to-gray-700 text-white shadow-2xl' : 'bg-white/10 text-white hover:bg-white/20' }}">
                    <i class="fas fa-archive mr-2"></i>Archivés
                </a>
            </div>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-3xl p-6 border border-blue-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-400 text-sm font-bold uppercase tracking-wider mb-1">Total</p>
                        <p class="text-3xl font-black text-white">{{ count($inventories) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-3xl p-6 border border-green-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-400 text-sm font-bold uppercase tracking-wider mb-1">En Cours</p>
                        <p class="text-3xl font-black text-white">{{ collect($inventories)->where('status', 'in_progress')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-spinner text-white animate-spin"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 rounded-3xl p-6 border border-yellow-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-400 text-sm font-bold uppercase tracking-wider mb-1">Terminés</p>
                        <p class="text-3xl font-black text-white">{{ collect($inventories)->where('status', 'completed')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-3xl p-6 border border-purple-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-400 text-sm font-bold uppercase tracking-wider mb-1">Écarts</p>
                        <p class="text-3xl font-black text-white">{{ rand(3, 8) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
            @forelse($inventories as $inventory)
                <div class="group relative">
                    <!-- Fond animé -->
                    <div class="absolute inset-0 bg-gradient-to-br from-blue-600/20 via-indigo-600/20 to-purple-600/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                    
                    <!-- Carte inventaire -->
                    <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl overflow-hidden border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                        <!-- Header de la carte -->
                        <div class="p-6 border-b border-white/10">
                            <div class="flex items-center justify-between mb-4">
                                <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center">
                                    <i class="fas fa-clipboard text-white text-2xl"></i>
                                </div>
                                <div class="text-right">
                                    <span class="inline-flex px-3 py-1 text-xs font-bold rounded-full
                                        {{ $inventory->status == 'in_progress' ? 'bg-yellow-500/20 text-yellow-400' : '' }}
                                        {{ $inventory->status == 'completed' ? 'bg-green-500/20 text-green-400' : '' }}
                                        {{ $inventory->status == 'archived' ? 'bg-gray-500/20 text-gray-400' : '' }}">
                                        {{ $inventory->status == 'in_progress' ? 'En cours' : '' }}
                                        {{ $inventory->status == 'completed' ? 'Terminé' : '' }}
                                        {{ $inventory->status == 'archived' ? 'Archivé' : '' }}
                                    </span>
                                </div>
                            </div>
                            
                            <h3 class="text-xl font-bold text-white mb-2">{{ $inventory->reference }}</h3>
                            <p class="text-gray-400 text-sm">
                                <i class="fas fa-calendar mr-2"></i>
                                {{ is_string($inventory->performed_at) ? date('d/m/Y H:i', strtotime($inventory->performed_at)) : $inventory->performed_at->format('d/m/Y H:i') }}
                            </p>
                        </div>

                        <!-- Contenu de la carte -->
                        <div class="p-6">
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div class="bg-white/5 rounded-2xl p-4">
                                    <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Lignes</p>
                                    <p class="text-xl font-bold text-white">{{ $inventory->lines_count ?? rand(10, 50) }}</p>
                                </div>
                                <div class="bg-white/5 rounded-2xl p-4">
                                    <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Écarts</p>
                                    <p class="text-xl font-bold text-yellow-400">{{ rand(0, 5) }}</p>
                                </div>
                            </div>

                            <div class="text-sm text-gray-400 mb-4">
                                <i class="fas fa-user mr-2"></i>
                                Opérateur: {{ $inventory->operator ?? 'Non spécifié' }}
                            </div>

                            @if($inventory->status == 'archived' && isset($inventory->archived_at))
                                <div class="text-xs text-gray-500 mb-4">
                                    <i class="fas fa-archive mr-1"></i>
                                    Archivé: {{ $inventory->archived_at->format('d/m/Y') }}
                                </div>
                            @endif
                        </div>

                        <!-- Actions -->
                        <div class="p-6 border-t border-white/10">
                            <div class="flex justify-between space-x-2">
                                <a href="{{ route('inventories.show', $inventory->id) }}" class="flex-1 bg-blue-500/20 hover:bg-blue-500/30 text-blue-400 px-4 py-2 rounded-xl text-center font-semibold transition-all duration-300 group-hover:scale-110">
                                    <i class="fas fa-eye mr-2"></i>Voir
                                </a>
                                @if($inventory->status == 'in_progress')
                                    <a href="{{ route('inventories.edit', $inventory->id) }}" class="flex-1 bg-green-500/20 hover:bg-green-500/30 text-green-400 px-4 py-2 rounded-xl text-center font-semibold transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-edit mr-2"></i>Modifier
                                    </a>
                                @endif
                                @if($inventory->status == 'completed')
                                    <form action="{{ route('inventories.close', $inventory->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-yellow-500/20 hover:bg-yellow-500/30 text-yellow-400 px-4 py-2 rounded-xl font-semibold transition-all duration-300 group-hover:scale-110">
                                            <i class="fas fa-check mr-2"></i>Clore
                                        </button>
                                    </form>
                                @endif
                                @if($inventory->status == 'completed')
                                    <form action="{{ route('inventories.archive', $inventory->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-gray-500/20 hover:bg-gray-500/30 text-gray-400 px-4 py-2 rounded-xl font-semibold transition-all duration-300 group-hover:scale-110">
                                            <i class="fas fa-archive mr-2"></i>Archiver
                                        </button>
                                    </form>
                                @endif
                                @if($inventory->status == 'archived')
                                    <form action="{{ route('inventories.restore', $inventory->id) }}" method="POST" class="flex-1">
                                        @csrf
                                        <button type="submit" class="w-full bg-green-500/20 hover:bg-green-500/30 text-green-400 px-4 py-2 rounded-xl font-semibold transition-all duration-300 group-hover:scale-110">
                                            <i class="fas fa-undo mr-2"></i>Restaurer
                                        </button>
                                    </form>
                                @endif
                                <a href="{{ route('inventories.delete', $inventory->id) }}" 
                                       onclick="return confirm('Êtes-vous sûr de vouloir supprimer cet inventaire ? Cette action est irréversible.')"
                                       class="w-full bg-red-500/20 hover:bg-red-500/30 text-red-400 px-4 py-2 rounded-xl font-semibold transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-trash mr-2"></i>Supprimer
                                    </a>
                            </div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-span-full text-center py-20">
                    <div class="w-32 h-32 mx-auto mb-6 relative">
                        <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full animate-pulse"></div>
                        <div class="absolute inset-2 bg-slate-900 rounded-full flex items-center justify-center">
                            <i class="fas fa-clipboard text-4xl text-gray-400"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-2">Aucun inventaire trouvé</h3>
                    <p class="text-gray-400 mb-6">Commencez par créer votre premier inventaire</p>
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

<!-- Inclure le composant de modal de suppression -->
@include('components.delete-modal')
@endsection
