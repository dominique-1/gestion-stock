@extends('layouts.app')

@section('title', 'Démonstration des Messages Élégants')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-purple-900 via-indigo-900 to-blue-900">
    <!-- Header -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-purple-600/30 via-indigo-600/30 to-blue-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="text-center">
                <h1 class="text-5xl font-black text-white mb-4 tracking-tight">
                    <span class="bg-gradient-to-r from-purple-400 via-indigo-500 to-blue-600 bg-clip-text text-transparent animate-gradient">
                        Démonstration des Messages
                    </span>
                </h1>
                <p class="text-gray-300 text-lg mb-8">Testez tous les types de modales et notifications</p>
            </div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Messages flash pour tester les toasts -->
        @if(session()->has('success'))
        <div class="alert-success hidden">{{ session('success') }}</div>
        @endif
        @if(session()->has('error'))
        <div class="alert-error hidden">{{ session('error') }}</div>
        @endif

        <div class="max-w-6xl mx-auto">
            <!-- Section Modales de Confirmation -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 mb-8 border border-white/20">
                <h2 class="text-3xl font-black text-white mb-6 flex items-center">
                    <div class="w-4 h-4 bg-gradient-to-r from-red-400 to-pink-400 rounded-full mr-3 animate-pulse"></div>
                    Modales de Confirmation
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Suppression -->
                    <div class="bg-gradient-to-r from-red-500/20 to-pink-500/20 rounded-2xl p-6 border border-red-500/30">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-red-500 to-pink-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-trash text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Suppression</h3>
                            <p class="text-gray-300 text-sm mb-4">Modal de confirmation pour les suppressions irréversibles</p>
                            <button onclick="confirmDelete('Êtes-vous sûr de vouloir supprimer cet élément ? Cette action est irréversible et ne peut pas être annulée.', function() { showSuccessToast('Élément supprimé avec succès !'); })" class="w-full bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white px-6 py-3 rounded-2xl font-semibold transition-all duration-300">
                                <i class="fas fa-trash mr-2"></i>Tester Suppression
                            </button>
                        </div>
                    </div>

                    <!-- Archivage -->
                    <div class="bg-gradient-to-r from-blue-500/20 to-indigo-500/20 rounded-2xl p-6 border border-blue-500/30">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-archive text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Archivage</h3>
                            <p class="text-gray-300 text-sm mb-4">Modal pour les actions d'archivage et de gestion</p>
                            <button onclick="confirmArchive('Êtes-vous sûr de vouloir archiver cet élément ? Il sera déplacé vers les archives.', function() { showSuccessToast('Élément archivé avec succès !'); })" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-3 rounded-2xl font-semibold transition-all duration-300">
                                <i class="fas fa-archive mr-2"></i>Tester Archivage
                            </button>
                        </div>
                    </div>

                    <!-- Envoi -->
                    <div class="bg-gradient-to-r from-green-500/20 to-emerald-500/20 rounded-2xl p-6 border border-green-500/30">
                        <div class="text-center">
                            <div class="w-16 h-16 bg-gradient-to-br from-green-500 to-emerald-500 rounded-2xl flex items-center justify-center mx-auto mb-4">
                                <i class="fas fa-paper-plane text-white text-2xl"></i>
                            </div>
                            <h3 class="text-xl font-bold text-white mb-2">Envoi</h3>
                            <p class="text-gray-300 text-sm mb-4">Modal pour les envois d'emails et notifications</p>
                            <button onclick="confirmSend('Êtes-vous sûr de vouloir envoyer ce message ? Il sera envoyé à tous les destinataires.', function() { showSuccessToast('Message envoyé avec succès !'); })" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-3 rounded-2xl font-semibold transition-all duration-300">
                                <i class="fas fa-paper-plane mr-2"></i>Tester Envoi
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section Notifications Toast -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 mb-8 border border-white/20">
                <h2 class="text-3xl font-black text-white mb-6 flex items-center">
                    <div class="w-4 h-4 bg-gradient-to-r from-green-400 to-emerald-400 rounded-full mr-3 animate-pulse"></div>
                    Notifications Toast
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    <!-- Succès -->
                    <div class="text-center">
                        <button onclick="showSuccessToast('Opération réussie ! Toutes les données ont été enregistrées correctement.', 'Succès')" class="w-full bg-gradient-to-r from-green-600 to-emerald-600 hover:from-green-700 hover:to-emerald-700 text-white px-6 py-4 rounded-2xl font-semibold transition-all duration-300 shadow-lg hover:shadow-green-500/50">
                            <i class="fas fa-check-circle text-2xl mb-2"></i>
                            <div>Succès</div>
                        </button>
                    </div>

                    <!-- Erreur -->
                    <div class="text-center">
                        <button onclick="showErrorToast('Une erreur est survenue ! Veuillez vérifier vos informations et réessayer.', 'Erreur')" class="w-full bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white px-6 py-4 rounded-2xl font-semibold transition-all duration-300 shadow-lg hover:shadow-red-500/50">
                            <i class="fas fa-exclamation-triangle text-2xl mb-2"></i>
                            <div>Erreur</div>
                        </button>
                    </div>

                    <!-- Attention -->
                    <div class="text-center">
                        <button onclick="showWarningToast('Attention ! Cette action peut affecter d autres éléments.', 'Attention')" class="w-full bg-gradient-to-r from-yellow-600 to-orange-600 hover:from-yellow-700 hover:to-orange-700 text-white px-6 py-4 rounded-2xl font-semibold transition-all duration-300 shadow-lg hover:shadow-yellow-500/50">
                            <i class="fas fa-exclamation text-2xl mb-2"></i>
                            <div>Attention</div>
                        </button>
                    </div>

                    <!-- Information -->
                    <div class="text-center">
                        <button onclick="showInfoToast('Information : Le système a été mis à jour avec succès.', 'Information')" class="w-full bg-gradient-to-r from-blue-600 to-indigo-600 hover:from-blue-700 hover:to-indigo-700 text-white px-6 py-4 rounded-2xl font-semibold transition-all duration-300 shadow-lg hover:shadow-blue-500/50">
                            <i class="fas fa-info-circle text-2xl mb-2"></i>
                            <div>Information</div>
                        </button>
                    </div>
                </div>
            </div>

            <!-- Section Tests Réels -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                <h2 class="text-3xl font-black text-white mb-6 flex items-center">
                    <div class="w-4 h-4 bg-gradient-to-r from-purple-400 to-pink-400 rounded-full mr-3 animate-pulse"></div>
                    Tests Réels (avec redirection)
                </h2>
                
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <!-- Test Suppression Alertes -->
                    <div class="text-center">
                        <a href="{{ route('alerts.index') }}" onclick="confirmDelete('Êtes-vous sûr de vouloir supprimer cette alerte critique ? Cette action est irréversible.', function() { window.location.href = '{{ route('alerts.index') }}'; }); return false;" class="inline-block bg-gradient-to-r from-red-600 to-pink-600 hover:from-red-700 hover:to-pink-700 text-white px-6 py-4 rounded-2xl font-semibold transition-all duration-300 shadow-lg hover:shadow-red-500/50">
                            <i class="fas fa-bell-slash mr-2"></i>
                            Page Alertes
                        </a>
                    </div>

                    <!-- Test Suppression Mouvements -->
                    <div class="text-center">
                        <a href="{{ route('movements.index') }}" onclick="confirmDelete('Êtes-vous sûr de vouloir supprimer ce mouvement de stock ? Cette action affectera vos inventaires.', function() { window.location.href = '{{ route('movements.index') }}'; }); return false;" class="inline-block bg-gradient-to-r from-orange-600 to-red-600 hover:from-orange-700 hover:to-red-700 text-white px-6 py-4 rounded-2xl font-semibold transition-all duration-300 shadow-lg hover:shadow-orange-500/50">
                            <i class="fas fa-exchange-alt mr-2"></i>
                            Page Mouvements
                        </a>
                    </div>

                    <!-- Test Suppression Catégories -->
                    <div class="text-center">
                        <a href="{{ route('categories.index') }}" onclick="confirmDelete('Êtes-vous sûr de vouloir supprimer cette catégorie ? Tous les produits associés seront affectés.', function() { window.location.href = '{{ route('categories.index') }}'; }); return false;" class="inline-block bg-gradient-to-r from-emerald-600 to-teal-600 hover:from-emerald-700 hover:to-teal-700 text-white px-6 py-4 rounded-2xl font-semibold transition-all duration-300 shadow-lg hover:shadow-emerald-500/50">
                            <i class="fas fa-folder mr-2"></i>
                            Page Catégories
                        </a>
                    </div>
                </div>

                <div class="mt-8 text-center">
                    <p class="text-gray-300 mb-4">Testez les messages flash Laravel (redirections) :</p>
                    <div class="flex justify-center space-x-4">
                        <a href="{{ route('alerts.index') }}?flash=success" class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded-xl font-semibold transition-all duration-300">
                            <i class="fas fa-check mr-2"></i>Succès Flash
                        </a>
                        <a href="{{ route('alerts.index') }}?flash=error" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded-xl font-semibold transition-all duration-300">
                            <i class="fas fa-times mr-2"></i>Erreur Flash
                        </a>
                    </div>
                </div>
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
