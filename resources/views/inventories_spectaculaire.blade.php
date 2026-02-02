@extends('layouts.app')

@section('title', 'Inventaires - Design Spectaculaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-violet-900 via-purple-900 to-indigo-900">
    <!-- Header spectaculaire -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-violet-600/30 via-purple-600/30 to-indigo-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-violet-400 via-purple-500 to-indigo-600 bg-clip-text text-transparent animate-gradient">
                            Inventaires
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Gestion complète des inventaires périodiques</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('inventories.create') }}" class="group bg-gradient-to-r from-violet-500 to-purple-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-violet-500/50">
                        <i class="fas fa-clipboard-list mr-3 group-hover:rotate-12 transition-transform duration-300"></i>
                        Nouvel Inventaire
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Éléments décoratifs -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-4 h-4 bg-violet-400 rounded-full animate-bounce"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-purple-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-10 left-1/4 w-3 h-3 bg-indigo-400 rounded-full animate-pulse"></div>
            <div class="absolute top-1/3 right-1/4 w-5 h-5 bg-pink-400 rounded-full animate-bounce animation-delay-2000"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-white/80 text-sm font-bold uppercase tracking-wider mb-1">Total Inventaires</h3>
                            <p class="text-3xl font-black text-white">{{ $stats['total_inventories'] ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-clipboard text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-blue-400 text-sm font-semibold">
                        <i class="fas fa-chart-line mr-2"></i>
                        <span>{{ $stats['this_month'] ?? 0 }} ce mois</span>
                    </div>
                </div>
            </div>

            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-emerald-600 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-white/80 text-sm font-bold uppercase tracking-wider mb-1">Complétés</h3>
                            <p class="text-3xl font-black text-white">{{ $stats['completed'] ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-check-circle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-green-400 text-sm font-semibold">
                        <i class="fas fa-trophy mr-2"></i>
                        <span>{{ $stats['completion_rate'] ?? 0 }}% de taux</span>
                    </div>
                </div>
            </div>

            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-white/80 text-sm font-bold uppercase tracking-wider mb-1">En Cours</h3>
                            <p class="text-3xl font-black text-white">{{ $stats['in_progress'] ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-spinner text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-yellow-400 text-sm font-semibold">
                        <i class="fas fa-clock mr-2"></i>
                        <span>En progression</span>
                    </div>
                </div>
            </div>

            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-6 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-white/80 text-sm font-bold uppercase tracking-wider mb-1">Écarts</h3>
                            <p class="text-3xl font-black text-white">{{ $stats['total_discrepancies'] ?? 0 }}</p>
                        </div>
                        <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-purple-400 text-sm font-semibold">
                        <i class="fas fa-chart-bar mr-2"></i>
                        <span>À analyser</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des inventaires -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
            @forelse($inventories ?? [] as $inventory)
            <div class="group relative">
                <!-- Fond animé -->
                <div class="absolute inset-0 bg-gradient-to-br from-violet-600/20 via-purple-600/20 to-indigo-600/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                
                <!-- Carte inventaire -->
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <!-- En-tête -->
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-2xl font-black text-white mb-2 group-hover:text-violet-300 transition-colors duration-300">
                                {{ $inventory->reference }}
                            </h3>
                            <div class="flex items-center space-x-3">
                                @switch($inventory->status)
                                    @case('draft')
                                        <div class="inline-flex items-center bg-gray-500/20 text-gray-400 px-4 py-2 rounded-full text-sm font-bold">
                                            <i class="fas fa-file-alt mr-2"></i>Brouillon
                                        </div>
                                    @case('in_progress')
                                        <div class="inline-flex items-center bg-yellow-500/20 text-yellow-400 px-4 py-2 rounded-full text-sm font-bold animate-pulse">
                                            <i class="fas fa-spinner mr-2"></i>En cours
                                        </div>
                                    @case('completed')
                                        <div class="inline-flex items-center bg-green-500/20 text-green-400 px-4 py-2 rounded-full text-sm font-bold">
                                            <i class="fas fa-check-circle mr-2"></i>Terminé
                                        </div>
                                    @case('archived')
                                        <div class="inline-flex items-center bg-blue-500/20 text-blue-400 px-4 py-2 rounded-full text-sm font-bold">
                                            <i class="fas fa-archive mr-2"></i>Archivé
                                        </div>
                                @endswitch
                            </div>
                        </div>
                        <div class="flex items-center space-x-2">
                            <div class="w-12 h-12 bg-gradient-to-br from-violet-500 to-purple-500 rounded-xl flex items-center justify-center">
                                <i class="fas fa-clipboard-list text-white"></i>
                            </div>
                        </div>
                    </div>

                    <!-- Informations -->
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div class="bg-white/5 rounded-2xl p-4">
                            <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Date de début</p>
                            <p class="text-white font-semibold">{{ $inventory->started_at ? $inventory->started_at->format('d/m/Y') : 'Non démarré' }}</p>
                        </div>
                        <div class="bg-white/5 rounded-2xl p-4">
                            <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Date de fin</p>
                            <p class="text-white font-semibold">{{ $inventory->completed_at ? $inventory->completed_at->format('d/m/Y') : 'En cours' }}</p>
                        </div>
                    </div>

                    <!-- Statistiques -->
                    <div class="grid grid-cols-3 gap-3 mb-6">
                        <div class="bg-white/5 rounded-xl p-3 text-center">
                            <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Produits</p>
                            <p class="text-xl font-black text-white">{{ $inventory->lines_count ?? 0 }}</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-3 text-center">
                            <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Écarts</p>
                            <p class="text-xl font-black text-yellow-400">{{ $inventory->discrepancies_count ?? 0 }}</p>
                        </div>
                        <div class="bg-white/5 rounded-xl p-3 text-center">
                            <p class="text-gray-400 text-xs uppercase tracking-wider mb-1">Précision</p>
                            <p class="text-xl font-black text-green-400">{{ $inventory->accuracy ?? 0 }}%</p>
                        </div>
                    </div>

                    <!-- Notes -->
                    @if($inventory->notes)
                    <div class="bg-white/5 rounded-2xl p-4 mb-6">
                        <p class="text-gray-400 text-xs uppercase tracking-wider mb-2">Notes</p>
                        <p class="text-gray-300 text-sm">{{ Str::limit($inventory->notes, 100) }}</p>
                    </div>
                    @endif

                    <!-- Actions -->
                    <div class="flex space-x-3">
                        <a href="{{ route('inventories.show', $inventory->id) }}" 
                           class="flex-1 bg-gradient-to-r from-blue-500 to-blue-600 text-white px-4 py-3 rounded-2xl font-semibold text-center hover:scale-105 transition-all duration-300 hover:shadow-blue-500/50">
                            <i class="fas fa-eye mr-2"></i>Voir
                        </a>
                        
                        @if($inventory->status == 'draft')
                            <a href="{{ route('inventories.edit', $inventory->id) }}" 
                               class="flex-1 bg-gradient-to-r from-purple-500 to-purple-600 text-white px-4 py-3 rounded-2xl font-semibold text-center hover:scale-105 transition-all duration-300 hover:shadow-purple-500/50">
                                <i class="fas fa-edit mr-2"></i>Modifier
                            </a>
                        @endif

                        @if($inventory->status == 'in_progress')
                            <form method="POST" action="{{ route('inventories.close', $inventory->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-gradient-to-r from-green-500 to-emerald-600 text-white px-4 py-3 rounded-2xl font-semibold hover:scale-105 transition-all duration-300 hover:shadow-green-500/50">
                                    <i class="fas fa-check-circle mr-2"></i>Clore
                                </button>
                            </form>
                        @endif

                        @if($inventory->status == 'completed')
                            <form method="POST" action="{{ route('inventories.archive', $inventory->id) }}" class="flex-1">
                                @csrf
                                <button type="submit" class="w-full bg-gradient-to-r from-gray-500 to-gray-600 text-white px-4 py-3 rounded-2xl font-semibold hover:scale-105 transition-all duration-300 hover:shadow-gray-500/50">
                                    <i class="fas fa-archive mr-2"></i>Archiver
                                </button>
                            </form>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="col-span-full text-center py-20">
                <div class="w-32 h-32 mx-auto mb-6 relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-600 to-gray-700 rounded-full animate-pulse"></div>
                    <div class="absolute inset-2 bg-slate-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-clipboard text-4xl text-gray-400"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-white mb-4">Aucun inventaire trouvé</h3>
                <p class="text-gray-400 text-lg mb-8">Commencez par créer votre premier inventaire</p>
                <a href="{{ route('inventories.create') }}" class="inline-block bg-gradient-to-r from-violet-500 to-purple-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-violet-500/50">
                    <i class="fas fa-clipboard-list mr-3"></i>Créer un inventaire
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
