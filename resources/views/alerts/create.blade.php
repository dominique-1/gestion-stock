@extends('layouts.app')

@section('title', 'Créer une alerte')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <h1 class="text-3xl font-bold text-gray-900 flex items-center">
            <div class="bg-gradient-to-r from-red-500 to-pink-500 text-white p-3 rounded-xl mr-4">
                <i class="fas fa-bell text-xl"></i>
            </div>
            Créer une alerte
        </h1>
        <p class="text-gray-600 mt-2">Créez une nouvelle alerte pour le système</p>
    </div>

    <div class="bg-white rounded-xl shadow-sm border border-gray-200">
        <form method="POST" action="{{ route('alerts.store') }}" class="p-6 space-y-6">
            @csrf
            
            <!-- Type d'alerte -->
            <div>
                <label for="type" class="block text-sm font-medium text-gray-700 mb-2">
                    Type d'alerte <span class="text-red-500">*</span>
                </label>
                <select name="type" id="type" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Sélectionnez un type</option>
                    <option value="min_stock">📉 Stock faible</option>
                    <option value="overstock">📈 Surstock</option>
                    <option value="expiry_soon">⏰ Expiration imminente</option>
                    <option value="critical">⚠️ Critique</option>
                    <option value="warning">⚡ Avertissement</option>
                    <option value="info">ℹ️ Information</option>
                    <option value="prediction_risk">🔮 Risque prédit</option>
                </select>
            </div>

            <!-- Niveau d'alerte -->
            <div>
                <label for="level" class="block text-sm font-medium text-gray-700 mb-2">
                    Niveau d'alerte <span class="text-red-500">*</span>
                </label>
                <select name="level" id="level" required class="w-full border border-gray-300 rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                    <option value="">Sélectionnez un niveau</option>
                    <option value="critical">🔴 Critique</option>
                    <option value="warning">🟡 Avertissement</option>
                    <option value="info">🔵 Information</option>
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
                        <option value="{{ $product->id }}">{{ $product->name }}</option>
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
                    placeholder="Décrivez l'alerte en détail..."></textarea>
                <p class="text-sm text-gray-500 mt-1">Maximum 500 caractères</p>
            </div>

            <!-- Boutons d'action -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
                <a href="{{ route('alerts.index') }}" class="text-gray-600 hover:text-gray-800 font-medium">
                    <i class="fas fa-arrow-left mr-2"></i>Retour aux alertes
                </a>
                <div class="space-x-3">
                    <button type="reset" class="px-6 py-2 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 transition-colors duration-200">
                        Réinitialiser
                    </button>
                    <button type="submit" class="px-6 py-2 bg-gradient-to-r from-blue-500 to-blue-600 text-white rounded-lg hover:from-blue-600 hover:to-blue-700 transition-all duration-200 shadow-md hover:shadow-lg">
                        <i class="fas fa-save mr-2"></i>Créer l'alerte
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>
@endsection
