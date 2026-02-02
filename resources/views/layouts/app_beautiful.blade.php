<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'StockApp Pro') - Gestion de Stock Intelligente</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
        }
        
        .glass-morphism {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
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
        }
        
        .nav-link::after {
            content: '';
            position: absolute;
            width: 0;
            height: 2px;
            bottom: -5px;
            left: 50%;
            background: linear-gradient(135deg, #667eea, #764ba2);
            transition: all 0.3s ease;
            transform: translateX(-50%);
        }
        
        .nav-link:hover::after {
            width: 100%;
        }
        
        .btn-primary {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
            padding: 10px 20px;
            border-radius: 8px;
            font-weight: 500;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }
        
        .btn-primary:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(102, 126, 234, 0.3);
        }
        
        .card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 20px;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-in;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .sidebar-item {
            transition: all 0.3s ease;
            border-radius: 8px;
            margin: 4px 0;
        }
        
        .sidebar-item:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: translateX(5px);
        }
        
        .sidebar-item.active {
            background: linear-gradient(135deg, #667eea, #764ba2);
            color: white;
        }
        
        .stats-card {
            background: linear-gradient(135deg, rgba(255, 255, 255, 0.9), rgba(255, 255, 255, 0.7));
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            padding: 24px;
            transition: all 0.3s ease;
        }
        
        .stats-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 40px rgba(0, 0, 0, 0.1);
        }
        
        .table-container {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            border-radius: 16px;
            overflow: hidden;
        }
        
        .form-input {
            background: rgba(255, 255, 255, 0.9);
            border: 1px solid rgba(102, 126, 234, 0.3);
            border-radius: 8px;
            padding: 12px 16px;
            transition: all 0.3s ease;
        }
        
        .form-input:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        
        .alert-success {
            background: linear-gradient(135deg, rgba(34, 197, 94, 0.9), rgba(22, 163, 74, 0.9));
            color: white;
            padding: 16px 20px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        
        .alert-error {
            background: linear-gradient(135deg, rgba(239, 68, 68, 0.9), rgba(220, 38, 38, 0.9));
            color: white;
            padding: 16px 20px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
        
        .alert-warning {
            background: linear-gradient(135deg, rgba(245, 158, 11, 0.9), rgba(217, 119, 6, 0.9));
            color: white;
            padding: 16px 20px;
            border-radius: 12px;
            backdrop-filter: blur(10px);
        }
    </style>
</head>
<body>
    @if(auth()->check())
        <!-- Navigation pour utilisateurs authentifiés -->
        <nav class="glass-morphism fixed top-0 w-full z-50 px-6 py-4">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-warehouse text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">StockApp Pro</span>
                </div>
                
                <div class="hidden md:flex items-center space-x-6">
                    <a href="{{ route('dashboard') }}" class="nav-link text-gray-700 hover:text-purple-600 font-medium">
                        <i class="fas fa-dashboard mr-2"></i>Dashboard
                    </a>
                    <a href="{{ route('products.index') }}" class="nav-link text-gray-700 hover:text-purple-600 font-medium">
                        <i class="fas fa-box mr-2"></i>Produits
                    </a>
                    <a href="{{ route('movements.index') }}" class="nav-link text-gray-700 hover:text-purple-600 font-medium">
                        <i class="fas fa-exchange-alt mr-2"></i>Mouvements
                    </a>
                    <a href="{{ route('categories.index') }}" class="nav-link text-gray-700 hover:text-purple-600 font-medium">
                        <i class="fas fa-tags mr-2"></i>Catégories
                    </a>
                    <a href="{{ route('alerts.index') }}" class="nav-link text-gray-700 hover:text-purple-600 font-medium">
                        <i class="fas fa-bell mr-2"></i>Alertes
                    </a>
                </div>
                
                <div class="flex items-center space-x-4">
                    <div class="relative">
                        <button class="text-gray-700 hover:text-purple-600">
                            <i class="fas fa-bell text-xl"></i>
                            @if(auth()->user()->unreadNotifications->count() > 0)
                                <span class="absolute -top-1 -right-1 w-3 h-3 bg-red-500 rounded-full"></span>
                            @endif
                        </button>
                    </div>
                    <div class="flex items-center space-x-2">
                        <div class="w-8 h-8 bg-gradient-to-r from-purple-500 to-blue-500 rounded-full flex items-center justify-center text-white font-semibold">
                            {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                        </div>
                        <span class="text-gray-700 font-medium">{{ auth()->user()->name }}</span>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="inline">
                        @csrf
                        <button type="submit" class="text-red-600 hover:text-red-700 font-medium">
                            <i class="fas fa-sign-out-alt mr-2"></i>Déconnexion
                        </button>
                    </form>
                </div>
            </div>
        </nav>

        <!-- Sidebar pour mobile -->
        <div class="md:hidden fixed inset-0 z-40 hidden" id="mobileSidebar">
            <div class="fixed inset-0 bg-black bg-opacity-50" onclick="toggleSidebar()"></div>
            <div class="fixed left-0 top-0 h-full w-64 bg-white shadow-lg">
                <div class="p-4">
                    <div class="flex items-center justify-between mb-8">
                        <div class="flex items-center space-x-2">
                            <div class="w-8 h-8 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg flex items-center justify-center">
                                <i class="fas fa-warehouse text-white"></i>
                            </div>
                            <span class="text-xl font-bold">StockApp</span>
                        </div>
                        <button onclick="toggleSidebar()" class="text-gray-600">
                            <i class="fas fa-times"></i>
                        </button>
                    </div>
                    
                    <div class="space-y-2">
                        <a href="{{ route('dashboard') }}" class="sidebar-item block px-4 py-3 text-gray-700">
                            <i class="fas fa-dashboard mr-3"></i>Dashboard
                        </a>
                        <a href="{{ route('products.index') }}" class="sidebar-item block px-4 py-3 text-gray-700">
                            <i class="fas fa-box mr-3"></i>Produits
                        </a>
                        <a href="{{ route('movements.index') }}" class="sidebar-item block px-4 py-3 text-gray-700">
                            <i class="fas fa-exchange-alt mr-3"></i>Mouvements
                        </a>
                        <a href="{{ route('categories.index') }}" class="sidebar-item block px-4 py-3 text-gray-700">
                            <i class="fas fa-tags mr-3"></i>Catégories
                        </a>
                        <a href="{{ route('alerts.index') }}" class="sidebar-item block px-4 py-3 text-gray-700">
                            <i class="fas fa-bell mr-3"></i>Alertes
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Content -->
        <main class="pt-20 px-6 pb-8">
            @yield('content')
        </main>

        <!-- Mobile menu button -->
        <div class="md:hidden fixed bottom-6 right-6 z-50">
            <button onclick="toggleSidebar()" class="w-14 h-14 bg-gradient-to-r from-purple-600 to-blue-600 rounded-full text-white shadow-lg flex items-center justify-center">
                <i class="fas fa-bars text-xl"></i>
            </button>
        </div>
    @else
        <!-- Navigation pour invités -->
        <nav class="glass-morphism fixed top-0 w-full z-50 px-6 py-4">
            <div class="max-w-7xl mx-auto flex justify-between items-center">
                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-gradient-to-r from-purple-600 to-blue-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-warehouse text-white text-xl"></i>
                    </div>
                    <span class="text-2xl font-bold bg-gradient-to-r from-purple-600 to-blue-600 bg-clip-text text-transparent">StockApp Pro</span>
                </div>
                
                <div class="flex items-center space-x-4">
                    <a href="{{ route('login') }}" class="btn-primary">
                        <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                    </a>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="pt-20 px-6 pb-8">
            @yield('content')
        </main>
    @endif

    <!-- Flash Messages -->
    <div class="fixed top-24 right-6 z-50 space-y-2">
        @if(session('success'))
            <div class="alert-success fade-in">
                <i class="fas fa-check-circle mr-2"></i>{{ session('success') }}
            </div>
        @endif
        
        @if(session('error'))
            <div class="alert-error fade-in">
                <i class="fas fa-exclamation-circle mr-2"></i>{{ session('error') }}
            </div>
        @endif
        
        @if(session('warning'))
            <div class="alert-warning fade-in">
                <i class="fas fa-exclamation-triangle mr-2"></i>{{ session('warning') }}
            </div>
        @endif
        
        @if($errors->any())
            <div class="alert-error fade-in">
                <i class="fas fa-exclamation-circle mr-2"></i>
                @foreach($errors->all() as $error)
                    {{ $error }}<br>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        // Toggle mobile sidebar
        function toggleSidebar() {
            const sidebar = document.getElementById('mobileSidebar');
            sidebar.classList.toggle('hidden');
        }
        
        // Auto-hide flash messages
        setTimeout(() => {
            const alerts = document.querySelectorAll('.alert-success, .alert-error, .alert-warning');
            alerts.forEach(alert => {
                alert.style.opacity = '0';
                setTimeout(() => alert.remove(), 300);
            });
        }, 5000);
        
        // Smooth scroll for anchor links
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
    </script>
    
    @stack('scripts')
</body>
</html>