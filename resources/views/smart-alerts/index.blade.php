@extends('layouts.app')

@section('title', 'Smart Alerts - Système Intelligent')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-purple-900 to-pink-900">
    <!-- Header moderne -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/30 via-purple-600/30 to-pink-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-indigo-400 via-purple-500 to-pink-600 bg-clip-text text-transparent animate-gradient">
                            Smart Alerts
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Système intelligent de gestion d'alertes</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-md text-white px-6 py-3 rounded-2xl border border-white/20">
                        <i class="fas fa-brain mr-2 text-purple-400"></i>
                        <span class="font-semibold">IA Activée</span>
                    </div>
                    <button onclick="refreshAlerts()" class="bg-white/10 backdrop-blur-md text-white px-6 py-3 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300">
                        <i class="fas fa-sync-alt mr-2"></i>
                        <span>Actualiser</span>
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-4 h-4 bg-indigo-400 rounded-full animate-bounce"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-purple-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-10 left-1/4 w-3 h-3 bg-pink-400 rounded-full animate-pulse"></div>
            <div class="absolute top-1/3 right-1/4 w-5 h-5 bg-cyan-400 rounded-full animate-bounce animation-delay-2000"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Cartes de statistiques -->
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
                        <p class="text-blue-400 text-sm font-bold uppercase tracking-wider mb-1">Non Lues</p>
                        <p class="text-3xl font-black text-white">{{ $stats['unread'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-envelope text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-3xl p-6 border border-green-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-400 text-sm font-bold uppercase tracking-wider mb-1">Total</p>
                        <p class="text-3xl font-black text-white">{{ $stats['total'] }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-chart-line text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Actions rapides -->
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-8">
            <button onclick="sendEmailAlerts()" class="group bg-gradient-to-r from-blue-500 to-cyan-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-blue-500/50 flex items-center justify-center">
                <i class="fas fa-envelope mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                <span>Envoyer Emails</span>
            </button>
            <button onclick="markAllAsRead()" class="group bg-gradient-to-r from-green-500 to-emerald-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-green-500/50 flex items-center justify-center">
                <i class="fas fa-check-double mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                <span>Tout Lire</span>
            </button>
            <a href="{{ route('smart-alerts.analytics') }}" class="group bg-gradient-to-r from-purple-500 to-pink-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-purple-500/50 flex items-center justify-center">
                <i class="fas fa-chart-bar mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                <span>Analytics</span>
            </a>
            <button onclick="exportAlerts()" class="group bg-gradient-to-r from-yellow-500 to-orange-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-yellow-500/50 flex items-center justify-center">
                <i class="fas fa-download mr-2 group-hover:scale-110 transition-transform duration-300"></i>
                <span>Exporter</span>
            </button>
        </div>

        <!-- Filtres -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 mb-8 border border-white/20">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                <div>
                    <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Type</label>
                    <select name="type" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                        <option value="">Tous les types</option>
                        <option value="stock_min" {{ request('type') == 'stock_min' ? 'selected' : '' }}>📉 Stock minimum</option>
                        <option value="overstock" {{ request('type') == 'overstock' ? 'selected' : '' }}>📈 Surstock</option>
                        <option value="expiry" {{ request('type') == 'expiry' ? 'selected' : '' }}>⏰ Expiration</option>
                        <option value="prediction_risk" {{ request('type') == 'prediction_risk' ? 'selected' : '' }}>🔮 Risque prédit</option>
                        <option value="movement_anomaly" {{ request('type') == 'movement_anomaly' ? 'selected' : '' }}>📊 Anomalie</option>
                    </select>
                </div>

                <div>
                    <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Niveau</label>
                    <select name="level" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                        <option value="">Tous les niveaux</option>
                        <option value="critical" {{ request('level') == 'critical' ? 'selected' : '' }}>🔴 Critique</option>
                        <option value="warning" {{ request('level') == 'warning' ? 'selected' : '' }}>🟡 Warning</option>
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
                    <button type="submit" class="w-full bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-indigo-500/50">
                        <i class="fas fa-filter mr-3"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Liste des alertes -->
        <div class="space-y-6">
            @forelse($alerts as $alert)
            <div class="group relative" data-alert-id="{{ $alert->id }}">
                <!-- Fond animé -->
                <div class="absolute inset-0 bg-gradient-to-br from-indigo-600/20 via-purple-600/20 to-pink-600/20 rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                
                <!-- Carte alerte -->
                <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105 {{ $alert->is_read ? 'opacity-60' : '' }}">
                    <div class="flex items-start justify-between">
                        <div class="flex items-start space-x-4">
                            <!-- Icône d'alerte -->
                            <div class="w-12 h-12 {{ $alert->level == 'critical' ? 'bg-gradient-to-br from-red-500 to-pink-500' : 'bg-gradient-to-br from-yellow-500 to-orange-500' }} rounded-xl flex items-center justify-center shadow-lg {{ !$alert->is_read ? 'animate-pulse' : '' }}">
                                <i class="{{ $alert->icon }} text-white text-xl"></i>
                            </div>
                            
                            <!-- Contenu -->
                            <div class="flex-1">
                                <div class="flex items-center space-x-3 mb-2">
                                    <h3 class="text-xl font-black text-white">{{ $alert->title }}</h3>
                                    @if(!$alert->is_read)
                                        <div class="bg-red-500 text-white px-3 py-1 rounded-full text-xs font-bold animate-pulse">
                                            Non lue
                                        </div>
                                    @endif
                                    @if($alert->email_sent)
                                        <div class="bg-green-500 text-white px-3 py-1 rounded-full text-xs font-bold">
                                            Email envoyé
                                        </div>
                                    @endif
                                </div>
                                
                                <p class="text-gray-300 text-lg mb-4">{{ $alert->message }}</p>
                                
                                @if($alert->data && !empty($alert->data))
                                <div class="bg-white/5 rounded-2xl p-4 mb-4">
                                    <h4 class="text-white font-semibold mb-2">Détails techniques</h4>
                                    <div class="grid grid-cols-2 gap-4 text-sm">
                                        @foreach($alert->data as $key => $value)
                                            <div class="flex justify-between">
                                                <span class="text-gray-400">{{ ucfirst(str_replace('_', ' ', $key)) }}:</span>
                                                <span class="text-white font-semibold">
                                                    @if(is_array($value))
                                                        {{ implode(', ', $value) }}
                                                    @else
                                                        {{ $value }}
                                                    @endif
                                                </span>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                                
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
                                </div>
                            </div>
                        </div>
                        
                        <!-- Actions -->
                        <div class="flex items-center space-x-3">
                            @if(!$alert->is_read)
                                <button onclick="markAsRead('{{ $alert->id }}')" class="w-10 h-10 bg-green-500/20 hover:bg-green-500/30 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                    <i class="fas fa-check text-green-400"></i>
                                </button>
                            @endif
                            
                            <button onclick="dismissAlert('{{ $alert->id }}')" class="w-10 h-10 bg-yellow-500/20 hover:bg-yellow-500/30 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-times text-yellow-400"></i>
                            </button>
                            
                            <button onclick="showAlertDetails('{{ $alert->id }}')" class="w-10 h-10 bg-blue-500/20 hover:bg-blue-500/30 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                <i class="fas fa-eye text-blue-400"></i>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
            @empty
            <div class="text-center py-20">
                <div class="w-32 h-32 mx-auto mb-6 relative">
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-600 to-gray-700 rounded-full animate-pulse"></div>
                    <div class="absolute inset-2 bg-slate-900 rounded-full flex items-center justify-center">
                        <i class="fas fa-bell-slash text-4xl text-gray-400"></i>
                    </div>
                </div>
                <h3 class="text-3xl font-black text-white mb-4">Aucune alerte</h3>
                <p class="text-gray-400 text-lg mb-8">Le système intelligent n'a détecté aucune alerte</p>
            </div>
            @endforelse
        </div>
        
        <!-- Pagination -->
        @if($alerts->hasPages())
        <div class="mt-8 flex justify-center">
            {{ $alerts->links() }}
        </div>
        @endif
    </div>
</div>

<!-- JavaScript pour les interactions -->
<script>
function refreshAlerts() {
    window.location.reload();
}

function sendEmailAlerts() {
    fetch('/smart-alerts/send-emails', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            showNotification('Emails envoyés avec succès!', 'success');
            setTimeout(() => window.location.reload(), 1500);
        }
    });
}

function markAsRead(alertId) {
    fetch(`/smart-alerts/mark-read/${alertId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const alertElement = document.querySelector(`[data-alert-id="${alertId}"]`);
            alertElement.style.opacity = '0.6';
            showNotification('Alerte marquée comme lue', 'success');
        }
    });
}

function dismissAlert(alertId) {
    fetch(`/smart-alerts/dismiss/${alertId}`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            const alertElement = document.querySelector(`[data-alert-id="${alertId}"]`);
            alertElement.style.display = 'none';
            showNotification('Alerte ignorée', 'info');
        }
    });
}

function markAllAsRead() {
    fetch('/smart-alerts/mark-all-read', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content')
        }
    })
    .then(() => {
        showNotification('Toutes les alertes marquées comme lues', 'success');
        setTimeout(() => window.location.reload(), 1500);
    });
}

function showAlertDetails(alertId) {
    // Implémenter la modal de détails
    console.log('Show details for alert:', alertId);
}

function exportAlerts() {
    // Implémenter l'exportation
    showNotification('Exportation en cours...', 'info');
}

function showNotification(message, type) {
    const notification = document.createElement('div');
    notification.className = `fixed top-4 right-4 px-6 py-3 rounded-2xl text-white font-semibold z-50 ${
        type === 'success' ? 'bg-green-500' : 
        type === 'error' ? 'bg-red-500' : 
        'bg-blue-500'
    }`;
    notification.textContent = message;
    document.body.appendChild(notification);
    
    setTimeout(() => {
        notification.remove();
    }, 3000);
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

/* Pagination personnalisée */
.pagination ::v-deep .page-item .page-link {
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.3);
    color: white;
    margin: 0 2px;
    border-radius: 8px;
}

.pagination ::v-deep .page-item.active .page-link {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    border-color: transparent;
}
</style>
@endsection
