@extends('layouts.app')

@section('title', 'Modifier l\'alerte')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white p-3 rounded-xl mr-4">
                <i class="fas fa-bell text-xl"></i>
            </div>
            Modifier l'alerte
        </h1>
        <p class="text-gray-600 mt-2">Modifiez les informations de l'alerte</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('alerts.update', $alert->id) }}" class="p-6 space-y-6">
            @csrf
            @method('PUT')
            
            <!-- Type d'alerte -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                    Type d'alerte <span class="text-red-500">*</span>
                </label>
                <select name="type" id="type" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Sélectionnez un type</option>
                    <option value="min_stock" {{ $alert->type == 'min_stock' ? 'selected' : '' }}>📉 Stock faible</option>
                    <option value="overstock" {{ $alert->type == 'overstock' ? 'selected' : '' }}>📈 Surstock</option>
                    <option value="expiry_soon" {{ $alert->type == 'expiry_soon' ? 'selected' : '' }}>⏰ Expiration imminente</option>
                    <option value="critical" {{ $alert->type == 'critical' ? 'selected' : '' }}>⚠️ Critique</option>
                    <option value="warning" {{ $alert->type == 'warning' ? 'selected' : '' }}>⚡ Avertissement</option>
                    <option value="info" {{ $alert->type == 'info' ? 'selected' : '' }}>ℹ️ Information</option>
                    <option value="prediction_risk" {{ $alert->type == 'prediction_risk' ? 'selected' : '' }}>🔮 Risque prédit</option>
                </select>
            </div>

            <!-- Niveau d'alerte -->
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700 mb-2">
                    Niveau d'alerte <span class="text-red-500">*</span>
                </label>
                <select name="level" id="level" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Sélectionnez un niveau</option>
                    <option value="critical" {{ $alert->level == 'critical' ? 'selected' : '' }}>🔴 Critique</option>
                    <option value="warning" {{ $alert->level == 'warning' ? 'selected' : '' }}>🟡 Avertissement</option>
                    <option value="info" {{ $alert->level == 'info' ? 'selected' : '' }}>🔵 Information</option>
                </select>
            </div>

            <!-- Produit associé -->
            <div>
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Produit associé
                </label>
                <select name="product_id" id="product_id" class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Sélectionnez un produit (optionnel)</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ $alert->product_id == $product->id ? 'selected' : '' }}>{{ $product->name }}</option>
                    @endforeach
                </select>
            </div>

            <!-- Message -->
            <div>
                <label for="message" class="block text-sm font-medium text-gray-700 mb-2">
                    Message de l'alerte <span class="text-red-500">*</span>
                </label>
                <textarea name="message" id="message" rows="4" required maxlength="500"
                    class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Décrivez l'alerte en détail...">{{ $alert->message }}</textarea>
                <p class="text-sm text-gray-500 mt-1">Maximum 500 caractères</p>
            </div>

            <!-- Statut de lecture -->
            <div>
                <label class="flex items-center space-x-3">
                    <input type="checkbox" name="is_read" value="1" {{ $alert->is_read ? 'checked' : '' }} class="w-4 h-4 text-blue-600 rounded focus:ring-blue-500">
                    <span class="text-sm font-medium text-gray-700">Marquer comme lue</span>
                </label>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('alerts.show', $alert->id) }}" class="text-gray-600 hover:text-gray-800 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Retour aux détails
                </a>
                <div class="space-x-3">
                    <a href="{{ route('alerts.index') }}" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Annuler
                    </a>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>Enregistrer les modifications
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
