@extends('layouts.app')

@section('title', 'Prédictions - Design Spectaculaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-900 via-pink-900 to-indigo-900">
    <!-- Header époustouflant -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-purple-600/30 via-pink-600/30 to-indigo-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-purple-400 via-pink-500 to-indigo-600 bg-clip-text text-transparent animate-gradient">
                            Prédictions Intelligentes
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Algorithmes ML et analyses prédictives avancées</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-md text-white px-6 py-3 rounded-2xl border border-white/20">
                        <i class="fas fa-brain mr-2 text-purple-400"></i>
                        <span class="font-semibold">IA Activée</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-4 h-4 bg-purple-400 rounded-full animate-bounce"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-pink-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-10 left-1/4 w-3 h-3 bg-indigo-400 rounded-full animate-pulse"></div>
            <div class="absolute top-1/3 right-1/4 w-5 h-5 bg-cyan-400 rounded-full animate-bounce animation-delay-2000"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Paramètres de prédiction spectaculaires -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 mb-8">
            <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                <div class="w-3 h-3 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full mr-3 animate-pulse"></div>
                Paramètres de Prédiction IA
            </h3>
            
            <form method="GET" action="{{ route('predictions.index') }}" class="space-y-6">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Période</label>
                        <select name="period" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                            <option value="7" {{ $period == 7 ? 'selected' : '' }}>7 jours</option>
                            <option value="30" {{ $period == 30 ? 'selected' : '' }}>30 jours</option>
                            <option value="90" {{ $period == 90 ? 'selected' : '' }}>90 jours</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Algorithme</label>
                        <select name="algorithm" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                            <option value="auto" {{ $algorithm == 'auto' ? 'selected' : '' }}>Auto (recommandé)</option>
                            <option value="linear" {{ $algorithm == 'linear' ? 'selected' : '' }}>Régression Linéaire</option>
                            <option value="moving_average" {{ $algorithm == 'moving_average' ? 'selected' : '' }}>Moyenne Mobile</option>
                            <option value="ml" {{ $algorithm == 'ml' ? 'selected' : '' }}>Machine Learning</option>
                        </select>
                    </div>
                    
                    <div>
                        <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Jours Moyenne Mobile</label>
                        <input type="number" name="moving_average_days" value="{{ $movingAverageDays }}" min="3" max="30" 
                               class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                    </div>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div>
                        <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Produit (optionnel)</label>
                        <select name="product_id" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                            <option value="">Tous les produits</option>
                            @foreach($products as $product)
                                <option value="{{ $product->id }}" {{ request('product_id') == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                            @endforeach
                        </select>
                    </div>
                    
                    <div class="md:col-span-2 flex items-end">
                        <button type="submit" class="bg-gradient-to-r from-purple-600 to-pink-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-purple-500/50">
                            <i class="fas fa-brain mr-3"></i>Générer les Prédictions
                        </button>
                    </div>
                </div>
            </form>
        </div>

        <!-- Statistiques des prédictions -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-3xl p-6 border border-green-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-400 text-sm font-bold uppercase tracking-wider mb-1">Précision</p>
                        <p class="text-3xl font-black text-white">{{ round(collect($predictions)->avg('confidence') * 100) }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bullseye text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-3xl p-6 border border-blue-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-400 text-sm font-bold uppercase tracking-wider mb-1">Algorithme</p>
                        <p class="text-3xl font-black text-white">{{ ucfirst($chosenAlgorithm) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-shield-alt text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 rounded-3xl p-6 border border-yellow-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-400 text-sm font-bold uppercase tracking-wider mb-1">Risques</p>
                        <p class="text-3xl font-black text-white">{{ collect($stockRisks)->where('risk_level', 'critical')->count() }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-3xl p-6 border border-purple-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-400 text-sm font-bold uppercase tracking-wider mb-1">Prédictions</p>
                        <p class="text-3xl font-black text-white">{{ count($predictions) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques de prédictions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Graphique principal -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <div class="w-3 h-3 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full mr-3 animate-pulse"></div>
                    Prédictions de Stock
                </h3>
                <div class="h-64 flex items-center justify-center">
                    <canvas id="predictionChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Analyse de tendance -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <div class="w-3 h-3 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-3 animate-pulse"></div>
                    Analyse de Tendance
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl">
                        <span class="text-white font-semibold">Tendance générale</span>
                        <span class="text-green-400 font-bold">📈 Hausse +12%</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl">
                        <span class="text-white font-semibold">Volatilité</span>
                        <span class="text-yellow-400 font-bold">📊 Modérée</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl">
                        <span class="text-white font-semibold">Saisonnalité</span>
                        <span class="text-blue-400 font-bold">🔄 Détectée</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl">
                        <span class="text-white font-semibold">Fiabilité</span>
                        <span class="text-purple-400 font-bold">✨ Élevée</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recommandations IA -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <div class="w-3 h-3 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full mr-3 animate-pulse"></div>
                Recommandations Intelligentes
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                <!-- Recommandation 1 -->
                <div class="bg-gradient-to-br from-red-500/20 to-pink-500/20 rounded-2xl p-6 border border-red-500/30">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-exclamation-triangle text-white"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold">Rupture Imminente</h4>
                            <p class="text-red-400 text-sm">Laptop Pro 15"</p>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm mb-4">Stock actuel: 3 unités<br>Rupture prévue: dans 4 jours</p>
                    <div class="bg-red-500/20 text-red-400 px-4 py-2 rounded-xl text-sm font-bold text-center">
                        🚨 Commander 15 unités
                    </div>
                </div>

                <!-- Recommandation 2 -->
                <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 rounded-2xl p-6 border border-yellow-500/30">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-chart-line text-white"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold">Tendance Baissière</h4>
                            <p class="text-yellow-400 text-sm">Moniteur 27"</p>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm mb-4">Ventes en baisse de 15%<br>Surstock prévu: 20 unités</p>
                    <div class="bg-yellow-500/20 text-yellow-400 px-4 py-2 rounded-xl text-sm font-bold text-center">
                        📉 Promotion conseillée
                    </div>
                </div>

                <!-- Recommandation 3 -->
                <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-2xl p-6 border border-green-500/30">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-rocket text-white"></i>
                        </div>
                        <div>
                            <h4 class="text-white font-bold">Opportunité</h4>
                            <p class="text-green-400 text-sm">Clavier USB</p>
                        </div>
                    </div>
                    <p class="text-gray-300 text-sm mb-4">Demande en forte hausse<br>+35% ce mois</p>
                    <div class="bg-green-500/20 text-green-400 px-4 py-2 rounded-xl text-sm font-bold text-center">
                        🚀 Stock augmenté
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des prédictions avec vraies données
const ctx = document.getElementById('predictionChart').getContext('2d');

// Préparer les données réelles du controller
const historicalLabels = @json($historicalData->pluck('date')->map(function($date) { 
    return \Carbon\Carbon::parse($date)->format('d/m'); 
}));
const historicalStock = @json($historicalData->pluck('stock'));

const predictionLabels = @json(collect($predictions)->pluck('date')->map(function($date) { 
    return \Carbon\Carbon::parse($date)->format('d/m'); 
}));
const predictionStock = @json(collect($predictions)->pluck('predicted_stock'));

const allLabels = historicalLabels.concat(predictionLabels);
const allHistoricalData = historicalStock.concat(Array(predictionLabels.length).fill(null));
const allPredictionData = Array(historicalLabels.length).fill(null).concat(predictionStock);

// Stock minimum (fixe à 10 pour l'exemple)
const stockMinData = Array(allLabels.length).fill(10);

const predictionChart = new Chart(ctx, {
    type: 'line',
    data: {
        labels: allLabels,
        datasets: [{
            label: 'Stock Historique',
            data: allHistoricalData,
            borderColor: 'rgb(59, 130, 246)',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            fill: false
        }, {
            label: 'Prédiction IA',
            data: allPredictionData,
            borderColor: 'rgb(236, 72, 153)',
            backgroundColor: 'rgba(236, 72, 153, 0.1)',
            borderDash: [5, 5],
            tension: 0.4,
            fill: false
        }, {
            label: 'Stock Minimum',
            data: stockMinData,
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            borderDash: [2, 2],
            fill: false
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    color: 'white',
                    font: {
                        size: 12
                    }
                }
            },
            tooltip: {
                mode: 'index',
                intersect: false,
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += Math.round(context.parsed.y) + ' unités';
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: {
                    color: 'white',
                    font: {
                        size: 11
                    }
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                },
                title: {
                    display: true,
                    text: 'Quantité en Stock',
                    color: 'white',
                    font: {
                        size: 12
                    }
                }
            },
            x: {
                ticks: {
                    color: 'white',
                    font: {
                        size: 11
                    }
                },
                grid: {
                    color: 'rgba(255, 255, 255, 0.1)'
                },
                title: {
                    display: true,
                    text: 'Date',
                    color: 'white',
                    font: {
                        size: 12
                    }
                }
            }
        },
        interaction: {
            mode: 'nearest',
            axis: 'x',
            intersect: false
        }
    }
});

// Animation de chargement
predictionChart.update('active');
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
</style>
@endsection
