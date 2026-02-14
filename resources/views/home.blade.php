@extends('layouts.app')

@section('title', 'Accueil - Gestion de Stock')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-50 via-white to-purple-50 relative overflow-hidden">
    <!-- Éléments décoratifs de fond animés -->
    <div class="absolute inset-0 overflow-hidden">
        <div class="absolute -top-40 -right-40 w-80 h-80 bg-gradient-to-br from-blue-400/20 to-purple-400/20 rounded-full blur-3xl float-animation"></div>
        <div class="absolute -bottom-40 -left-40 w-80 h-80 bg-gradient-to-tr from-indigo-400/20 to-pink-400/20 rounded-full blur-3xl float-animation" style="animation-delay: 2s;"></div>
        <div class="absolute top-1/2 left-1/2 -translate-x-1/2 -translate-y-1/2 w-96 h-96 bg-gradient-to-r from-purple-300/10 to-blue-300/10 rounded-full blur-3xl float-animation" style="animation-delay: 4s;"></div>
    </div>

    <!-- Hero Section avec navigation intégrée -->
    <div class="relative z-10">
        <!-- Navigation moderne -->
        <nav class="glass-morphism sticky top-0 z-50 border-b border-white/20">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center py-4">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-xl flex items-center justify-center shadow-lg">
                            <i class="fas fa-warehouse text-white"></i>
                        </div>
                        <h1 class="text-2xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">StockApp</h1>
                    </div>
                    <div class="flex items-center space-x-4">
                        <a href="{{ route('login') }}" class="btn-gradient shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300">
                            <i class="fas fa-sign-in-alt mr-2"></i>
                            <span class="hidden sm:inline">Connexion</span>
                            <span class="sm:hidden">🔑</span>
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Contenu principal -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <!-- Section Hero -->
            <div class="text-center mb-16 fade-in">
                <div class="mb-8">
                    <div class="inline-flex items-center justify-center w-20 h-20 bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl shadow-2xl mb-6 scale-in">
                        <i class="fas fa-chart-line text-white text-3xl"></i>
                    </div>
                    <h1 class="text-4xl sm:text-5xl lg:text-6xl font-bold bg-gradient-to-r from-indigo-600 via-purple-600 to-pink-600 bg-clip-text text-transparent mb-6">
                        Gestion de Stock
                    </h1>
                    <p class="text-xl sm:text-2xl text-gray-600 max-w-4xl mx-auto leading-relaxed">
                        Solution complète et moderne pour gérer efficacement vos produits, 
                        mouvements et inventaires avec une interface intuitive et responsive.
                    </p>
                </div>

                <!-- Boutons d'action principaux -->
                <div class="flex flex-col sm:flex-row gap-4 justify-center items-center mb-12">
                    <a href="{{ route('login') }}" class="group bg-gradient-to-r from-indigo-600 to-purple-600 text-white px-8 py-4 rounded-2xl font-semibold text-lg shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3">
                        <i class="fas fa-rocket text-xl group-hover:scale-110 transition-transform duration-300"></i>
                        <span>Commencer maintenant</span>
                        <i class="fas fa-arrow-right group-hover:translate-x-1 transition-transform duration-300"></i>
                    </a>
                    <button onclick="scrollToFeatures()" class="group bg-white/90 backdrop-blur-sm text-gray-700 px-8 py-4 rounded-2xl font-semibold text-lg shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3 border border-white/50">
                        <i class="fas fa-play-circle text-xl group-hover:scale-110 transition-transform duration-300"></i>
                        <span>Découvrir</span>
                    </button>
                </div>

                <!-- Statistiques en direct -->
                <div class="grid grid-cols-2 sm:grid-cols-4 gap-4 mb-16">
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 shadow-lg border border-white/50 fade-in-up" style="animation-delay: 0.1s;">
                        <div class="text-3xl font-bold text-indigo-600 mb-1">500+</div>
                        <div class="text-sm text-gray-600">Produits</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 shadow-lg border border-white/50 fade-in-up" style="animation-delay: 0.2s;">
                        <div class="text-3xl font-bold text-green-600 mb-1">1.2K</div>
                        <div class="text-sm text-gray-600">Mouvements</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 shadow-lg border border-white/50 fade-in-up" style="animation-delay: 0.3s;">
                        <div class="text-3xl font-bold text-purple-600 mb-1">50+</div>
                        <div class="text-sm text-gray-600">Catégories</div>
                    </div>
                    <div class="bg-white/80 backdrop-blur-sm rounded-2xl p-4 shadow-lg border border-white/50 fade-in-up" style="animation-delay: 0.4s;">
                        <div class="text-3xl font-bold text-orange-600 mb-1">99.9%</div>
                        <div class="text-sm text-gray-600">Uptime</div>
                    </div>
                </div>
            </div>

            <!-- Section Fonctionnalités -->
            <div id="features" class="mb-16">
                <div class="text-center mb-12 fade-in">
                    <h2 class="text-3xl sm:text-4xl font-bold bg-gradient-to-r from-gray-800 to-gray-600 bg-clip-text text-transparent mb-4">
                        Fonctionnalités Principales
                    </h2>
                    <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                        Tout ce dont vous avez besoin pour une gestion de stock efficace
                    </p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                    <!-- Carte Produits -->
                    <div class="group bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-8 border border-white/50 transform hover:scale-105 transition-all duration-300 fade-in-up">
                        <div class="w-16 h-16 bg-gradient-to-br from-blue-400 to-blue-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-box text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Gestion des Produits</h3>
                        <p class="text-gray-600 mb-4">Ajoutez, modifiez et suivez tous vos produits en temps réel avec photos, barcodes et documents.</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Photos</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Barcodes</span>
                            <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-medium">Documents</span>
                        </div>
                    </div>

                    <!-- Carte Mouvements -->
                    <div class="group bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-8 border border-white/50 transform hover:scale-105 transition-all duration-300 fade-in-up" style="animation-delay: 0.1s;">
                        <div class="w-16 h-16 bg-gradient-to-br from-green-400 to-green-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-exchange-alt text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Mouvements de Stock</h3>
                        <p class="text-gray-600 mb-4">Enregistrez facilement les entrées et sorties avec suivi des motifs et historique complet.</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Entrées</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Sorties</span>
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-medium">Historique</span>
                        </div>
                    </div>

                    <!-- Carte Inventaires -->
                    <div class="group bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-8 border border-white/50 transform hover:scale-105 transition-all duration-300 fade-in-up" style="animation-delay: 0.2s;">
                        <div class="w-16 h-16 bg-gradient-to-br from-purple-400 to-purple-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-clipboard-list text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Inventaires</h3>
                        <p class="text-gray-600 mb-4">Effectuez des inventaires périodiques et comparez avec le stock théorique automatiquement.</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">Périodiques</span>
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">Comparaison</span>
                            <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-xs font-medium">Rapports</span>
                        </div>
                    </div>

                    <!-- Carte Alertes -->
                    <div class="group bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-8 border border-white/50 transform hover:scale-105 transition-all duration-300 fade-in-up" style="animation-delay: 0.3s;">
                        <div class="w-16 h-16 bg-gradient-to-br from-yellow-400 to-orange-500 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-bell text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Alertes Intelligentes</h3>
                        <p class="text-gray-600 mb-4">Recevez des notifications automatiques pour les stocks faibles et les prévisions.</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Stock faible</span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Email</span>
                            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-xs font-medium">Prévisions</span>
                        </div>
                    </div>

                    <!-- Carte Export -->
                    <div class="group bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-8 border border-white/50 transform hover:scale-105 transition-all duration-300 fade-in-up" style="animation-delay: 0.4s;">
                        <div class="w-16 h-16 bg-gradient-to-br from-indigo-400 to-indigo-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-download text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Exports & Rapports</h3>
                        <p class="text-gray-600 mb-4">Générez des rapports détaillés en CSV, Excel et PDF avec graphiques et analyses.</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">CSV</span>
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">Excel</span>
                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-xs font-medium">PDF</span>
                        </div>
                    </div>

                    <!-- Carte Prédictions -->
                    <div class="group bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg hover:shadow-2xl p-8 border border-white/50 transform hover:scale-105 transition-all duration-300 fade-in-up" style="animation-delay: 0.5s;">
                        <div class="w-16 h-16 bg-gradient-to-br from-pink-400 to-pink-600 rounded-2xl flex items-center justify-center mb-6 group-hover:scale-110 transition-transform duration-300 shadow-lg">
                            <i class="fas fa-brain text-white text-2xl"></i>
                        </div>
                        <h3 class="text-xl font-bold text-gray-800 mb-3">Prédictions IA</h3>
                        <p class="text-gray-600 mb-4">Anticipez les besoins de réapprovisionnement avec notre algorithme intelligent.</p>
                        <div class="flex flex-wrap gap-2">
                            <span class="px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-xs font-medium">ML</span>
                            <span class="px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-xs font-medium">Tendances</span>
                            <span class="px-3 py-1 bg-pink-100 text-pink-700 rounded-full text-xs font-medium">Prévisions</span>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Section CTA finale -->
            <div class="text-center mb-16">
                <div class="bg-gradient-to-r from-indigo-600 to-purple-600 rounded-3xl p-12 max-w-4xl mx-auto shadow-2xl fade-in-up">
                    <h2 class="text-3xl sm:text-4xl font-bold text-white mb-6">
                        Prêt à transformer votre gestion de stock ?
                    </h2>
                    <p class="text-xl text-white/90 mb-8 max-w-2xl mx-auto">
                        Rejoignez des centaines d'entreprises qui font confiance à StockApp pour optimiser leurs opérations.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4 justify-center">
                        <a href="{{ route('login') }}" class="bg-white text-indigo-600 px-8 py-4 rounded-2xl font-semibold text-lg shadow-xl hover:shadow-2xl transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3">
                            <i class="fas fa-rocket"></i>
                            <span>Essayer gratuitement</span>
                        </a>
                        <button onclick="showDemo()" class="bg-white/20 backdrop-blur-sm text-white px-8 py-4 rounded-2xl font-semibold text-lg border border-white/30 hover:bg-white/30 transform hover:scale-105 transition-all duration-300 flex items-center justify-center space-x-3">
                            <i class="fas fa-play"></i>
                            <span>Voir démo</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Fonction pour faire défiler vers les fonctionnalités
function scrollToFeatures() {
    document.getElementById('features').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
}

// Fonction pour afficher une démo
function showDemo() {
    // Afficher une notification ou rediriger vers une page de démo
    alert('Démo bientôt disponible ! Pour l\'instant, connectez-vous pour explorer l\'application.');
}

// Animations au scroll
const observerOptions = {
    threshold: 0.1,
    rootMargin: '0px 0px -50px 0px'
};

const observer = new IntersectionObserver((entries) => {
    entries.forEach(entry => {
        if (entry.isIntersecting) {
            entry.target.style.opacity = '1';
            entry.target.style.transform = 'translateY(0)';
        }
    });
}, observerOptions);

// Observer les éléments avec animation
document.addEventListener('DOMContentLoaded', () => {
    const animatedElements = document.querySelectorAll('.fade-in-up');
    animatedElements.forEach(el => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.6s ease-out, transform 0.6s ease-out';
        observer.observe(el);
    });
});
</script>

<style>
/* Animations personnalisées */
@keyframes float {
    0%, 100% { transform: translateY(0px) rotate(0deg); }
    50% { transform: translateY(-20px) rotate(180deg); }
}

.float-animation {
    animation: float 6s ease-in-out infinite;
}

/* Responsive amélioré */
@media (max-width: 640px) {
    .text-4xl {
        font-size: 2.5rem;
        line-height: 1.2;
    }
    
    .text-6xl {
        font-size: 3rem;
        line-height: 1.1;
    }
    
    .px-8 {
        padding-left: 1.5rem;
        padding-right: 1.5rem;
    }
    
    .py-4 {
        padding-top: 1rem;
        padding-bottom: 1rem;
    }
}

@media (max-width: 480px) {
    .grid-cols-1 {
        grid-template-columns: 1fr;
    }
    
    .text-xl {
        font-size: 1.1rem;
    }
    
    .text-2xl {
        font-size: 1.25rem;
    }
}

/* Amélioration du touch sur mobile */
@media (hover: none) and (pointer: coarse) {
    .group:hover {
        transform: none;
    }
    
    .group:active {
        transform: scale(0.98);
    }
}
</style>
@endsection
