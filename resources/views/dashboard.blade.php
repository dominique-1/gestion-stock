@extends('layouts.app')

@section('title', 'Dashboard Professionnel')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-slate-50 to-gray-100">
    <!-- Header professionnel -->
    <div class="bg-white shadow-sm border-b">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between items-center py-6">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Tableau de Bord</h1>
                    <p class="text-gray-600">Vue d'ensemble en temps réel</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-blue-50 text-blue-700 px-4 py-2 rounded-lg">
                        <span class="font-semibold">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                    <button onclick="refreshDashboard()" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-sync-alt mr-2"></i>Rafraîchir
                    </button>
                </div>
            </div>
        </div>
    </div>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Indicateurs clés en temps réel -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Total Produits</h3>
                        <p class="text-2xl font-bold text-gray-900" id="total-products">{{ $data['total_products'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-box text-blue-600"></i>
                    </div>
                </div>
                <div class="text-sm text-green-600">
                    <i class="fas fa-arrow-up mr-1"></i>+12% ce mois
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Valeur Stock</h3>
                        <p class="text-2xl font-bold text-gray-900" id="stock-value">{{ number_format($data['total_stock_value'] ?? 0, 2) }} €</p>
                    </div>
                    <div class="w-12 h-12 bg-green-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-euro-sign text-green-600"></i>
                    </div>
                </div>
                <div class="text-sm text-green-600">
                    <i class="fas fa-arrow-up mr-1"></i>+8% ce mois
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Stock Faible</h3>
                        <p class="text-2xl font-bold text-yellow-600" id="low-stock">{{ $data['low_stock_products'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-yellow-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-yellow-600"></i>
                    </div>
                </div>
                <div class="text-sm text-red-600">
                    <i class="fas fa-arrow-up mr-1"></i>+3 cette semaine
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <div class="flex items-center justify-between mb-4">
                    <div>
                        <h3 class="text-sm font-medium text-gray-600">Prévision Besoins</h3>
                        <p class="text-2xl font-bold text-purple-600" id="needs-forecast">{{ $data['needs_forecast'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-purple-100 rounded-lg flex items-center justify-center">
                        <i class="fas fa-chart-line text-purple-600"></i>
                    </div>
                </div>
                <div class="text-sm text-purple-600">
                    <i class="fas fa-info-circle mr-1"></i>Produits à réapprovisionner
                </div>
            </div>
        </div>

        <!-- Graphiques interactifs Chart.js -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Graphique évolution stock par produit -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Évolution Stock par Produit</h3>
                <canvas id="stockEvolutionChart" width="400" height="200"></canvas>
            </div>

            <!-- Graphique ventes/sorties -->
            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Ventes / Sorties</h3>
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
        </div>

        <!-- Graphique mouvements par catégorie -->
        <div class="bg-white rounded-lg shadow p-6 mb-8">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Mouvements par Catégorie</h3>
            <canvas id="categoryMovementsChart" width="800" height="300"></canvas>
        </div>

        <!-- Tableau des mouvements récents -->
        <div class="bg-white rounded-lg shadow mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h3 class="text-lg font-semibold text-gray-900">Mouvements Récents</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Produit</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Quantité</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motif</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="movements-table">
                        @foreach($movements as $movement)
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movement->moved_at->format('d/m/Y H:i') }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movement->product->name ?? 'Produit' }}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full {{ $movement->type == 'in' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ $movement->type == 'in' ? 'Entrée' : 'Sortie' }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movement->quantity }}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">{{ $movement->reason }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white rounded-lg shadow p-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-4">Actions Rapides</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('products.create') }}" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 transition-colors text-center">
                    <i class="fas fa-plus mr-2"></i>Ajouter Produit
                </a>
                <a href="{{ route('movements.create') }}" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 transition-colors text-center">
                    <i class="fas fa-exchange-alt mr-2"></i>Mouvement
                </a>
                <a href="{{ route('alerts.index') }}" class="bg-yellow-600 text-white px-4 py-2 rounded-lg hover:bg-yellow-700 transition-colors text-center">
                    <i class="fas fa-bell mr-2"></i>Alertes
                </a>
                <a href="{{ route('exports.index') }}" class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 transition-colors text-center">
                    <i class="fas fa-download mr-2"></i>Exporter
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
/* Optimisation des performances */
.chart-container {
    position: relative;
    height: 200px;
}

/* Animations de chargement */
@keyframes pulse {
    0%, 100% { opacity: 1; }
    50% { opacity: 0.5; }
}

.loading {
    animation: pulse 2s cubic-bezier(0.4, 0, 0.6, 1) infinite;
}

/* Responsive */
@media (max-width: 768px) {
    .grid-cols-1 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
    
    .grid-cols-2 {
        grid-template-columns: repeat(2, minmax(0, 1fr));
    }
    
    .grid-cols-4 {
        grid-template-columns: repeat(1, minmax(0, 1fr));
    }
}
</style>
@endsection
