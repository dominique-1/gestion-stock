@extends('layouts.app')

@section('title', 'Dashboard Moderne')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 relative overflow-hidden">
    <!-- Éléments décoratifs de fond -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-indigo-400/20 to-pink-400/20 rounded-full blur-3xl"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-purple-300/10 to-blue-300/10 rounded-full blur-3xl"></div>
    </div>

    <!-- Header moderne avec effet glassmorphism -->
    <div class="glass-morphism shadow-xl border-b border-white/20 relative z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-8">
                <div class="fade-in">
                    <h1 class="text-4xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent mb-2">
                        Tableau de Bord
                    </h1>
                    <p class="text-gray-600 flex items-center">
                        <i class="fas fa-chart-line mr-2 text-indigo-500"></i>
                        Vue d'ensemble en temps réel
                        <span class="ml-2 inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            <span class="w-2 h-2 bg-green-400 rounded-full mr-1 pulse"></span>
                            En ligne
                        </span>
                    </p>
                </div>
                <div class="flex items-center space-x-4 fade-in">
                    <div class="bg-white/80 backdrop-blur-sm text-gray-700 px-6 py-3 rounded-xl shadow-lg border border-white/50">
                        <div class="flex items-center space-x-2">
                            <i class="far fa-calendar-alt text-indigo-500"></i>
                            <span class="font-semibold">{{ now()->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>
                    <button onclick="refreshDashboard()" class="btn-gradient shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                        <i class="fas fa-sync-alt mr-2"></i>Rafraîchir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8 relative z-10">
        <!-- Indicateurs clés avec animations modernes -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <!-- Total Produits -->
            <div class="indicator-card group relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-6 border border-white/50 transform hover:scale-105 transition-all duration-300 fade-in-up">
                <div class="absolute inset-0 bg-gradient-to-br from-blue-400/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Total Produits</h3>
                            <p class="text-3xl font-bold bg-gradient-to-r from-blue-600 to-indigo-600 bg-clip-text text-transparent" id="total-products">{{ $data['total_products'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-box text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-green-600 font-medium">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>+12% ce mois</span>
                        <div class="ml-2 w-2 h-2 bg-green-400 rounded-full pulse"></div>
                    </div>
                </div>
            </div>

            <!-- Valeur Stock -->
            <div class="indicator-card group relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-6 border border-white/50 transform hover:scale-105 transition-all duration-300 fade-in-up" style="animation-delay: 0.1s">
                <div class="absolute inset-0 bg-gradient-to-br from-green-400/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Valeur Stock</h3>
                            <p class="text-3xl font-bold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent" id="stock-value">{{ number_format($data['total_stock_value'] ?? 0, 2) }} €</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-green-400 to-green-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-euro-sign text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-green-600 font-medium">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>+8% ce mois</span>
                        <div class="ml-2 w-2 h-2 bg-green-400 rounded-full pulse"></div>
                    </div>
                </div>
            </div>

            <!-- Stock Faible -->
            <div class="indicator-card group relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-6 border border-white/50 transform hover:scale-105 transition-all duration-300 fade-in-up" style="animation-delay: 0.2s">
                <div class="absolute inset-0 bg-gradient-to-br from-yellow-400/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Stock Faible</h3>
                            <p class="text-3xl font-bold bg-gradient-to-r from-yellow-600 to-orange-600 bg-clip-text text-transparent" id="low-stock">{{ $data['low_stock_products'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-red-600 font-medium">
                        <i class="fas fa-arrow-up mr-1"></i>
                        <span>+3 cette semaine</span>
                        <div class="ml-2 w-2 h-2 bg-red-400 rounded-full pulse"></div>
                    </div>
                </div>
            </div>

            <!-- Prévision Besoins -->
            <div class="indicator-card group relative bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-6 border border-white/50 transform hover:scale-105 transition-all duration-300 fade-in-up" style="animation-delay: 0.3s">
                <div class="absolute inset-0 bg-gradient-to-br from-purple-400/10 to-transparent rounded-2xl opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                <div class="relative z-10">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-sm font-medium text-gray-600 mb-1">Prévision Besoins</h3>
                            <p class="text-3xl font-bold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent" id="needs-forecast">{{ $data['needs_forecast'] ?? 0 }}</p>
                        </div>
                        <div class="w-14 h-14 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-chart-line text-white text-xl"></i>
                        </div>
                    </div>
                    <div class="flex items-center text-sm text-purple-600 font-medium">
                        <i class="fas fa-info-circle mr-1"></i>
                        <span>Produits à réapprovisionner</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques interactifs avec design moderne -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Graphique évolution stock par produit -->
            <div class="chart-container group bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-6 border border-white/50 fade-in-up" style="animation-delay: 0.4s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Évolution Stock par Produit</h3>
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 bg-blue-400 rounded-full pulse"></span>
                        <span class="text-xs text-gray-500">En temps réel</span>
                    </div>
                </div>
                <div class="relative">
                    <canvas id="stockEvolutionChart" width="400" height="200"></canvas>
                </div>
            </div>

            <!-- Graphique ventes/sorties -->
            <div class="chart-container group bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-6 border border-white/50 fade-in-up" style="animation-delay: 0.5s">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold bg-gradient-to-r from-green-600 to-emerald-600 bg-clip-text text-transparent">Ventes / Sorties</h3>
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 bg-green-400 rounded-full pulse"></span>
                        <span class="text-xs text-gray-500">Cette semaine</span>
                    </div>
                </div>
                <div class="relative">
                    <canvas id="salesChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>

        <!-- Graphique mouvements par catégorie -->
        <div class="chart-container group bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-6 mb-8 border border-white/50 fade-in-up" style="animation-delay: 0.6s">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold bg-gradient-to-r from-purple-600 to-pink-600 bg-clip-text text-transparent">Mouvements par Catégorie</h3>
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 bg-purple-400 rounded-full pulse"></span>
                    <span class="text-xs text-gray-500">Analyse complète</span>
                </div>
            </div>
            <div class="relative">
                <canvas id="categoryMovementsChart" width="800" height="300"></canvas>
            </div>
        </div>

        <!-- Tableau des mouvements récents avec design moderne -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl mb-8 border border-white/50 fade-in-up" style="animation-delay: 0.7s">
            <div class="px-6 py-4 border-b border-gray-200/50 bg-gradient-to-r from-gray-50 to-white rounded-t-2xl">
                <div class="flex items-center justify-between">
                    <h3 class="text-lg font-semibold bg-gradient-to-r from-gray-700 to-gray-900 bg-clip-text text-transparent">Mouvements Récents</h3>
                    <div class="flex items-center space-x-2">
                        <span class="w-2 h-2 bg-blue-400 rounded-full pulse"></span>
                        <span class="text-xs text-gray-500">Dernières 24h</span>
                    </div>
                </div>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200/50">
                    <thead class="bg-gradient-to-r from-gray-50/50 to-white/50">
                        <tr>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Motif</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white/50 divide-y divide-gray-200/30" id="movements-table">
                        @foreach($movements as $movement)
                        <tr class="hover:bg-gray-50/50 transition-colors duration-200">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movement->moved_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">{{ $movement->product->name ?? 'Produit' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-3 py-1 inline-flex text-xs leading-5 font-semibold rounded-full {{ $movement->type == 'in' ? 'bg-gradient-to-r from-green-100 to-emerald-100 text-green-800 border border-green-200' : 'bg-gradient-to-r from-red-100 to-pink-100 text-red-800 border border-red-200' }}">
                                    <i class="fas {{ $movement->type == 'in' ? 'fa-arrow-down' : 'fa-arrow-up' }} mr-1"></i>
                                    {{ $movement->type == 'in' ? 'Entrée' : 'Sortie' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900">{{ $movement->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">{{ $movement->reason }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actions rapides avec design moderne -->
        <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-8 border border-white/50 fade-in-up" style="animation-delay: 0.8s">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-semibold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">Actions Rapides</h3>
                <div class="flex items-center space-x-2">
                    <span class="w-2 h-2 bg-indigo-400 rounded-full pulse"></span>
                    <span class="text-xs text-gray-500">Accès rapide</span>
                </div>
            </div>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('products.create') }}" class="group bg-gradient-to-r from-blue-500 to-blue-600 text-white px-6 py-4 rounded-xl hover:from-blue-600 hover:to-blue-700 transition-all duration-300 text-center shadow-lg hover:shadow-xl transform hover:scale-105 flex flex-col items-center space-y-2">
                    <i class="fas fa-plus text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                    <span class="font-semibold">Ajouter Produit</span>
                </a>
                <a href="{{ route('movements.create') }}" class="group bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl hover:from-green-600 hover:to-green-700 transition-all duration-300 text-center shadow-lg hover:shadow-xl transform hover:scale-105 flex flex-col items-center space-y-2">
                    <i class="fas fa-exchange-alt text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                    <span class="font-semibold">Mouvement</span>
                </a>
                <a href="{{ route('alerts.index') }}" class="group bg-gradient-to-r from-yellow-500 to-orange-500 text-white px-6 py-4 rounded-xl hover:from-yellow-600 hover:to-orange-600 transition-all duration-300 text-center shadow-lg hover:shadow-xl transform hover:scale-105 flex flex-col items-center space-y-2">
                    <i class="fas fa-bell text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                    <span class="font-semibold">Alertes</span>
                </a>
                <a href="{{ route('exports.index') }}" class="group bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-4 rounded-xl hover:from-purple-600 hover:to-purple-700 transition-all duration-300 text-center shadow-lg hover:shadow-xl transform hover:scale-105 flex flex-col items-center space-y-2">
                    <i class="fas fa-download text-2xl group-hover:scale-110 transition-transform duration-300"></i>
                    <span class="font-semibold">Exporter</span>
                </a>
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique évolution stock par produit
const stockEvolutionCtx = document.getElementById('stockEvolutionChart').getContext('2d');
const stockEvolutionData = @json($stockEvolution);
const stockEvolutionChart = new Chart(stockEvolutionCtx, {
    type: 'line',
    data: {
        labels: stockEvolutionData.labels,
        datasets: stockEvolutionData.datasets.map((dataset, index) => ({
            label: dataset.label,
            data: dataset.data,
            borderColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'][index],
            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'].map(color => color + '10')[index],
            borderWidth: 2,
            tension: 0.3,
            fill: false,
            pointRadius: 3,
            pointHoverRadius: 5,
            pointBackgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'][index],
            pointBorderColor: '#fff',
            pointBorderWidth: 1,
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'][index],
            pointHoverBorderWidth: 2
        }))
    },
    options: {
        responsive: true,
        animation: {
            duration: 1000,
            easing: 'easeOutCubic'
        },
        interaction: {
            mode: 'index',
            intersect: false,
        },
        plugins: {
            legend: {
                position: 'top',
                labels: {
                    usePointStyle: true,
                    padding: 15,
                    font: {
                        size: 11
                    }
                }
            },
            title: {
                display: true,
                text: 'Évolution du stock par produit sur 6 mois',
                font: {
                    size: 14,
                    weight: 'normal'
                },
                padding: 15
            },
            tooltip: {
                backgroundColor: 'rgba(0, 0, 0, 0.7)',
                titleColor: '#fff',
                bodyColor: '#fff',
                borderColor: '#ddd',
                borderWidth: 1,
                cornerRadius: 4,
                displayColors: true,
                callbacks: {
                    label: function(context) {
                        let label = context.dataset.label || '';
                        if (label) {
                            label += ': ';
                        }
                        if (context.parsed.y !== null) {
                            label += context.parsed.y + ' unités';
                        }
                        return label;
                    }
                }
            }
        },
        scales: {
            x: {
                display: true,
                title: {
                    display: true,
                    text: 'Mois',
                    font: {
                        size: 12
                    }
                },
                grid: {
                    display: false
                }
            },
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Quantité en stock',
                    font: {
                        size: 12
                    }
                },
                grid: {
                    color: 'rgba(0, 0, 0, 0.03)'
                },
                ticks: {
                    callback: function(value) {
                        return value + ' pcs';
                    }
                }
            }
        }
    }
});

// Graphique ventes/sorties
const salesCtx = document.getElementById('salesChart').getContext('2d');
const salesData = @json($salesData);
const salesChart = new Chart(salesCtx, {
    type: 'bar',
    data: {
        labels: salesData.labels,
        datasets: [{
            label: 'Ventes',
            data: salesData.sales,
            backgroundColor: 'rgba(34, 197, 94, 0.8)',
            borderColor: 'rgb(34, 197, 94)',
            borderWidth: 1
        }, {
            label: 'Sorties',
            data: salesData.exits,
            backgroundColor: 'rgba(239, 68, 68, 0.8)',
            borderColor: 'rgb(239, 68, 68)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Ventes et sorties par jour de la semaine'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Nombre d\'unités'
                }
            }
        }
    }
});

// Graphique mouvements par catégorie
const categoryMovementsCtx = document.getElementById('categoryMovementsChart').getContext('2d');
const categoryMovementsData = @json($categoryMovements);
const categoryMovementsChart = new Chart(categoryMovementsCtx, {
    type: 'bar',
    data: {
        labels: categoryMovementsData.labels,
        datasets: [{
            label: 'Entrées',
            data: categoryMovementsData.entries,
            backgroundColor: 'rgba(59, 130, 246, 0.8)',
            borderColor: 'rgb(59, 130, 246)',
            borderWidth: 1
        }, {
            label: 'Sorties',
            data: categoryMovementsData.exits,
            backgroundColor: 'rgba(239, 68, 68, 0.8)',
            borderColor: 'rgb(239, 68, 68)',
            borderWidth: 1
        }]
    },
    options: {
        responsive: true,
        plugins: {
            legend: {
                position: 'top',
            },
            title: {
                display: true,
                text: 'Mouvements de stock par catégorie'
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                title: {
                    display: true,
                    text: 'Nombre de mouvements'
                }
            }
        }
    }
});

// Fonction de rafraîchissement en temps réel
function refreshDashboard() {
    fetch('/api/dashboard/refresh')
        .then(response => response.json())
        .then(data => {
            // Mettre à jour les indicateurs
            document.getElementById('total-products').textContent = data.total_products;
            document.getElementById('stock-value').textContent = new Intl.NumberFormat('fr-FR', { style: 'currency', currency: 'EUR' }).format(data.total_stock_value);
            document.getElementById('low-stock').textContent = data.low_stock_products;
            document.getElementById('needs-forecast').textContent = data.needs_forecast;
            
            // Mettre à jour les graphiques
            updateCharts(data);
            
            // Mettre à jour le tableau
            updateMovementsTable(data.recent_movements);
        })
        .catch(error => {
            console.error('Erreur de rafraîchissement:', error);
        });
}

// Mise à jour automatique toutes les 30 secondes
setInterval(refreshDashboard, 30000);

function updateCharts(data) {
    // Mettre à jour le graphique d'évolution de stock par produit
    if (data.stock_evolution) {
        stockEvolutionChart.data.labels = data.stock_evolution.labels;
        stockEvolutionChart.data.datasets = data.stock_evolution.datasets.map((dataset, index) => ({
            label: dataset.label,
            data: dataset.data,
            borderColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'][index],
            backgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'].map(color => color + '10')[index],
            borderWidth: 2,
            tension: 0.3,
            fill: false,
            pointRadius: 3,
            pointHoverRadius: 5,
            pointBackgroundColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'][index],
            pointBorderColor: '#fff',
            pointBorderWidth: 1,
            pointHoverBackgroundColor: '#fff',
            pointHoverBorderColor: ['#3B82F6', '#10B981', '#F59E0B', '#EF4444'][index],
            pointHoverBorderWidth: 2
        }));
        stockEvolutionChart.update('none'); // Pas d'animation lors de la mise à jour
    }
    
    // Mettre à jour le graphique des ventes/sorties
    if (data.sales_data) {
        salesChart.data.labels = data.sales_data.labels;
        salesChart.data.datasets[0].data = data.sales_data.sales;
        salesChart.data.datasets[1].data = data.sales_data.exits;
        salesChart.update('none');
    }
    
    // Mettre à jour le graphique des mouvements par catégorie
    if (data.category_movements) {
        categoryMovementsChart.data.labels = data.category_movements.labels;
        categoryMovementsChart.data.datasets[0].data = data.category_movements.entries;
        categoryMovementsChart.data.datasets[1].data = data.category_movements.exits;
        categoryMovementsChart.update('none');
    }
}

function updateMovementsTable(movements) {
    const tableBody = document.getElementById('movements-table');
    tableBody.innerHTML = '';
    
    movements.forEach(movement => {
        const row = document.createElement('tr');
        row.innerHTML = `
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${movement.date}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${movement.product}</td>
            <td class="px-6 py-4 whitespace-nowrap">
                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full ${movement.type === 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800'}">
                    ${movement.type === 'in' ? 'Entrée' : 'Sortie'}
                </span>
            </td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${movement.quantity}</td>
            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${movement.reason}</td>
        `;
        tableBody.appendChild(row);
    });
}
</script>

<style>
/* Styles avancés pour le dashboard moderne */

/* Animations de fond */
@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

@keyframes slideInFromLeft {
    0% { opacity: 0; transform: translateX(-50px); }
    100% { opacity: 1; transform: translateX(0); }
}

@keyframes slideInFromRight {
    0% { opacity: 0; transform: translateX(50px); }
    100% { opacity: 1; transform: translateX(0); }
}

@keyframes slideInFromTop {
    0% { opacity: 0; transform: translateY(-50px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes slideInFromBottom {
    0% { opacity: 0; transform: translateY(50px); }
    100% { opacity: 1; transform: translateY(0); }
}

@keyframes scaleIn {
    0% { opacity: 0; transform: scale(0.8); }
    100% { opacity: 1; transform: scale(1); }
}

@keyframes shimmer {
    0% { background-position: -1000px 0; }
    100% { background-position: 1000px 0; }
}

@keyframes glow {
    0%, 100% { box-shadow: 0 0 20px rgba(99, 102, 241, 0.3); }
    50% { box-shadow: 0 0 40px rgba(99, 102, 241, 0.6); }
}

@keyframes wave {
    0% { transform: translateX(0) translateY(0); }
    25% { transform: translateX(-20px) translateY(-10px); }
    50% { transform: translateX(20px) translateY(10px); }
    75% { transform: translateX(-10px) translateY(5px); }
    100% { transform: translateX(0) translateY(0); }
}

/* Classes d'animation */
.fade-in {
    animation: fadeIn 0.8s ease-out;
}

.fade-in-up {
    animation: slideInFromBottom 0.8s ease-out;
    animation-fill-mode: both;
}

.fade-in-left {
    animation: slideInFromLeft 0.8s ease-out;
    animation-fill-mode: both;
}

.fade-in-right {
    animation: slideInFromRight 0.8s ease-out;
    animation-fill-mode: both;
}

.scale-in {
    animation: scaleIn 0.6s ease-out;
    animation-fill-mode: both;
}

/* Effet de brillance */
.shimmer {
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
    background-size: 1000px 100%;
    animation: shimmer 2s infinite;
}

/* Effet de lueur */
.glow-effect {
    animation: glow 2s ease-in-out infinite;
}

/* Effet de flottement */
.float-animation {
    animation: float 6s ease-in-out infinite;
}

/* Effet de vague */
.wave-animation {
    animation: wave 3s ease-in-out infinite;
}

/* Amélioration des indicateurs */
.indicator-card {
    position: relative;
    overflow: hidden;
}

.indicator-card::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: linear-gradient(
        45deg,
        transparent 30%,
        rgba(255, 255, 255, 0.1) 50%,
        transparent 70%
    );
    transform: rotate(45deg);
    transition: all 0.5s;
    opacity: 0;
}

.indicator-card:hover::before {
    animation: shimmer 0.5s ease-out;
}

/* Amélioration des graphiques */
.chart-container {
    position: relative;
    overflow: hidden;
}

.chart-container::after {
    content: '';
    position: absolute;
    top: 0;
    left: 0;
    right: 0;
    height: 3px;
    background: linear-gradient(90deg, #6366f1, #8b5cf6, #ec4899);
    border-radius: 3px;
    opacity: 0;
    transition: opacity 0.3s;
}

.chart-container:hover::after {
    opacity: 1;
}

/* Amélioration des boutons */
.btn-gradient {
    position: relative;
    overflow: hidden;
}

.btn-gradient::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
    transition: left 0.5s;
}

.btn-gradient:hover::before {
    left: 100%;
}

/* Amélioration du tableau */
.table-hover tbody tr {
    transition: all 0.3s ease;
}

.table-hover tbody tr:hover {
    background: linear-gradient(90deg, rgba(99, 102, 241, 0.05), rgba(139, 92, 246, 0.05));
    transform: translateX(5px);
}

/* Effet de chargement */
.loading-skeleton {
    background: linear-gradient(90deg, #f3f4f6 25%, #e5e7eb 50%, #f3f4f6 75%);
    background-size: 200% 100%;
    animation: shimmer 1.5s infinite;
}

/* Responsive amélioré */
@media (max-width: 768px) {
    .fade-in-up {
        animation: slideInFromBottom 0.6s ease-out;
    }
    
    .indicator-card {
        transform: scale(0.95);
        transition: transform 0.3s ease;
    }
    
    .indicator-card:hover {
        transform: scale(1);
    }
}

/* Effet de parallaxe pour les éléments de fond */
.parallax-element {
    position: absolute;
    pointer-events: none;
    will-change: transform;
}

/* Amélioration des transitions */
.smooth-transition {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Effet de profondeur */
.depth-effect {
    transform-style: preserve-3d;
    perspective: 1000px;
}

.depth-effect:hover {
    transform: translateZ(20px);
}

/* Animation de particules */
@keyframes particle {
    0% {
        opacity: 0;
        transform: translateY(0) scale(0);
    }
    10% {
        opacity: 1;
        transform: translateY(-10px) scale(1);
    }
    100% {
        opacity: 0;
        transform: translateY(-100px) scale(0.5);
    }
}

.particle {
    position: absolute;
    pointer-events: none;
    animation: particle 3s ease-out infinite;
}

/* Effet de néon */
.neon-glow {
    text-shadow: 0 0 10px currentColor, 0 0 20px currentColor, 0 0 30px currentColor;
}

/* Amélioration de l'accessibilité */
@media (prefers-reduced-motion: reduce) {
    * {
        animation-duration: 0.01ms !important;
        animation-iteration-count: 1 !important;
        transition-duration: 0.01ms !important;
    }
}

/* Mode sombre prêt */
@media (prefers-color-scheme: dark) {
    .indicator-card {
        background: rgba(31, 41, 55, 0.9);
        border-color: rgba(75, 85, 99, 0.5);
    }
    
    .chart-container {
        background: rgba(31, 41, 55, 0.9);
        border-color: rgba(75, 85, 99, 0.5);
    }
}
</style>
@endsection
