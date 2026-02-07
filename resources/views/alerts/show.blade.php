@extends('layouts.app')

@section('title', 'Détails de l\'alerte')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white p-3 rounded-xl mr-4">
                <i class="fas fa-bell text-xl"></i>
            </div>
            Détails de l'alerte
        </h1>
        <p class="text-gray-600 mt-2">Informations complètes sur l'alerte</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <div class="p-6">
            <!-- En-tête alerte -->
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center space-x-4">
                    <!-- Icône et niveau -->
                    <div class="flex items-center space-x-3">
                        @if($alert->level == 'critical')
                            <div class="bg-red-100 text-red-600 p-3 rounded-full">
                                <i class="fas fa-exclamation-triangle text-xl"></i>
                            </div>
                        @elseif($alert->level == 'warning')
                            <div class="bg-yellow-100 text-yellow-600 p-3 rounded-full">
                                <i class="fas fa-exclamation-circle text-xl"></i>
                            </div>
                        @else
                            <div class="bg-blue-100 text-blue-600 p-3 rounded-full">
                                <i class="fas fa-info-circle text-xl"></i>
                            </div>
                        @endif
                        
                        <div>
                            <span class="px-4 py-2 text-sm font-bold rounded-full
                                @if($alert->level == 'critical') bg-red-100 text-red-800
                                @elseif($alert->level == 'warning') bg-yellow-100 text-yellow-800
                                @else bg-blue-100 text-blue-800
                                @endif
                            ">
                                {{ strtoupper($alert->level) }}
                            </span>
                        </div>
                    </div>

                    <!-- Type -->
                    <span class="px-4 py-2 text-sm font-medium rounded-full
                        @if($alert->type == 'critical') bg-red-50 text-red-700 border border-red-200
                        @elseif($alert->type == 'warning') bg-yellow-50 text-yellow-700 border border-yellow-200
                        @elseif($alert->type == 'info') bg-blue-50 text-blue-700 border border-blue-200
                        @elseif($alert->type == 'min_stock') bg-orange-50 text-orange-700 border border-orange-200
                        @elseif($alert->type == 'overstock') bg-purple-50 text-purple-700 border border-purple-200
                        @elseif($alert->type == 'expiry_soon') bg-indigo-50 text-indigo-700 border border-indigo-200
                        @elseif($alert->type == 'prediction_risk') bg-pink-50 text-pink-700 border border-pink-200
                        @else bg-gray-50 text-gray-700 border border-gray-200
                        @endif
                    ">
                        {{ ucfirst(str_replace('_', ' ', $alert->type)) }}
                    </span>

                    <!-- Statut lecture -->
                    @if(!$alert->is_read)
                        <span class="bg-blue-100 text-blue-700 px-3 py-1 text-sm font-bold rounded-full">
                            <i class="fas fa-circle text-blue-500 mr-1" style="font-size: 8px;"></i>NOUVEAU
                        </span>
                    @endif
                </div>

                <!-- Actions -->
                <div class="flex items-center space-x-2">
                    <a href="{{ route('alerts.edit', $alert->id) }}" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-600 transition-colors duration-200">
                        <i class="fas fa-edit mr-2"></i>Modifier
                    </a>
                    @if(!$alert->is_read)
                        <form method="POST" action="{{ route('alerts.mark-read', $alert->id) }}" class="inline">
                            @csrf
                            <button type="submit" class="bg-green-500 text-white px-4 py-2 rounded-lg hover:bg-green-600 transition-colors duration-200">
                                <i class="fas fa-check mr-2"></i>Marquer comme lu
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            <!-- Message principal -->
            <div class="bg-gray-50 rounded-lg p-6 mb-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-3">Message</h3>
                <p class="text-gray-700 leading-relaxed">{{ $alert->message }}</p>
            </div>

            <!-- Informations détaillées -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                <!-- Produit associé -->
                <div class="bg-blue-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-blue-900 mb-2">
                        <i class="fas fa-box mr-2"></i>Produit associé
                    </h3>
                    @if($alert->product && isset($alert->product->id))
                        <a href="{{ route('products.show', $alert->product->id) }}" class="text-blue-600 hover:text-blue-800 font-medium">
                            {{ $alert->product->name }}
                        </a>
                        <p class="text-sm text-blue-700 mt-1">
                            Stock actuel : {{ $alert->product->stock ?? 'N/A' }} {{ $alert->product->unit ?? '' }}
                        </p>
                    @elseif($alert->product)
                        <span class="text-blue-600 font-medium">
                            {{ $alert->product->name }}
                        </span>
                        <p class="text-sm text-blue-700 mt-1">
                            Stock actuel : {{ $alert->product->stock ?? 'N/A' }} {{ $alert->product->unit ?? '' }}
                        </p>
                    @else
                        <p class="text-blue-700">Aucun produit associé</p>
                    @endif
                </div>

                <!-- Statut -->
                <div class="bg-gray-50 rounded-lg p-4">
                    <h3 class="text-sm font-semibold text-gray-900 mb-2">
                        <i class="fas fa-info-circle mr-2"></i>Statut
                    </h3>
                    <p class="text-gray-700">
                        @if($alert->is_read)
                            <span class="text-green-600 font-medium">
                                <i class="fas fa-check-circle mr-1"></i>Lue
                            </span>
                        @else
                            <span class="text-blue-600 font-medium">
                                <i class="fas fa-envelope mr-1"></i>Non lue
                            </span>
                        @endif
                    </p>
                    @if($alert->email_sent_at)
                        <p class="text-sm text-green-600 mt-1">
                            <i class="fas fa-paper-plane mr-1"></i>Email envoyé le {{ is_string($alert->email_sent_at) ? \Carbon\Carbon::parse($alert->email_sent_at)->format('d/m/Y H:i') : $alert->email_sent_at->format('d/m/Y H:i') }}
                        </p>
                    @endif
                </div>
            </div>

            <!-- Métadonnées -->
            <div class="border-t border-gray-200 pt-6">
                <h3 class="text-sm font-semibold text-gray-900 mb-4">Informations système</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
                    <div>
                        <span class="text-gray-500">ID de l'alerte :</span>
                        <span class="ml-2 font-mono text-gray-900">{{ $alert->id }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Créée le :</span>
                        <span class="ml-2 text-gray-900">{{ $alert->created_at->format('d/m/Y H:i:s') }}</span>
                    </div>
                    <div>
                        <span class="text-gray-500">Modifiée le :</span>
                        <span class="ml-2 text-gray-900">{{ isset($alert->updated_at) ? $alert->updated_at->format('d/m/Y H:i:s') : 'Jamais' }}</span>
                    </div>
                    @if(isset($alert->creator) && $alert->creator)
                    <div>
                        <span class="text-gray-500">Créée par :</span>
                        <span class="ml-2 text-gray-900">{{ $alert->creator->name ?? 'N/A' }}</span>
                    </div>
                    @else
                    <div>
                        <span class="text-gray-500">Créée par :</span>
                        <span class="ml-2 text-gray-900">
                            <a href="{{ route('reset.alerts') }}" class="text-blue-600 hover:text-blue-800 underline">
                                Réinitialiser les données
                            </a>
                        </span>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Actions inférieures -->
            <div class="flex items-center justify-between mt-8 pt-6 border-t border-gray-200">
                <a href="{{ route('alerts.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Retour à la liste
                </a>
                <form method="POST" action="{{ route('alerts.destroy', $alert->id) }}" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette alerte ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-600 transition-colors duration-200">
                        <i class="fas fa-trash mr-2"></i>Supprimer l'alerte
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
