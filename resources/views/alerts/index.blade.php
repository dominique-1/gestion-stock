@extends('layouts.app')

@section('title', 'Alertes - Design Spectaculaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-red-900 via-pink-900 to-purple-900">
    <!-- Header spectaculaire -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-red-600/30 via-pink-600/30 to-purple-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-red-400 via-pink-500 to-purple-600 bg-clip-text text-transparent animate-gradient">
                            Centre d'Alertes
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Surveillez et gérez toutes vos alertes stock</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Messages flash -->
                    @if(session()->has('success'))
                    <div class="bg-green-500/20 backdrop-blur-md border border-green-500/30 text-green-300 px-6 py-3 rounded-2xl">
                        <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
                    </div>
                    @endif
                    @if(session()->has('error'))
                    <div class="bg-red-500/20 backdrop-blur-md border border-red-500/30 text-red-300 px-6 py-3 rounded-2xl">
                        <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
                    </div>
                    @endif
                </div>
            </div>
        </div>
        
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-4 h-4 bg-red-400 rounded-full animate-bounce"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-pink-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-10 left-1/4 w-3 h-3 bg-purple-400 rounded-full animate-pulse"></div>
            <div class="absolute top-1/3 right-1/4 w-5 h-5 bg-orange-400 rounded-full animate-bounce animation-delay-2000"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Actions rapides -->
        <div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-8">
            <a href="{{ route('alerts.create') }}" class="group bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-green-500/50 flex items-center justify-center">
                <i class="fas fa-plus-circle mr-2 group-hover:rotate-90 transition-transform duration-300"></i>
                <span>Créer</span>
            </a>
            <a href="{{ route('alerts.send-emails') }}" class="group bg-gradient-to-r from-blue-500 to-cyan-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-blue-500/50 flex items-center justify-center">
                <i class="fas fa-envelope mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                <span>Envoyer</span>
            </a>
            <a href="{{ route('alerts.emails') }}" class="group bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-indigo-500/50 flex items-center justify-center">
                <i class="fas fa-history mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                <span>Historique</span>
            </a>
            <a href="{{ route('alerts.stats') }}" class="group bg-gradient-to-r from-purple-500 to-pink-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-purple-500/50 flex items-center justify-center">
                <i class="fas fa-chart-bar mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                <span>Stats</span>
            </a>
            <a href="{{ route('alerts.test-email') }}" class="group bg-gradient-to-r from-yellow-500 to-orange-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-yellow-500/50 flex items-center justify-center">
                <i class="fas fa-paper-plane mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                <span>Test</span>
            </a>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-red-500/20 to-pink-500/20 rounded-3xl p-6 border border-red-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-400 text-sm font-bold uppercase tracking-wider mb-1">Alertes Critiques</p>
                        <p class="text-3xl font-black text-white">{{ $stats['critical'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center animate-pulse">
                        <i class="fas fa-exclamation-triangle text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-yellow-500/20 to-orange-500/20 rounded-3xl p-6 border border-yellow-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-yellow-400 text-sm font-bold uppercase tracking-wider mb-1">Alertes Warning</p>
                        <p class="text-3xl font-black text-white">{{ $stats['warning'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exclamation text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-3xl p-6 border border-blue-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-400 text-sm font-bold uppercase tracking-wider mb-1">Alertes Info</p>
                        <p class="text-3xl font-black text-white">{{ $stats['info'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-info-circle text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-3xl p-6 border border-green-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-400 text-sm font-bold uppercase tracking-wider mb-1">Non Lues</p>
                        <p class="text-3xl font-black text-white">{{ $stats['unread'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-envelope text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Filtres spectaculaires -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 mb-8 border border-white/20">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Type</label>
                    <select name="type" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                        <option value="">Tous les types</option>
                        <option value="min_stock" {{ request('type') == 'min_stock' ? 'selected' : '' }}>📉 Stock faible</option>
                        <option value="overstock" {{ request('type') == 'overstock' ? 'selected' : '' }}>📈 Surstock</option>
                        <option value="expiry_soon" {{ request('type') == 'expiry_soon' ? 'selected' : '' }}>⏰ Expiration</option>
                        <option value="critical" {{ request('type') == 'critical' ? 'selected' : '' }}>🚨 Critique</option>
                        <option value="warning" {{ request('type') == 'warning' ? 'selected' : '' }}>⚠️ Warning</option>
                        <option value="info" {{ request('type') == 'info' ? 'selected' : '' }}>ℹ️ Info</option>
                    </select>
                </div>

                <div>
                    <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Niveau</label>
                    <select name="level" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                        <option value="">Tous les niveaux</option>
                        <option value="critical" {{ request('level') == 'critical' ? 'selected' : '' }}>🚨 Critique</option>
                        <option value="warning" {{ request('level') == 'warning' ? 'selected' : '' }}>⚠️ Warning</option>
                        <option value="info" {{ request('level') == 'info' ? 'selected' : '' }}>ℹ️ Info</option>
                    </select>
                </div>

                <div>
                    <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Statut</label>
                    <select name="is_read" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                        <option value="">Toutes</option>
                        <option value="0" {{ request('is_read') == '0' ? 'selected' : '' }}>🔴 Non lues</option>
                        <option value="1" {{ request('is_read') == '1' ? 'selected' : '' }}>🟢 Lues</option>
                    </select>
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-red-600 to-pink-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-red-500/50">
                        <i class="fas fa-filter mr-3"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des alertes -->
        <div class="space-y-6">
            @if($paginatedAlerts->count() > 0)
                @foreach($paginatedAlerts as $alert)
                <div class="group relative">
                    <!-- Fond animé -->
                    <div class="absolute inset-0 bg-gradient-to-br from-red-600/20 via-pink-600/20 to-purple-600/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                    
                    <!-- Carte alerte -->
                    <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105 {{ $alert->is_read ? 'opacity-60' : '' }}">
                        <div class="flex items-start justify-between">
                            <div class="flex items-start space-x-4">
                                <!-- Icône d'alerte -->
                                <div class="w-12 h-12 {{ $alert->level == 'critical' ? 'bg-gradient-to-br from-red-500 to-pink-500' : ($alert->level == 'warning' ? 'bg-gradient-to-br from-yellow-500 to-orange-500' : 'bg-gradient-to-br from-blue-500 to-indigo-500') }} rounded-xl flex items-center justify-center shadow-lg {{ !$alert->is_read ? 'animate-pulse' : '' }}">
                                    @switch($alert->level)
                                        @case('critical')
                                            <i class="fas fa-exclamation-triangle text-white text-xl"></i>
                                            @break
                                        @case('warning')
                                            <i class="fas fa-exclamation text-white text-xl"></i>
                                            @break
                                        @case('info')
                                            <i class="fas fa-info-circle text-white text-xl"></i>
                                            @break
                                        @default
                                            <i class="fas fa-bell text-white text-xl"></i>
                                    @endswitch
                                </div>
                                
                                <!-- Contenu -->
                                <div class="flex-1">
                                    <div class="flex items-center space-x-3 mb-2">
                                        <h3 class="text-xl font-black text-white">{{ $alert->type }}</h3>
                                        @if(!$alert->is_read)
                                            <div class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold animate-pulse">
                                                Non lue
                                            </div>
                                        @endif
                                    </div>
                                    
                                    <p class="text-gray-300 text-lg mb-4">{{ $alert->message }}</p>
                                    
                                    <div class="flex items-center space-x-4 text-sm">
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-calendar text-gray-400"></i>
                                            <span class="text-gray-400">{{ $alert->created_at->format('d/m/Y H:i') }}</span>
                                        </div>
                                        
                                        @if($alert->product)
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-box text-gray-400"></i>
                                            <span class="text-gray-400">{{ $alert->product->name }}</span>
                                        </div>
                                        @endif
                                        
                                        @if($alert->email_sent_at)
                                        <div class="flex items-center space-x-2">
                                            <i class="fas fa-paper-plane text-green-400"></i>
                                            <span class="text-green-400">Email envoyé</span>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Actions -->
                            <div class="flex items-center space-x-3">
                                <a href="{{ route('alerts.show', $alert->id) }}" 
                                   class="w-10 h-10 bg-blue-500/20 hover:bg-blue-500/30 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                    <i class="fas fa-eye text-blue-400"></i>
                                </a>
                                
                                @if(!$alert->is_read)
                                    <form method="POST" action="{{ route('alerts.mark-read', $alert->id) }}" class="inline">
                                        @csrf
                                        <button type="submit" class="w-10 h-10 bg-green-500/20 hover:bg-green-500/30 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                            <i class="fas fa-check text-green-400"></i>
                                        </button>
                                    </form>
                                @endif
                                
                                <form method="POST" action="{{ route('alerts.destroy', $alert->id) }}" class="inline" onsubmit="return confirm('Supprimer cette alerte ?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="w-10 h-10 bg-red-500/20 hover:bg-red-500/30 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-trash text-red-400"></i>
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            @else
            <div class="text-center py-20">
                <div class="w-32 h-32 mx-auto mb-6 relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-600 to-gray-700 rounded-full animate-pulse"></div>
                    <div class="absolute inset-2 bg-slate-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-bell-slash text-4xl text-gray-400"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-white mb-4">Aucune alerte trouvée</h3>
                <p class="text-gray-400 text-lg mb-8">Aucune alerte ne correspond à vos critères</p>
                <a href="{{ route('alerts.create') }}" class="inline-block bg-gradient-to-r from-green-500 to-emerald-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-green-500/50">
                    <i class="fas fa-plus-circle mr-3"></i>Créer une alerte
                </a>
            </div>
            @endif
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
