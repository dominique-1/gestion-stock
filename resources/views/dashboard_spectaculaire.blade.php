@extends('layouts.app')

@section('title', 'Dashboard Spectaculaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-900 via-purple-900 to-slate-900">
    <!-- Header animé -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/20 to-purple-600/20 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-cyan-400 via-blue-500 to-purple-600 bg-clip-text text-transparent animate-gradient">
                            Tableau de Bord
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Vue d'ensemble en temps réel</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/20">
                        <span class="text-white font-semibold">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Particules animées -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-2 h-2 bg-blue-400 rounded-full animate-ping"></div>
            <div class="absolute top-20 right-20 w-3 h-3 bg-purple-400 rounded-full animate-ping animation-delay-2000"></div>
            <div class="absolute bottom-10 left-1/4 w-2 h-2 bg-cyan-400 rounded-full animate-ping animation-delay-4000"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Cartes statistiques spectaculaires -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Carte Produits -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-blue-600 to-cyan-600 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-white/80 text-sm font-bold uppercase tracking-wider mb-1">Total Produits</h3>
                            <p class="text-4xl font-black text-white">{{ $data['total_products'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-cyan-500 rounded-2xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-box text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-green-400 text-sm font-semibold">
                        <i class="fas fa-arrow-trend-up mr-2"></i>
                        <span>+12% ce mois</span>
                    </div>
                </div>
            </div>

            <!-- Carte Valeur Stock -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-emerald-600 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-white/80 text-sm font-bold uppercase tracking-wider mb-1">Valeur Stock</h3>
                            <p class="text-4xl font-black text-white">{{ number_format($data['total_stock_value'] ?? 0, 2) }} €</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-euro-sign text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-green-400 text-sm font-semibold">
                        <i class="fas fa-arrow-trend-up mr-2"></i>
                        <span>+8% ce mois</span>
                    </div>
                </div>
            </div>

            <!-- Carte Stock Faible -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-white/80 text-sm font-bold uppercase tracking-wider mb-1">Stock Faible</h3>
                            <p class="text-4xl font-black text-white">{{ $data['low_stock_products'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-red-400 text-sm font-semibold">
                        <i class="fas fa-arrow-trend-up mr-2"></i>
                        <span>+3 cette semaine</span>
                    </div>
                </div>
            </div>

            <!-- Carte Alertes -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-pink-600 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-white/80 text-sm font-bold uppercase tracking-wider mb-1">Alertes Actives</h3>
                            <p class="text-4xl font-black text-white">{{ $data['active_alerts'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-bell text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-yellow-400 text-sm font-semibold">
                        <i class="fas fa-clock mr-2"></i>
                        <span>5 non lues</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques et analyses -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Graphique des tendances -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <div class="w-3 h-3 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full mr-3 animate-pulse"></div>
                    Tendances des Stocks
                </h3>
                <div class="h-64 flex items-center justify-center">
                    <div class="text-center">
                        <div class="w-32 h-32 mx-auto mb-4 relative">
                            <div class="absolute inset-0 bg-gradient-to-r from-blue-500 to-purple-500 rounded-full animate-pulse"></div>
                            <div class="absolute inset-2 bg-slate-900 rounded-full flex items-center justify-center">
                                <span class="text-3xl font-black text-white">87%</span>
                            </div>
                        </div>
                        <p class="text-gray-400">Efficacité globale</p>
                    </div>
                </div>
            </div>

            <!-- Mouvements récents -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <div class="w-3 h-3 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-3 animate-pulse"></div>
                    Mouvements Récents
                </h3>
                <div class="space-y-4">
                    @foreach($data['recent_movements'] ?? [] as $movement)
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl hover:bg-white/10 transition-all duration-300">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 {{ $movement->type == 'in' ? 'bg-green-500/20' : 'bg-red-500/20' }} rounded-xl flex items-center justify-center">
                                <i class="fas fa-arrow-{{ $movement->type == 'in' ? 'down' : 'up' }} text-white {{ $movement->type == 'in' ? 'text-green-400' : 'text-red-400' }}"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold">{{ $movement->product->name ?? 'Produit' }}</p>
                                <p class="text-gray-400 text-sm">{{ $movement->created_at->format('d/m H:i') }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="text-white font-bold">{{ $movement->quantity }}</p>
                            <p class="text-gray-400 text-sm">{{ $movement->reason }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-gradient-to-r from-purple-600/20 to-blue-600/20 rounded-3xl p-8 border border-white/20">
            <h3 class="text-2xl font-bold text-white mb-6">Actions Rapides</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('products.create') }}" class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <i class="fas fa-plus-circle text-3xl text-blue-400 mb-3 group-hover:scale-110 transition-transform duration-300"></i>
                    <p class="text-white font-semibold">Ajouter Produit</p>
                </a>
                <a href="{{ route('movements.create') }}" class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <i class="fas fa-exchange-alt text-3xl text-green-400 mb-3 group-hover:scale-110 transition-transform duration-300"></i>
                    <p class="text-white font-semibold">Mouvement Stock</p>
                </a>
                <a href="{{ route('alerts.index') }}" class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <i class="fas fa-bell text-3xl text-yellow-400 mb-3 group-hover:scale-110 transition-transform duration-300"></i>
                    <p class="text-white font-semibold">Voir Alertes</p>
                </a>
                <a href="{{ route('exports.index') }}" class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <i class="fas fa-download text-3xl text-purple-400 mb-3 group-hover:scale-110 transition-transform duration-300"></i>
                    <p class="text-white font-semibold">Exporter</p>
                </a>
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

.animation-delay-2000 {
    animation-delay: 2s;
}

.animation-delay-4000 {
    animation-delay: 4s;
}

.card-hover {
    transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1);
}

.card-hover:hover {
    transform: translateY(-8px) scale(1.02);
}
</style>
@endsection
