<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <meta name="mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <title>@yield('title', 'Gestion de Stock') - StockApp</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap');
        
        * {
            -webkit-tap-highlight-color: transparent;
            -webkit-touch-callout: none;
            -webkit-user-select: none;
            -khtml-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            overflow-x: hidden;
        }
        
        .glass-morphism {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .glass-morphism::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .glass-morphism:hover::before {
            left: 100%;
        }
        
        .nav-link {
            position: relative;
            transition: all 0.3s ease;
            padding: 0.5rem 0.75rem;
            border-radius: 0.5rem;
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: 0;
            left: 50%;
            background-color: #667eea;
            transition: all 0.3s ease;
        }
        
        .nav-link:hover::after {
            width: 100%;
            left: 0;
        }
        
        .nav-link:hover {
            color: #667eea;
            background-color: rgba(102, 126, 234, 0.1);
        }
        
        .card-hover:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }
        
        .btn-gradient {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            transition: all 0.3s ease;
            color: white;
            padding: 8px 16px;
            border-radius: 8px;
            text-decoration: none;
            display: inline-block;
            border: none;
            cursor: pointer;
            font-weight: 500;
            touch-action: manipulation;
        }
        
        .btn-gradient:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.4);
        }
        
        .btn-gradient:active {
            transform: translateY(0);
        }
        
        .indicator-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            backdrop-filter: blur(10px);
            -webkit-backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            position: relative;
            overflow: hidden;
        }
        
        .indicator-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: -100%;
            width: 100%;
            height: 100%;
            background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
            transition: left 0.5s;
        }
        
        .indicator-card:hover::before {
            left: 100%;
        }
        
        .chart-container {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            padding: 16px;
            transition: all 0.3s ease;
        }
        
        .chart-container:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
            border-color: #667eea;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in-up {
            animation: fadeInUp 0.6s ease-out;
        }
        
        .pulse {
            animation: pulse 2s infinite;
        }
        
        @keyframes pulse {
            0% {
                box-shadow: 0 0 0 0 rgba(102, 126, 234, 0.7);
            }
            70% {
                box-shadow: 0 0 0 10px rgba(102, 126, 234, 0);
            }
            100% {
                box-shadow: 0 0 0 0 rgba(102, 126, 234, 0);
            }
        }
        
        /* Responsive Mobile First */
        @media (max-width: 768px) {
            .container {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .glass-morphism {
                border-radius: 1rem;
            }
            
            .nav-link {
                padding: 0.75rem 1rem;
                font-size: 0.875rem;
            }
            
            .btn-gradient {
                padding: 12px 20px;
                font-size: 1rem;
            }
            
            .chart-container {
                padding: 12px;
                border-radius: 8px;
            }
            
            .indicator-card {
                padding: 1rem;
            }
        }
        
        @media (max-width: 640px) {
            .text-3xl {
                font-size: 1.5rem;
                line-height: 1.2;
            }
            
            .text-2xl {
                font-size: 1.25rem;
                line-height: 1.3;
            }
            
            .text-xl {
                font-size: 1.125rem;
                line-height: 1.4;
            }
            
            .text-lg {
                font-size: 1rem;
                line-height: 1.5;
            }
            
            .px-6 {
                padding-left: 1rem;
                padding-right: 1rem;
            }
            
            .py-4 {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
            
            .gap-6 {
                gap: 1rem;
            }
            
            .gap-4 {
                gap: 0.75rem;
            }
        }
        
        @media (max-width: 480px) {
            .text-3xl {
                font-size: 1.25rem;
            }
            
            .text-2xl {
                font-size: 1.125rem;
            }
            
            .btn-gradient {
                padding: 10px 16px;
                font-size: 0.875rem;
            }
            
            .nav-link {
                padding: 0.5rem 0.75rem;
                font-size: 0.75rem;
            }
        }
        
        /* Touch improvements */
        @media (hover: none) and (pointer: coarse) {
            .nav-link:hover {
                background-color: transparent;
            }
            
            .nav-link:active {
                background-color: rgba(102, 126, 234, 0.1);
            }
            
            .btn-gradient:hover {
                transform: none;
            }
            
            .btn-gradient:active {
                transform: scale(0.98);
            }
            
            .card-hover:hover {
                transform: none;
            }
            
            .card-hover:active {
                transform: scale(0.98);
            }
        }
        
        /* Prevent horizontal scroll */
        html, body {
            overflow-x: hidden;
            width: 100%;
        }
        
        /* Safe area for iPhone X+ */
        @supports (padding: max(0px)) {
            .safe-area-top {
                padding-top: max(1rem, env(safe-area-inset-top));
            }
            
            .safe-area-bottom {
                padding-bottom: max(1rem, env(safe-area-inset-bottom));
            }
        }
        
        /* Mobile menu styles */
        .mobile-menu {
            transform: translateX(-100%);
            transition: transform 0.3s ease-in-out;
        }
        
        .mobile-menu.active {
            transform: translateX(0);
        }
        
        .mobile-menu-overlay {
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.3s ease-in-out;
        }
        
        .mobile-menu-overlay.active {
            opacity: 1;
            pointer-events: all;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-purple-50 to-blue-50 min-h-screen">
    @session('user')
        <nav class="glass-morphism sticky top-0 z-50 safe-area-top">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-16">
                    <!-- Logo -->
                    <a href="{{ route('dashboard') }}" class="text-xl sm:text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent hover:from-purple-700 hover:to-blue-700 transition-all duration-300 flex items-center">
                        <i class="fas fa-warehouse mr-1 sm:mr-2"></i>
                        <span class="hidden sm:inline">StockApp</span>
                        <span class="sm:hidden">SA</span>
                    </a>

                    <!-- Desktop Navigation -->
                    <div class="hidden lg:flex items-center space-x-6">
                        <a href="{{ route('dashboard') }}" class="nav-link text-gray-700 hover:text-purple-600 font-medium text-sm">
                            <i class="fas fa-chart-line mr-1"></i>Dashboard
                        </a>
                        <a href="{{ route('products.index') }}" class="nav-link text-gray-700 hover:text-purple-600 font-medium text-sm">
                            <i class="fas fa-box mr-1"></i>Produits
                        </a>
                        <a href="{{ route('categories.index') }}" class="nav-link text-gray-700 hover:text-purple-600 font-medium text-sm">
                            <i class="fas fa-tags mr-1"></i>Catégories
                        </a>
                        <a href="{{ route('movements.index') }}" class="nav-link text-gray-700 hover:text-purple-600 font-medium text-sm">
                            <i class="fas fa-exchange-alt mr-1"></i>Mouvements
                        </a>
                        <a href="{{ route('inventories.index') }}" class="nav-link text-gray-700 hover:text-purple-600 font-medium text-sm">
                            <i class="fas fa-clipboard-list mr-1"></i>Inventaires
                        </a>
                        <a href="{{ route('alerts.index') }}" class="nav-link text-gray-700 hover:text-purple-600 font-medium text-sm">
                            <i class="fas fa-bell mr-1"></i>Alertes
                        </a>
                        <a href="{{ route('predictions.index') }}" class="nav-link text-gray-700 hover:text-purple-600 font-medium text-sm">
                            <i class="fas fa-brain mr-1"></i>Prédictions
                        </a>
                    </div>

                    <!-- User Section -->
                    <div class="flex items-center space-x-2 sm:space-x-4">
                        <!-- User Info (Desktop) -->
                        <div class="hidden sm:flex items-center space-x-2 bg-white/50 px-3 py-1 rounded-full">
                            <div class="w-6 h-6 sm:w-8 sm:h-8 bg-gradient-to-r from-purple-400 to-blue-400 rounded-full flex items-center justify-center text-white text-xs sm:text-sm font-bold">
                                {{ substr(session('user')['name'], 0, 1) }}
                            </div>
                            <span class="text-xs sm:text-sm font-medium text-gray-700 hidden md:block">{{ session('user')['name'] }}</span>
                            <span class="text-xs bg-purple-100 text-purple-700 px-2 py-1 rounded-full font-medium hidden lg:block">{{ session('user')['role'] }}</span>
                        </div>
                        
                        <!-- Mobile Menu Button -->
                        <button id="mobile-menu-button" class="lg:hidden p-2 rounded-lg hover:bg-gray-100 transition-colors">
                            <i class="fas fa-bars text-gray-600 text-xl"></i>
                        </button>
                        
                        <!-- Logout Button -->
                        <form method="POST" action="{{ route('logout') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs sm:text-sm text-red-500 hover:text-red-700 font-medium transition-colors duration-200 p-2 rounded-lg hover:bg-red-50">
                                <i class="fas fa-sign-out-alt"></i>
                                <span class="hidden sm:inline ml-1">Déconnexion</span>
                            </button>
                        </form>
                    </div>
                </div>

                <!-- Mobile Navigation -->
                <div id="mobile-menu" class="mobile-menu fixed top-16 left-0 w-64 h-full bg-white shadow-2xl z-50 lg:hidden">
                    <div class="p-4 space-y-2">
                        <a href="{{ route('dashboard') }}" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 hover:bg-purple-50 p-3 rounded-lg transition-colors">
                            <i class="fas fa-chart-line w-5"></i>
                            <span>Dashboard</span>
                        </a>
                        <a href="{{ route('products.index') }}" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 hover:bg-purple-50 p-3 rounded-lg transition-colors">
                            <i class="fas fa-box w-5"></i>
                            <span>Produits</span>
                        </a>
                        <a href="{{ route('categories.index') }}" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 hover:bg-purple-50 p-3 rounded-lg transition-colors">
                            <i class="fas fa-tags w-5"></i>
                            <span>Catégories</span>
                        </a>
                        <a href="{{ route('movements.index') }}" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 hover:bg-purple-50 p-3 rounded-lg transition-colors">
                            <i class="fas fa-exchange-alt w-5"></i>
                            <span>Mouvements</span>
                        </a>
                        <a href="{{ route('inventories.index') }}" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 hover:bg-purple-50 p-3 rounded-lg transition-colors">
                            <i class="fas fa-clipboard-list w-5"></i>
                            <span>Inventaires</span>
                        </a>
                        <a href="{{ route('alerts.index') }}" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 hover:bg-purple-50 p-3 rounded-lg transition-colors">
                            <i class="fas fa-bell w-5"></i>
                            <span>Alertes</span>
                        </a>
                        <a href="{{ route('predictions.index') }}" class="flex items-center space-x-3 text-gray-700 hover:text-purple-600 hover:bg-purple-50 p-3 rounded-lg transition-colors">
                            <i class="fas fa-brain w-5"></i>
                            <span>Prédictions</span>
                        </a>
                        
                        <!-- Mobile User Info -->
                        <div class="border-t pt-4 mt-4">
                            <div class="flex items-center space-x-3 bg-gray-50 p-3 rounded-lg">
                                <div class="w-10 h-10 bg-gradient-to-r from-purple-400 to-blue-400 rounded-full flex items-center justify-center text-white font-bold">
                                    {{ substr(session('user')['name'], 0, 1) }}
                                </div>
                                <div>
                                    <div class="font-medium text-gray-700">{{ session('user')['name'] }}</div>
                                    <div class="text-xs text-gray-500">{{ session('user')['role'] }}</div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Mobile Menu Overlay -->
                <div id="mobile-menu-overlay" class="mobile-menu-overlay fixed inset-0 bg-black/50 z-40 lg:hidden"></div>
            </div>
        </nav>
    @endsession

    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        @if(session('success'))
            <div class="mb-6 p-4 bg-gradient-to-r from-green-50 to-emerald-50 border border-green-200 text-green-800 rounded-lg fade-in-up">
                <div class="flex items-center">
                    <i class="fas fa-check-circle mr-2"></i>
                    {{ session('success') }}
                </div>
            </div>
        @endif

        @yield('content')
    </main>

    <script>
        // Configuration Chart.js globale
        Chart.defaults.font.family = 'Inter, sans-serif';
        Chart.defaults.color = '#6B7280';
        Chart.defaults.borderColor = '#E5E7EB';
        Chart.defaults.plugins.tooltip.backgroundColor = 'rgba(0, 0, 0, 0.8)';
        Chart.defaults.plugins.tooltip.titleFont = { size: 14 };
        Chart.defaults.plugins.tooltip.bodyFont = { size: 12 };
        Chart.defaults.plugins.legend.labels.font = { size: 12 };
        
        // Smooth scrolling
        document.querySelectorAll('a[href^="#"]').forEach(anchor => {
            anchor.addEventListener('click', function (e) {
                e.preventDefault();
                const target = document.querySelector(this.getAttribute('href'));
                if (target) {
                    target.scrollIntoView({
                        behavior: 'smooth'
                    });
                }
            });
        });
        
        // Loading animations
        document.addEventListener('DOMContentLoaded', function() {
            const cards = document.querySelectorAll('.card-hover');
            cards.forEach((card, index) => {
                setTimeout(() => {
                    card.classList.add('fade-in-up');
                }, index * 100);
            });
            
            const indicators = document.querySelectorAll('.indicator-card');
            indicators.forEach((indicator, index) => {
                setTimeout(() => {
                    indicator.classList.add('fade-in-up');
                }, index * 150);
            });
        });

        // Mobile Menu Toggle
        const mobileMenuButton = document.getElementById('mobile-menu-button');
        const mobileMenu = document.getElementById('mobile-menu');
        const mobileMenuOverlay = document.getElementById('mobile-menu-overlay');
        
        if (mobileMenuButton && mobileMenu && mobileMenuOverlay) {
            mobileMenuButton.addEventListener('click', function() {
                mobileMenu.classList.toggle('active');
                mobileMenuOverlay.classList.toggle('active');
                document.body.style.overflow = mobileMenu.classList.contains('active') ? 'hidden' : '';
            });
            
            mobileMenuOverlay.addEventListener('click', function() {
                mobileMenu.classList.remove('active');
                mobileMenuOverlay.classList.remove('active');
                document.body.style.overflow = '';
            });
            
            // Close menu when clicking on links
            const menuLinks = mobileMenu.querySelectorAll('a');
            menuLinks.forEach(link => {
                link.addEventListener('click', function() {
                    mobileMenu.classList.remove('active');
                    mobileMenuOverlay.classList.remove('active');
                    document.body.style.overflow = '';
                });
            });
        }

        // Handle resize events
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(function() {
                if (window.innerWidth >= 1024) {
                    // Close mobile menu on desktop
                    if (mobileMenu && mobileMenuOverlay) {
                        mobileMenu.classList.remove('active');
                        mobileMenuOverlay.classList.remove('active');
                        document.body.style.overflow = '';
                    }
                }
            }, 250);
        });

        // Touch improvements for mobile
        if ('ontouchstart' in window) {
            document.body.classList.add('touch-device');
        }

        // Prevent zoom on double tap for iOS
        let lastTouchEnd = 0;
        document.addEventListener('touchend', function (event) {
            const now = (new Date()).getTime();
            if (now - lastTouchEnd <= 300) {
                event.preventDefault();
            }
            lastTouchEnd = now;
        }, false);

        // Handle viewport height for mobile browsers
        function setViewportHeight() {
            const vh = window.innerHeight * 0.01;
            document.documentElement.style.setProperty('--vh', `${vh}px`);
        }
        
        setViewportHeight();
        window.addEventListener('resize', setViewportHeight);
        window.addEventListener('orientationchange', setViewportHeight);
    </script>

    <!-- Scripts globaux pour les modales et notifications -->
    <script src="{{ asset('js/confirm-modal.js') }}"></script>
    <script src="{{ asset('js/toast-notifications.js') }}"></script>
</body>
</html>
