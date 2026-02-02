<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>StockApp Pro - Gestion de Stock Intelligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800;900&display=swap');
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            overflow-x: hidden;
        }
        
        /* Animations */
        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-20px); }
        }
        
        @keyframes slideInFromLeft {
            0% { transform: translateX(-100%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideInFromRight {
            0% { transform: translateX(100%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fadeInUp {
            0% { transform: translateY(30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        .slide-in-left {
            animation: slideInFromLeft 1s ease-out;
        }
        
        .slide-in-right {
            animation: slideInFromRight 1s ease-out;
        }
        
        .fade-in-up {
            animation: fadeInUp 1s ease-out;
        }
        
        .pulse-hover:hover {
            animation: pulse 2s infinite;
        }
        
        .gradient-bg {
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .text-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .hero-image {
            border-radius: 20px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transition: all 0.3s ease;
        }
        
        .hero-image:hover {
            transform: scale(1.05) rotate(2deg);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
        }
        
        .feature-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            transition: all 0.3s ease;
        }
        
        .feature-card:hover {
            transform: translateY(-10px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }
        
        .btn-glow {
            position: relative;
            overflow: hidden;
            transition: all 0.3s ease;
        }
        
        .btn-glow::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.3), transparent);
            transition: left 0.5s;
        }
        
        .btn-glow:hover::before {
            left: 100%;
        }
        
        .parallax {
            position: relative;
            overflow: hidden;
        }
        
        .parallax::before {
            content: '';
            position: absolute;
            top: -20%;
            left: -20%;
            width: 140%;
            height: 140%;
            background: radial-gradient(circle, rgba(102, 126, 234, 0.1) 0%, transparent 70%);
            animation: float 20s ease-in-out infinite;
        }
        
        .scroll-indicator {
            position: absolute;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%);
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateX(-50%) translateY(0); }
            40% { transform: translateX(-50%) translateY(-10px); }
            60% { transform: translateX(-50%) translateY(-5px); }
        }
        
        .stats-counter {
            font-size: 3rem;
            font-weight: 800;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }
        
        .testimonial-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
    </style>
</head>
<body class="bg-gray-50">
    
    <!-- Navigation -->
    <nav class="fixed top-0 w-full z-50 glass-effect px-6 py-4">
        <div class="max-w-7xl mx-auto flex justify-between items-center">
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-warehouse text-white text-xl"></i>
                </div>
                <span class="text-2xl font-bold text-gradient">StockApp Pro</span>
            </div>
            <div class="hidden md:flex space-x-8">
                <a href="#features" class="text-gray-700 hover:text-purple-600 transition-colors">Fonctionnalités</a>
                <a href="#stats" class="text-gray-700 hover:text-purple-600 transition-colors">Statistiques</a>
                <a href="#testimonials" class="text-gray-700 hover:text-purple-600 transition-colors">Témoignages</a>
                <a href="#contact" class="text-gray-700 hover:text-purple-600 transition-colors">Contact</a>
            </div>
            <a href="/login" class="btn-glow bg-white text-purple-600 px-6 py-2 rounded-full font-medium hover:shadow-lg transition-all">
                <i class="fas fa-sign-in-alt mr-2"></i>Espace Admin
            </a>
            @if(session()->has('user'))
                <form method="POST" action="/logout" class="inline">
                    @csrf
                    <button type="submit" class="text-red-600 hover:text-red-700 font-medium ml-4">
                        <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                    </button>
                </form>
            @endif
        </div>
    </nav>

    <!-- Hero Section with Image Slideshow -->
    <section class="min-h-screen flex items-center justify-center gradient-bg relative parallax">
        <div class="max-w-7xl mx-auto px-6 py-20 relative z-10">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="slide-in-left">
                    <h1 class="text-5xl md:text-7xl font-bold text-white mb-6 leading-tight">
                        Gestion de Stock
                        <span class="block text-3xl md:text-5xl mt-2 text-yellow-300">Intelligente & Automatisée</span>
                    </h1>
                    <p class="text-xl text-white/90 mb-8 leading-relaxed">
                        Optimisez votre inventaire avec des prédictions ML, des alertes intelligentes et une interface moderne. 
                        Réduisez les ruptures de 40% et augmentez votre productivité de 35%.
                    </p>
                    <div class="flex flex-col sm:flex-row gap-4">
                        <a href="/login" class="btn-glow bg-white text-purple-600 px-8 py-4 rounded-full font-semibold text-lg hover:shadow-2xl transition-all pulse-hover">
                            <i class="fas fa-rocket mr-2"></i>Démarrer Maintenant
                        </a>
                        <a href="#features" class="glass-effect text-white px-8 py-4 rounded-full font-semibold text-lg hover:bg-white/20 transition-all">
                            <i class="fas fa-play-circle mr-2"></i>Voir la Démo
                        </a>
                    </div>
                    <div class="mt-12 flex items-center space-x-8 text-white">
                        <div>
                            <div class="text-3xl font-bold">500+</div>
                            <div class="text-sm opacity-80">Entreprises</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold">99.9%</div>
                            <div class="text-sm opacity-80">Uptime</div>
                        </div>
                        <div>
                            <div class="text-3xl font-bold">24/7</div>
                            <div class="text-sm opacity-80">Support</div>
                        </div>
                    </div>
                </div>
                <div class="slide-in-right">
                    <!-- Image Slideshow -->
                    <div class="relative">
                        <div id="imageSlideshow" class="relative w-full h-96 rounded-2xl overflow-hidden shadow-2xl">
                            <div class="slideshow-image absolute inset-0 transition-opacity duration-1000">
                                <img src="{{ asset('image/OIP.webp') }}" alt="Gestion de Stock" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                            </div>
                            <div class="slideshow-image absolute inset-0 transition-opacity duration-1000 opacity-0">
                                <img src="{{ asset('image/OIP (1).webp') }}" alt="Interface Dashboard" class="w-full h-full object-cover">
                                <div class="absolute inset-0 bg-gradient-to-t from-black/30 to-transparent"></div>
                            </div>
                            <!-- Slideshow Controls -->
                            <div class="absolute bottom-4 left-1/2 transform -translate-x-1/2 flex space-x-2">
                                <button onclick="showSlide(0)" class="slideshow-dot w-3 h-3 bg-white rounded-full transition-all duration-300 opacity-100"></button>
                                <button onclick="showSlide(1)" class="slideshow-dot w-3 h-3 bg-white/50 rounded-full transition-all duration-300"></button>
                            </div>
                        </div>
                        <div class="absolute -bottom-6 -left-6 bg-white rounded-xl p-4 shadow-xl">
                            <div class="flex items-center space-x-3">
                                <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center">
                                    <i class="fas fa-chart-line text-green-600 text-xl"></i>
                                </div>
                                <div>
                                    <div class="font-semibold">+35% Productivité</div>
                                    <div class="text-sm text-gray-600">Dès le premier mois</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="scroll-indicator text-white">
            <i class="fas fa-chevron-down text-2xl"></i>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16 fade-in-up">
                <h2 class="text-4xl md:text-5xl font-bold text-gradient mb-4">Fonctionnalités Exceptionnelles</h2>
                <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                    Découvrez toutes les fonctionnalités qui font de StockApp Pro la solution de gestion de stock la plus complète
                </p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="feature-card rounded-2xl p-8 text-center fade-in-up">
                    <div class="w-20 h-20 bg-gradient-to-r from-purple-500 to-blue-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-brain text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Prédictions ML</h3>
                    <p class="text-gray-600 mb-6">Algorithmes d'intelligence artificielle pour anticiper vos besoins et optimiser vos stocks</p>
                    <ul class="text-left space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Régression linéaire</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Moyenne mobile</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Deep Learning</li>
                    </ul>
                </div>
                
                <div class="feature-card rounded-2xl p-8 text-center fade-in-up" style="animation-delay: 0.1s">
                    <div class="w-20 h-20 bg-gradient-to-r from-green-500 to-teal-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-bell text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Alertes Intelligentes</h3>
                    <p class="text-gray-600 mb-6">Notifications automatiques pour gérer les stocks critiques et éviter les ruptures</p>
                    <ul class="text-left space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Stock faible</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Produits expirants</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Anomalies détectées</li>
                    </ul>
                </div>
                
                <div class="feature-card rounded-2xl p-8 text-center fade-in-up" style="animation-delay: 0.2s">
                    <div class="w-20 h-20 bg-gradient-to-r from-orange-500 to-red-500 rounded-2xl flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-chart-bar text-white text-3xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold mb-4">Dashboard Avancé</h3>
                    <p class="text-gray-600 mb-6">Tableau de bord interactif avec graphiques en temps réel et indicateurs clés</p>
                    <ul class="text-left space-y-2 text-sm text-gray-600">
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Graphiques dynamiques</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Export PDF/Excel</li>
                        <li><i class="fas fa-check text-green-500 mr-2"></i>Reports personnalisés</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <!-- Image Showcase Section -->
    <section class="py-20 gradient-bg">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-2 gap-12 items-center">
                <div class="slide-in-left">
                    <img src="{{ asset('image/OIP (1).webp') }}" alt="Interface Dashboard" class="hero-image w-full">
                </div>
                <div class="slide-in-right text-white">
                    <h2 class="text-4xl font-bold mb-6">Interface Moderne & Intuitive</h2>
                    <p class="text-xl mb-8 text-white/90">
                        Une interface utilisateur exceptionnelle conçue pour offrir la meilleure expérience possible
                    </p>
                    <div class="space-y-6">
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-mobile-alt text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-semibold mb-2">100% Responsive</h4>
                                <p class="text-white/80">Parfait sur mobile, tablette et desktop</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-palette text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-semibold mb-2">Design Moderne</h4>
                                <p class="text-white/80">Glass morphism, animations fluides, effets visuels</p>
                            </div>
                        </div>
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-white/20 rounded-lg flex items-center justify-center flex-shrink-0">
                                <i class="fas fa-bolt text-white text-xl"></i>
                            </div>
                            <div>
                                <h4 class="text-xl font-semibold mb-2">Performance Optimale</h4>
                                <p class="text-white/80">Chargement ultra-rapide, navigation fluide</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Stats Section -->
    <section id="stats" class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-gradient mb-4">Chiffres Impressionnants</h2>
                <p class="text-xl text-gray-600">Les résultats parlent d'eux-mêmes</p>
            </div>
            
            <div class="grid md:grid-cols-4 gap-8">
                <div class="text-center fade-in-up">
                    <div class="stats-counter" data-target="40">0</div>
                    <div class="text-xl font-semibold mt-2">%</div>
                    <div class="text-gray-600">Réduction des ruptures</div>
                </div>
                <div class="text-center fade-in-up" style="animation-delay: 0.1s">
                    <div class="stats-counter" data-target="35">0</div>
                    <div class="text-xl font-semibold mt-2">%</div>
                    <div class="text-gray-600">Productivité en plus</div>
                </div>
                <div class="text-center fade-in-up" style="animation-delay: 0.2s">
                    <div class="stats-counter" data-target="327">0</div>
                    <div class="text-xl font-semibold mt-2">%</div>
                    <div class="text-gray-600">ROI la première année</div>
                </div>
                <div class="text-center fade-in-up" style="animation-delay: 0.3s">
                    <div class="stats-counter" data-target="99.9">0</div>
                    <div class="text-xl font-semibold mt-2">%</div>
                    <div class="text-gray-600">Disponibilité</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="py-20 gradient-bg">
        <div class="max-w-7xl mx-auto px-6">
            <div class="text-center mb-16">
                <h2 class="text-4xl md:text-5xl font-bold text-white mb-4">Ils Nous Font Confiance</h2>
                <p class="text-xl text-white/90">Découvrez les témoignages de nos clients</p>
            </div>
            
            <div class="grid md:grid-cols-3 gap-8">
                <div class="testimonial-card rounded-2xl p-8 fade-in-up">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center text-white font-bold">
                            JD
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold">Jean Dupont</div>
                            <div class="text-sm text-gray-600">Directeur Logistique</div>
                        </div>
                    </div>
                    <div class="flex mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-700">
                        "StockApp Pro a transformé notre gestion de stock. Les prédictions ML nous ont fait économiser 20% sur nos coûts d'inventaire."
                    </p>
                </div>
                
                <div class="testimonial-card rounded-2xl p-8 fade-in-up" style="animation-delay: 0.1s">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-500 rounded-full flex items-center justify-center text-white font-bold">
                            MM
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold">Marie Martin</div>
                            <div class="text-sm text-gray-600">Responsable Achats</div>
                        </div>
                    </div>
                    <div class="flex mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-700">
                        "Interface incroyable et fonctionnalités puissantes. Les alertes automatiques nous ont évité de nombreuses ruptures."
                    </p>
                </div>
                
                <div class="testimonial-card rounded-2xl p-8 fade-in-up" style="animation-delay: 0.2s">
                    <div class="flex items-center mb-4">
                        <div class="w-12 h-12 bg-gradient-to-r from-orange-500 to-red-500 rounded-full flex items-center justify-center text-white font-bold">
                            PL
                        </div>
                        <div class="ml-4">
                            <div class="font-semibold">Pierre Leroy</div>
                            <div class="text-sm text-gray-600">CEO TechShop</div>
                        </div>
                    </div>
                    <div class="flex mb-4">
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                        <i class="fas fa-star text-yellow-400"></i>
                    </div>
                    <p class="text-gray-700">
                        "ROI atteint en 3 mois seulement ! L'investissement a été rentabilisé rapidement grâce à l'optimisation des stocks."
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- CTA Section -->
    <section class="py-20 bg-white">
        <div class="max-w-4xl mx-auto px-6 text-center">
            <h2 class="text-4xl md:text-5xl font-bold text-gradient mb-6">Prêt à Révolutionner Votre Gestion de Stock ?</h2>
            <p class="text-xl text-gray-600 mb-8">
                Rejoignez les 500+ entreprises qui font déjà confiance à StockApp Pro
            </p>
            <div class="flex flex-col sm:flex-row gap-4 justify-center">
                <a href="/login" class="btn-glow bg-gradient-to-r from-purple-600 to-blue-600 text-white px-8 py-4 rounded-full font-semibold text-lg hover:shadow-2xl transition-all">
                    <i class="fas fa-rocket mr-2"></i>Commencer Gratuitement
                </a>
                <button onclick="window.location.href='#contact'" class="glass-effect text-gray-700 px-8 py-4 rounded-full font-semibold text-lg hover:bg-gray-100 transition-all">
                    <i class="fas fa-phone mr-2"></i>Demander une Démo
                </button>
            </div>
        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white py-12">
        <div class="max-w-7xl mx-auto px-6">
            <div class="grid md:grid-cols-4 gap-8">
                <div>
                    <div class="flex items-center space-x-2 mb-4">
                        <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg flex items-center justify-center">
                            <i class="fas fa-warehouse text-white text-xl"></i>
                        </div>
                        <span class="text-2xl font-bold">StockApp Pro</span>
                    </div>
                    <p class="text-gray-400">La solution de gestion de stock la plus intelligente du marché</p>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Produit</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#features" class="hover:text-white transition-colors">Fonctionnalités</a></li>
                        <li><a href="#stats" class="hover:text-white transition-colors">Statistiques</a></li>
                        <li><a href="/login" class="hover:text-white transition-colors">Espace Admin</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Support</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><a href="#" class="hover:text-white transition-colors">Documentation</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">API</a></li>
                        <li><a href="#" class="hover:text-white transition-colors">Contact</a></li>
                    </ul>
                </div>
                
                <div>
                    <h4 class="font-semibold mb-4">Contact</h4>
                    <ul class="space-y-2 text-gray-400">
                        <li><i class="fas fa-envelope mr-2"></i>contact@stockapp.com</li>
                        <li><i class="fas fa-phone mr-2"></i>+33 1 23 45 67 89</li>
                        <li><i class="fas fa-map-marker-alt mr-2"></i>Paris, France</li>
                    </ul>
                </div>
            </div>
            
            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-gray-400">
                <p>&copy; 2024 StockApp Pro. Tous droits réservés.</p>
            </div>
        </div>
    </footer>

    <script>
        // Image Slideshow functionality
        let currentSlide = 0;
        const slides = document.querySelectorAll('.slideshow-image');
        const dots = document.querySelectorAll('.slideshow-dot');
        
        function showSlide(index) {
            // Hide all slides
            slides.forEach(slide => slide.classList.add('opacity-0'));
            dots.forEach(dot => dot.classList.remove('opacity-100'));
            dots.forEach(dot => dot.classList.add('bg-white/50'));
            
            // Show current slide
            slides[index].classList.remove('opacity-0');
            dots[index].classList.add('opacity-100');
            dots[index].classList.remove('bg-white/50');
            
            currentSlide = index;
        }
        
        function nextSlide() {
            const nextIndex = (currentSlide + 1) % slides.length;
            showSlide(nextIndex);
        }
        
        // Auto-advance slideshow
        setInterval(nextSlide, 4000);
        
        // Counter animation
        function animateCounter() {
            const counters = document.querySelectorAll('.stats-counter');
            
            counters.forEach(counter => {
                const target = parseFloat(counter.getAttribute('data-target'));
                const duration = 2000;
                const increment = target / (duration / 16);
                let current = 0;
                
                const updateCounter = () => {
                    current += increment;
                    if (current < target) {
                        counter.textContent = current.toFixed(target % 1 !== 0 ? 1 : 0);
                        requestAnimationFrame(updateCounter);
                    } else {
                        counter.textContent = target.toFixed(target % 1 !== 0 ? 1 : 0);
                    }
                };
                
                updateCounter();
            });
        }
        
        // Intersection Observer for animations
        const observerOptions = {
            threshold: 0.1,
            rootMargin: '0px 0px -50px 0px'
        };
        
        const observer = new IntersectionObserver((entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    if (entry.target.classList.contains('stats-counter')) {
                        animateCounter();
                        observer.unobserve(entry.target);
                    }
                }
            });
        }, observerOptions);
        
        // Observe stats section
        document.querySelectorAll('.stats-counter').forEach(counter => {
            observer.observe(counter);
        });
        
        // Smooth scrolling for navigation links
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            });
        });
        
        // Parallax effect on scroll
        window.addEventListener('scroll', () => {
            const scrolled = window.pageYOffset;
            const parallaxElements = document.querySelectorAll('.parallax::before');
            
            parallaxElements.forEach(element => {
                const speed = 0.5;
                element.style.transform = `translateY(${scrolled * speed}px)`;
            });
        });
    </script>
</body>
</html>
