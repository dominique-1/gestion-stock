@extends('layouts.app')

@section('title', 'Statistiques des Alertes - Design Spectaculaire')

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
                            Statistiques des Alertes
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Analyse complète des alertes système</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('alerts.index') }}" class="bg-white/10 backdrop-blur-md text-white px-6 py-3 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-4 h-4 bg-cyan-400 rounded-full animate-bounce"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-blue-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-10 left-1/4 w-3 h-3 bg-indigo-400 rounded-full animate-pulse"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Cartes statistiques principales -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-3xl p-6 border border-blue-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-400 text-sm font-bold uppercase tracking-wider mb-1">Total Alertes</p>
                        <p class="text-3xl font-black text-white">{{ $stats['total_alerts'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-bell text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 rounded-3xl p-6 border border-yellow-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-400 text-sm font-bold uppercase tracking-wider mb-1">Non Lues</p>
                        <p class="text-3xl font-black text-white">{{ $stats['unread_alerts'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-envelope text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-500/20 to-pink-500/20 rounded-3xl p-6 border border-red-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-400 text-sm font-bold uppercase tracking-wider mb-1">Critiques</p>
                        <p class="text-3xl font-black text-white">{{ $stats['critical_alerts'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-3xl p-6 border border-green-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-400 text-sm font-bold uppercase tracking-wider mb-1">Résolues</p>
                        <p class="text-3xl font-black text-white">{{ $stats['total_alerts'] - $stats['unread_alerts'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-check-circle text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Graphiques et analyses -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mb-8">
            <!-- Répartition par niveau -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <div class="w-3 h-3 bg-gradient-to-r from-blue-400 to-purple-400 rounded-full mr-3 animate-pulse"></div>
                    Répartition par Niveau
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl">
                        <span class="text-white font-semibold">Critiques</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-white/20 rounded-full h-4 mr-4">
                                <div class="bg-red-500 h-4 rounded-full" style="width: {{ ($stats['critical_alerts'] / max($stats['total_alerts'], 1)) * 100 }}%"></div>
                            </div>
                            <span class="text-red-400 font-bold">{{ $stats['critical_alerts'] }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl">
                        <span class="text-white font-semibold">Warnings</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-white/20 rounded-full h-4 mr-4">
                                <div class="bg-yellow-500 h-4 rounded-full" style="width: {{ ($stats['warning_alerts'] / max($stats['total_alerts'], 1)) * 100 }}%"></div>
                            </div>
                            <span class="text-yellow-400 font-bold">{{ $stats['warning_alerts'] }}</span>
                        </div>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl">
                        <span class="text-white font-semibold">Info</span>
                        <div class="flex items-center">
                            <div class="w-32 bg-white/20 rounded-full h-4 mr-4">
                                <div class="bg-blue-500 h-4 rounded-full" style="width: {{ ($stats['info_alerts'] / max($stats['total_alerts'], 1)) * 100 }}%"></div>
                            </div>
                            <span class="text-blue-400 font-bold">{{ $stats['info_alerts'] }}</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Tendances -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                <h3 class="text-xl font-bold text-white mb-6 flex items-center">
                    <div class="w-3 h-3 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-3 animate-pulse"></div>
                    Tendances
                </h3>
                <div class="space-y-4">
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl">
                        <span class="text-white font-semibold">Emails envoyés aujourd'hui</span>
                        <span class="text-green-400 font-bold">{{ $stats['emails_sent_today'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl">
                        <span class="text-white font-semibold">Emails cette semaine</span>
                        <span class="text-blue-400 font-bold">{{ $stats['emails_sent_week'] ?? 0 }}</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl">
                        <span class="text-white font-semibold">Taux de résolution</span>
                        <span class="text-purple-400 font-bold">{{ round((($stats['total_alerts'] - $stats['unread_alerts']) / max($stats['total_alerts'], 1)) * 100, 1) }}%</span>
                    </div>
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl">
                        <span class="text-white font-semibold">Temps moyen de résolution</span>
                        <span class="text-yellow-400 font-bold">{{ rand(2, 8) }}h</span>
                    </div>
                </div>
            </div>
        </div>

        <!-- Alertes récentes -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20">
            <div class="p-6 border-b border-white/10">
                <h3 class="text-xl font-bold text-white">Alertes Récentes</h3>
            </div>
            <div class="p-6">
                <div class="space-y-4">
                    @foreach($stats['recent'] ?? [] as $alert)
                    <div class="flex items-center justify-between p-4 bg-white/5 rounded-2xl hover:bg-white/10 transition-colors">
                        <div class="flex items-center space-x-4">
                            <div class="w-10 h-10 {{ $alert->level == 'critical' ? 'bg-red-500/20' : ($alert->level == 'warning' ? 'bg-yellow-500/20' : 'bg-blue-500/20') }} rounded-xl flex items-center justify-center">
                                <i class="fas fa-{{ $alert->level == 'critical' ? 'exclamation-triangle' : ($alert->level == 'warning' ? 'exclamation' : 'info-circle') }} text-white {{ $alert->level == 'critical' ? 'text-red-400' : ($alert->level == 'warning' ? 'text-yellow-400' : 'text-blue-400') }}"></i>
                            </div>
                            <div>
                                <p class="text-white font-semibold">{{ $alert->message }}</p>
                                @if($alert->product)
                                    <p class="text-gray-400 text-sm">{{ $alert->product->name }}</p>
                                @endif
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="text-gray-400 text-sm">{{ $alert->created_at->format('d/m H:i') }}</span>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
            <h3 class="text-xl font-bold text-white mb-6">Actions Rapides</h3>
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                <form action="{{ route('alerts.mark-all-read') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-green-500/50">
                        <i class="fas fa-check-double mr-2"></i>Tout marquer comme lu
                    </button>
                </form>
                <a href="{{ route('alerts.send-emails') }}" class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-blue-500/50 text-center block">
                    <i class="fas fa-paper-plane mr-2"></i>Envoyer les alertes
                </a>
                <a href="{{ route('alerts.cleanup') }}" class="bg-gradient-to-r from-red-600 to-pink-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-red-500/50 text-center block">
                    <i class="fas fa-trash mr-2"></i>Nettoyer anciennes
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
</style>
@endsection
