@extends('layouts.app')

@section('title', 'Prédictions - Design Spectaculaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-cyan-900 via-blue-900 to-indigo-900">
    <!-- Header spectaculaire -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-cyan-600/30 via-blue-600/30 to-indigo-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-cyan-400 via-blue-500 to-indigo-600 bg-clip-text text-transparent animate-gradient">
                            Prédictions Intelligentes
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Machine Learning pour anticiper les besoins de stock</p>
                </div>
                <div class="flex items-center space-x-4">
                    <button onclick="refreshPredictions()" class="group bg-gradient-to-r from-cyan-500 to-blue-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-cyan-500/50">
                        <i class="fas fa-sync-alt mr-3 group-hover:rotate-180 transition-transform duration-500"></i>
                        Actualiser
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-4 h-4 bg-cyan-400 rounded-full animate-bounce"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-blue-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-10 left-1/4 w-3 h-3 bg-indigo-400 rounded-full animate-pulse"></div>
            <div class="absolute top-1/3 right-1/4 w-5 h-5 bg-purple-400 rounded-full animate-bounce animation-delay-2000"></div>
            <div class="absolute top-2/3 left-1/3 w-4 h-4 bg-pink-400 rounded-full animate-ping animation-delay-4000"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Cartes de prédiction principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <!-- Prédictions de rupture -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-red-600 to-pink-600 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-white/80 text-sm font-bold uppercase tracking-wider mb-1">Ruptures Imminentes</h3>
                            <p class="text-4xl font-black text-white">{{ $predictions['stockouts'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-2xl animate-pulse">
                            <i class="fas fa-exclamation-triangle text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="space-y-3">
                        @foreach($predictions['stockout_products'] ?? [] as $product)
                        <div class="bg-white/5 rounded-2xl p-3 flex items-center justify-between">
                            <span class="text-white text-sm font-medium">{{ $product->name }}</span>
                            <span class="text-red-400 text-xs font-bold">{{ $product->days_until_stockout }} jours</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Surstocks prédits -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-yellow-600 to-orange-600 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-white/80 text-sm font-bold uppercase tracking-wider mb-1">Surstocks Détectés</h3>
                            <p class="text-4xl font-black text-white">{{ $predictions['overstocks'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-arrow-trend-up text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="space-y-3">
                        @foreach($predictions['overstock_products'] ?? [] as $product)
                        <div class="bg-white/5 rounded-2xl p-3 flex items-center justify-between">
                            <span class="text-white text-sm font-medium">{{ $product->name }}</span>
                            <span class="text-yellow-400 text-xs font-bold">+{{ $product->excess_quantity }}</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Expirations -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-purple-600 to-pink-600 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-white/80 text-sm font-bold uppercase tracking-wider mb-1">Produits Expirants</h3>
                            <p class="text-4xl font-black text-white">{{ $predictions['expiring'] ?? 0 }}</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-clock text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="space-y-3">
                        @foreach($predictions['expiring_products'] ?? [] as $product)
                        <div class="bg-white/5 rounded-2xl p-3 flex items-center justify-between">
                            <span class="text-white text-sm font-medium">{{ $product->name }}</span>
                            <span class="text-purple-400 text-xs font-bold">{{ $product->days_until_expiry }} jours</span>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Précision du modèle -->
            <div class="group relative">
                <div class="absolute inset-0 bg-gradient-to-r from-green-600 to-emerald-600 rounded-3xl blur-xl opacity-75 group-hover:opacity-100 transition-all duration-500"></div>
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                    <div class="flex items-center justify-between mb-6">
                        <div>
                            <h3 class="text-white/80 text-sm font-bold uppercase tracking-wider mb-1">Précision ML</h3>
                            <p class="text-4xl font-black text-white">{{ $predictions['accuracy'] ?? 0 }}%</p>
                        </div>
                        <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center shadow-2xl">
                            <i class="fas fa-brain text-white text-2xl"></i>
                        </div>
                    </div>
                    <div class="space-y-3">
                        <div class="bg-white/5 rounded-2xl p-3">
                            <div class="flex justify-between items-center mb-2">
                                <span class="text-gray-400 text-xs">Modèle entraîné</span>
                                <span class="text-green-400 text-xs font-bold">{{ $predictions['model_trained'] ?? 'Non' }}</span>
                            </div>
                            <div class="flex justify-between items-center">
                                <span class="text-gray-400 text-xs">Dernière mise à jour</span>
                                <span class="text-green-400 text-xs font-bold">{{ $predictions['last_update'] ?? 'Jamais' }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphique de prédictions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-12">
            <!-- Tendances prédictives -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <div class="w-3 h-3 bg-gradient-to-r from-cyan-400 to-blue-400 rounded-full mr-3 animate-pulse"></div>
                    Tendances Prédictives
                </h3>
                <div class="h-80 flex items-center justify-center">
                    <div class="relative w-full h-full">
                        <!-- Graphique simulé -->
                        <div class="absolute inset-0 flex items-end justify-between px-4">
                            @for($i = 0; $i < 12; $i++)
                            <div class="flex-1 mx-1">
                                <div class="bg-gradient-to-t from-cyan-500/30 to-blue-500 rounded-t-lg" style="height: {{ rand(40, 90) }}%"></div>
                                <div class="text-center text-xs text-gray-400 mt-2">{{ ['J', 'F', 'M', 'A', 'M', 'J', 'J', 'A', 'S', 'O', 'N', 'D'][$i] }}</div>
                            </div>
                            @endfor
                        </div>
                        <!-- Ligne de prédiction -->
                        <div class="absolute inset-0 flex items-center justify-center">
                            <div class="w-full h-0.5 bg-gradient-to-r from-purple-400 to-pink-400 opacity-75"></div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Recommandations IA -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                    <div class="w-3 h-3 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-3 animate-pulse"></div>
                    Recommandations IA
                </h3>
                <div class="space-y-4">
                    @foreach($predictions['recommendations'] ?? [] as $rec)
                    <div class="bg-gradient-to-r from-purple-500/20 to-pink-500/20 rounded-2xl p-6 border border-purple-500/30 hover:border-purple-500/50 transition-all duration-300">
                        <div class="flex items-start space-x-4">
                            <div class="w-10 h-10 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-lightbulb text-white"></i>
                            </div>
                            <div>
                                <h4 class="text-white font-bold mb-2">{{ $rec['title'] }}</h4>
                                <p class="text-gray-300 text-sm leading-relaxed">{{ $rec['description'] }}</p>
                                <div class="mt-3 flex items-center space-x-2">
                                    <span class="bg-purple-500/20 text-purple-400 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $rec['priority'] }}
                                    </span>
                                    <span class="bg-blue-500/20 text-blue-400 px-3 py-1 rounded-full text-xs font-bold">
                                        {{ $rec['impact'] }}% impact
                                    </span>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Actions recommandées -->
        <div class="bg-gradient-to-r from-cyan-600/20 via-blue-600/20 to-indigo-600/20 rounded-3xl p-8 border border-white/20">
            <h3 class="text-2xl font-bold text-white mb-6">Actions Recommandées</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <a href="{{ route('orders.create') }}" class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-red-500 to-orange-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-shopping-cart text-white text-2xl"></i>
                    </div>
                    <h4 class="text-white font-bold mb-2">Commandes Urgentes</h4>
                    <p class="text-gray-400 text-sm">{{ $predictions['urgent_orders'] ?? 0 }} produits à commander</p>
                </a>

                <a href="{{ route('products.create') }}" class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-tags text-white text-2xl"></i>
                    </div>
                    <h4 class="text-white font-bold mb-2">Promotions</h4>
                    <p class="text-gray-400 text-sm">{{ $predictions['promotion_candidates'] ?? 0 }} produits en surstock</p>
                </a>

                <a href="{{ route('alerts.index') }}" class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <div class="w-16 h-16 mx-auto mb-4 bg-gradient-to-br from-purple-500 to-pink-500 rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                        <i class="fas fa-bell text-white text-2xl"></i>
                    </div>
                    <h4 class="text-white font-bold mb-2">Alertes Configurées</h4>
                    <p class="text-gray-400 text-sm">{{ $predictions['configured_alerts'] ?? 0 }} alertes actives</p>
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function refreshPredictions() {
    // Animation de rafraîchissement
    const button = event.target.closest('button');
    const icon = button.querySelector('i');
    icon.classList.add('fa-spin');
    
    setTimeout(() => {
        icon.classList.remove('fa-spin');
        // Ici, vous pourriez ajouter un appel AJAX pour rafraîchir les prédictions
        location.reload();
    }, 2000);
}
</script>

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
</style>
@endsection
