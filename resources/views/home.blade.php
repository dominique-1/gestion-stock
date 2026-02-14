@extends('layouts.app')

@section('title', 'Accueil - Gestion de Stock')

@section('content')
<div class="min-h-screen bg-white relative overflow-hidden">
    <!-- Fond géométrique moderne -->
    <div class="absolute inset-0 opacity-5">
        <div class="absolute top-0 left-0 w-96 h-96 bg-black rounded-full transform -translate-x-1/2 -translate-y-1/2"></div>
        <div class="absolute bottom-0 right-0 w-96 h-96 bg-black rounded-full transform translate-x-1/2 translate-y-1/2"></div>
        <div class="absolute top-1/2 left-1/2 w-96 h-96 bg-black rounded-full transform -translate-x-1/2 -translate-y-1/2"></div>
    </div>

    <!-- Navigation simplifiée -->
    <nav class="relative z-20 border-b border-gray-100">
        <div class="max-w-6xl mx-auto px-6 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center space-x-2">
                    <div class="w-8 h-8 bg-black rounded-lg flex items-center justify-center">
                        <i class="fas fa-cube text-white text-sm"></i>
                    </div>
                    <span class="text-xl font-light tracking-tight">StockFlow</span>
                </div>
                <a href="{{ route('login') }}" class="px-6 py-2 border border-black rounded-full text-sm font-medium hover:bg-black hover:text-white transition-all duration-300">
                    Connexion
                </a>
            </div>
        </div>
    </nav>

    <!-- Hero Section minimaliste -->
    <div class="relative z-10 max-w-6xl mx-auto px-6 py-20">
        <div class="text-center max-w-4xl mx-auto">
            <h1 class="text-6xl md:text-7xl lg:text-8xl font-extralight tracking-tight mb-6">
                Gestion
                <span class="block font-medium">Intelligente</span>
            </h1>
            <p class="text-xl text-gray-600 mb-12 max-w-2xl mx-auto leading-relaxed font-light">
                Une solution épurée pour gérer votre stock avec précision et élégance. 
                Moins de complexité, plus d'efficacité.
            </p>
            
            <!-- Bouton principal -->
            <div class="flex flex-col sm:flex-row gap-4 justify-center items-center">
                <a href="{{ route('login') }}" class="group relative px-12 py-4 bg-black text-white rounded-full text-lg font-medium hover:shadow-2xl transition-all duration-500 overflow-hidden">
                    <span class="relative z-10 flex items-center">
                        Commencer
                        <i class="fas fa-arrow-right ml-2 group-hover:translate-x-1 transition-transform duration-300"></i>
                    </span>
                    <div class="absolute inset-0 bg-gradient-to-r from-gray-800 to-black transform scale-x-0 group-hover:scale-x-100 transition-transform duration-500 origin-left"></div>
                </a>
                <button onclick="scrollToFeatures()" class="px-12 py-4 border border-gray-300 rounded-full text-lg font-medium hover:border-black transition-all duration-300">
                    Découvrir
                </button>
            </div>
        </div>

        <!-- Image placeholder ou illustration -->
        <div class="mt-20 relative">
            <div class="bg-gray-50 rounded-2xl p-8 border border-gray-100">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <div class="text-center">
                        <div class="text-4xl font-light mb-2">500+</div>
                        <div class="text-sm text-gray-600">Produits</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-light mb-2">99.9%</div>
                        <div class="text-sm text-gray-600">Précision</div>
                    </div>
                    <div class="text-center">
                        <div class="text-4xl font-light mb-2">24/7</div>
                        <div class="text-sm text-gray-600">Disponibilité</div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Section Fonctionnalités - Design épuré -->
    <div id="features" class="relative z-10 bg-gray-50 py-20">
        <div class="max-w-6xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl font-light mb-4">Fonctionnalités</h2>
                <p class="text-gray-600 text-lg font-light">L'essentiel pour une gestion efficace</p>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-12">
                <!-- Produits -->
                <div class="group cursor-pointer">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-black rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-box text-white text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-light mb-3">Produits</h3>
                    <p class="text-gray-600 leading-relaxed">Catalogue complet avec suivi en temps réel et gestion des variantes.</p>
                </div>

                <!-- Mouvements -->
                <div class="group cursor-pointer">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-black rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-arrows-alt-h text-white text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-light mb-3">Mouvements</h3>
                    <p class="text-gray-600 leading-relaxed">Traçabilité complète des entrées et sorties avec historique détaillé.</p>
                </div>

                <!-- Alertes -->
                <div class="group cursor-pointer">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-black rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-bell text-white text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-light mb-3">Alertes</h3>
                    <p class="text-gray-600 leading-relaxed">Notifications intelligentes pour les stocks faibles et réapprovisionnements.</p>
                </div>

                <!-- Rapports -->
                <div class="group cursor-pointer">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-black rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-chart-bar text-white text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-light mb-3">Rapports</h3>
                    <p class="text-gray-600 leading-relaxed">Analyses détaillées et exportations en plusieurs formats.</p>
                </div>

                <!-- Inventaires -->
                <div class="group cursor-pointer">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-black rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-clipboard-check text-white text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-light mb-3">Inventaires</h3>
                    <p class="text-gray-600 leading-relaxed">Gestion des inventaires périodiques avec comparaisons automatiques.</p>
                </div>

                <!-- Prédictions -->
                <div class="group cursor-pointer">
                    <div class="mb-6">
                        <div class="w-16 h-16 bg-black rounded-2xl flex items-center justify-center group-hover:scale-110 transition-transform duration-300">
                            <i class="fas fa-brain text-white text-xl"></i>
                        </div>
                    </div>
                    <h3 class="text-2xl font-light mb-3">Prédictions</h3>
                    <p class="text-gray-600 leading-relaxed">Anticipation des besoins avec algorithmes d'intelligence artificielle.</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Section CTA - Design minimaliste -->
    <div class="relative z-10 py-20">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <div class="border border-gray-200 rounded-3xl p-12">
                <h2 class="text-3xl font-light mb-6">Prêt à simplifier votre gestion ?</h2>
                <p class="text-gray-600 text-lg mb-8 font-light">
                    Rejoignez les entreprises qui font confiance à StockFlow pour une gestion 
                    de stock simple et efficace.
                </p>
                <a href="{{ route('login') }}" class="inline-flex items-center px-8 py-3 bg-black text-white rounded-full text-lg font-medium hover:shadow-xl transition-all duration-300">
                    Essayer gratuitement
                    <i class="fas fa-arrow-right ml-2"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Footer minimaliste -->
    <footer class="relative z-10 border-t border-gray-100">
        <div class="max-w-6xl mx-auto px-6 py-8">
            <div class="flex flex-col md:flex-row justify-between items-center">
                <div class="flex items-center space-x-2 mb-4 md:mb-0">
                    <div class="w-6 h-6 bg-black rounded-lg flex items-center justify-center">
                        <i class="fas fa-cube text-white text-xs"></i>
                    </div>
                    <span class="text-sm text-gray-600">StockFlow {{ date('Y') }}</span>
                </div>
                <div class="flex space-x-6 text-sm text-gray-600">
                    <a href="#" class="hover:text-black transition-colors">Confidentialité</a>
                    <a href="#" class="hover:text-black transition-colors">Conditions</a>
                    <a href="#" class="hover:text-black transition-colors">Support</a>
                </div>
            </div>
        </div>
    </footer>
</div>

<script>
// Fonction pour faire défiler vers les fonctionnalités
function scrollToFeatures() {
    document.getElementById('features').scrollIntoView({ 
        behavior: 'smooth',
        block: 'start'
    });
}

// Animations subtiles au scroll
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
    const animatedElements = document.querySelectorAll('.group');
    animatedElements.forEach((el, index) => {
        el.style.opacity = '0';
        el.style.transform = 'translateY(30px)';
        el.style.transition = 'opacity 0.8s ease-out, transform 0.8s ease-out';
        el.style.transitionDelay = `${index * 0.1}s`;
        observer.observe(el);
    });
});

// Effet de parallaxe subtil sur le hero
window.addEventListener('scroll', () => {
    const scrolled = window.pageYOffset;
    const hero = document.querySelector('.text-center');
    if (hero) {
        hero.style.transform = `translateY(${scrolled * 0.3}px)`;
        hero.style.opacity = 1 - scrolled / 600;
    }
});
</script>

<style>
/* Styles personnalisés pour le design minimaliste */
* {
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}

body {
    font-family: 'Inter', -apple-system, BlinkMacSystemFont, sans-serif;
    font-weight: 300;
}

/* Animations fluides */
.group {
    transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Responsive design */
@media (max-width: 768px) {
    .text-6xl {
        font-size: 3rem;
        line-height: 1.1;
    }
    
    .text-7xl {
        font-size: 3.5rem;
        line-height: 1.1;
    }
    
    .text-8xl {
        font-size: 4rem;
        line-height: 1.1;
    }
    
    .px-12 {
        padding-left: 2rem;
        padding-right: 2rem;
    }
}

@media (max-width: 480px) {
    .text-6xl {
        font-size: 2.5rem;
    }
    
    .text-7xl {
        font-size: 3rem;
    }
    
    .text-8xl {
        font-size: 3.5rem;
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

/* Focus states pour l'accessibilité */
a:focus, button:focus {
    outline: 2px solid black;
    outline-offset: 2px;
}

/* Smooth scrolling */
html {
    scroll-behavior: smooth;
}

/* Custom scrollbar */
::-webkit-scrollbar {
    width: 8px;
}

::-webkit-scrollbar-track {
    background: #f1f1f1;
}

::-webkit-scrollbar-thumb {
    background: #888;
    border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
    background: #555;
}
</style>
@endsection
