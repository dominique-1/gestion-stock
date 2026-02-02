@extends('layouts.app')

@section('title', 'Analytics - Smart Alerts')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-900 via-indigo-900 to-blue-900">
    <!-- Header -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-purple-600/30 via-indigo-600/30 to-blue-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-purple-400 via-indigo-500 to-blue-600 bg-clip-text text-transparent animate-gradient">
                            Analytics Smart Alerts
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Analyse intelligente des alertes système</p>
                </div>
                <a href="{{ route('smart-alerts.index') }}" class="bg-white/10 backdrop-blur-md text-white px-6 py-3 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300">
                    <i class="fas fa-arrow-left mr-2"></i>Retour
                </a>
            </div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-3xl p-6 border border-green-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-400 text-sm font-bold uppercase tracking-wider mb-1">Taux de lecture</p>
                        <p class="text-3xl font-black text-white">{{ $analytics['performance']['read_rate'] }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-eye text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-3xl p-6 border border-blue-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-400 text-sm font-bold uppercase tracking-wider mb-1">Taux d'ignorance</p>
                        <p class="text-3xl font-black text-white">{{ $analytics['performance']['dismissal_rate'] }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-times text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-3xl p-6 border border-purple-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-400 text-sm font-bold uppercase tracking-wider mb-1">Emails envoyés</p>
                        <p class="text-3xl font-black text-white">{{ $analytics['performance']['email_delivery_rate'] }}%</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-envelope text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 rounded-3xl p-6 border border-yellow-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-400 text-sm font-bold uppercase tracking-wider mb-1">Temps moyen</p>
                        <p class="text-3xl font-black text-white">{{ $analytics['performance']['avg_resolution_time'] }}min</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clock text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Tendances -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <div class="w-3 h-3 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full mr-3 animate-pulse"></div>
                    Tendances sur 7 jours
                </h3>
                <div class="h-64">
                    <canvas id="trendsChart"></canvas>
                </div>
            </div>

            <!-- Distribution -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <div class="w-3 h-3 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-3 animate-pulse"></div>
                    Distribution par type
                </h3>
                <div class="h-64">
                    <canvas id="distributionChart"></canvas>
                </div>
            </div>
        </div>

        <!-- Prédictions -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 mb-8">
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <div class="w-3 h-3 bg-gradient-to-r from-yellow-400 to-orange-400 rounded-full mr-3 animate-pulse"></div>
                Prédictions IA
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-2xl p-6 border border-blue-500/30">
                    <h4 class="text-lg font-bold text-white mb-4">Prochaines 24h</h4>
                    <p class="text-3xl font-black text-blue-400 mb-2">{{ $analytics['predictions']['next_24h'] }}</p>
                    <p class="text-gray-300 text-sm">Alertes attendues</p>
                </div>
                
                <div class="bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-2xl p-6 border border-purple-500/30">
                    <h4 class="text-lg font-bold text-white mb-4">Semaine prochaine</h4>
                    <p class="text-3xl font-black text-purple-400 mb-2">{{ $analytics['predictions']['next_week'] }}</p>
                    <p class="text-gray-300 text-sm">Alertes prévues</p>
                </div>
                
                <div class="bg-gradient-to-br from-red-500/20 to-orange-500/20 rounded-2xl p-6 border border-red-500/30">
                    <h4 class="text-lg font-bold text-white mb-4">Risque critique</h4>
                    <p class="text-3xl font-black text-red-400 mb-2">{{ $analytics['predictions']['critical_probability'] }}%</p>
                    <p class="text-gray-300 text-sm">Probabilité</p>
                </div>
            </div>
        </div>

        <!-- Recommandations -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
            <h3 class="text-2xl font-bold text-white mb-6 flex items-center">
                <div class="w-3 h-3 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-3 animate-pulse"></div>
                Recommandations IA
            </h3>
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                @foreach($analytics['predictions']['recommendations'] as $recommendation)
                <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-2xl p-6 border border-green-500/30">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center mr-4">
                            <i class="fas fa-lightbulb text-white"></i>
                        </div>
                        <h4 class="text-white font-bold">Optimisation</h4>
                    </div>
                    <p class="text-gray-300 text-sm">{{ $recommendation }}</p>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>

<!-- Chart.js -->
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
// Graphique des tendances
const trendsCtx = document.getElementById('trendsChart').getContext('2d');
const trendsData = @json($analytics['trends']);

new Chart(trendsCtx, {
    type: 'line',
    data: {
        labels: trendsData.map(item => item.date),
        datasets: [{
            label: 'Total',
            data: trendsData.map(item => item.total),
            borderColor: 'rgb(147, 51, 234)',
            backgroundColor: 'rgba(147, 51, 234, 0.1)',
            tension: 0.4
        }, {
            label: 'Critiques',
            data: trendsData.map(item => item.critical),
            borderColor: 'rgb(239, 68, 68)',
            backgroundColor: 'rgba(239, 68, 68, 0.1)',
            tension: 0.4
        }, {
            label: 'Warnings',
            data: trendsData.map(item => item.warning),
            borderColor: 'rgb(245, 158, 11)',
            backgroundColor: 'rgba(245, 158, 11, 0.1)',
            tension: 0.4
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                labels: { color: 'white' }
            }
        },
        scales: {
            y: {
                beginAtZero: true,
                ticks: { color: 'white' },
                grid: { color: 'rgba(255, 255, 255, 0.1)' }
            },
            x: {
                ticks: { color: 'white' },
                grid: { color: 'rgba(255, 255, 255, 0.1)' }
            }
        }
    }
});

// Graphique de distribution
const distributionCtx = document.getElementById('distributionChart').getContext('2d');
const distributionData = @json($analytics['distribution']);

new Chart(distributionCtx, {
    type: 'doughnut',
    data: {
        labels: distributionData.map(item => item.name),
        datasets: [{
            data: distributionData.map(item => item.count),
            backgroundColor: [
                'rgba(239, 68, 68, 0.8)',
                'rgba(245, 158, 11, 0.8)',
                'rgba(59, 130, 246, 0.8)',
                'rgba(147, 51, 234, 0.8)',
                'rgba(34, 197, 94, 0.8)'
            ],
            borderWidth: 0
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: {
                position: 'right',
                labels: { color: 'white' }
            }
        }
    }
});
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
</style>
@endsection
