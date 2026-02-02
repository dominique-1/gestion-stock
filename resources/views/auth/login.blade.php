<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion Admin - StockApp Pro</title>
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
        
        @keyframes slideInLeft {
            0% { transform: translateX(-100%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes slideInRight {
            0% { transform: translateX(100%); opacity: 0; }
            100% { transform: translateX(0); opacity: 1; }
        }
        
        @keyframes fadeInUp {
            0% { transform: translateY(30px); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        @keyframes gradientShift {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }
        
        @keyframes pulse {
            0%, 100% { transform: scale(1); }
            50% { transform: scale(1.05); }
        }
        
        .gradient-bg {
            background: linear-gradient(-45deg, #667eea, #764ba2, #f093fb, #f5576c);
            background-size: 400% 400%;
            animation: gradientShift 15s ease infinite;
        }
        
        .glass-effect {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.2);
        }
        
        .glass-card {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.3);
        }
        
        .floating {
            animation: float 6s ease-in-out infinite;
        }
        
        .slide-in-left {
            animation: slideInLeft 1s ease-out;
        }
        
        .slide-in-right {
            animation: slideInRight 1s ease-out;
        }
        
        .fade-in-up {
            animation: fadeInUp 1s ease-out;
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
        
        .input-glow:focus {
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1), 0 0 20px rgba(102, 126, 234, 0.2);
        }
        
        .feature-icon {
            transition: all 0.3s ease;
        }
        
        .feature-icon:hover {
            transform: scale(1.1) rotate(5deg);
        }
        
        .login-card {
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            transition: all 0.3s ease;
        }
        
        .login-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 30px 60px -15px rgba(0, 0, 0, 0.3);
        }
        
        .password-toggle {
            cursor: pointer;
            transition: all 0.2s ease;
        }
        
        .password-toggle:hover {
            color: #667eea;
        }
        
        .back-to-home {
            position: absolute;
            top: 30px;
            left: 30px;
            z-index: 10;
        }
        
        .particles {
            position: absolute;
            width: 100%;
            height: 100%;
            overflow: hidden;
            pointer-events: none;
        }
        
        .particle {
            position: absolute;
            width: 4px;
            height: 4px;
            background: rgba(255, 255, 255, 0.5);
            border-radius: 50%;
            animation: float 10s infinite ease-in-out;
        }
        
        @keyframes slideInFromTop {
            0% { transform: translateY(-100%); opacity: 0; }
            100% { transform: translateY(0); opacity: 1; }
        }
        
        .slide-in-top {
            animation: slideInFromTop 0.8s ease-out;
        }
        
        .loading-spinner {
            display: none;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid #667eea;
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }
        
        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }
        
        .loading .loading-spinner {
            display: inline-block;
        }
        
        .loading .btn-text {
            display: none;
        }
    </style>
</head>
<body class="gradient-bg min-h-screen flex items-center justify-center relative">
    <!-- Particles Background -->
    <div class="particles">
        <div class="particle" style="top: 10%; left: 20%; animation-delay: 0s;"></div>
        <div class="particle" style="top: 20%; left: 80%; animation-delay: 1s;"></div>
        <div class="particle" style="top: 30%; left: 10%; animation-delay: 2s;"></div>
        <div class="particle" style="top: 40%; left: 90%; animation-delay: 3s;"></div>
        <div class="particle" style="top: 50%; left: 50%; animation-delay: 4s;"></div>
        <div class="particle" style="top: 60%; left: 30%; animation-delay: 5s;"></div>
        <div class="particle" style="top: 70%; left: 70%; animation-delay: 6s;"></div>
        <div class="particle" style="top: 80%; left: 15%; animation-delay: 7s;"></div>
        <div class="particle" style="top: 90%; left: 85%; animation-delay: 8s;"></div>
    </div>

    <!-- Back to Home Button -->
    <a href="/" class="back-to-home glass-effect text-white px-4 py-2 rounded-full hover:bg-white/20 transition-all slide-in-top">
        <i class="fas fa-arrow-left mr-2"></i>Retour à l'accueil
    </a>

    <!-- Main Content -->
    <div class="max-w-6xl mx-auto px-6 py-12 relative z-10">
        <div class="grid md:grid-cols-2 gap-12 items-center">
            <!-- Left Side - Features -->
            <div class="slide-in-left text-white">
                <div class="mb-8">
                    <div class="flex items-center space-x-3 mb-6">
                        <div class="w-16 h-16 bg-white/20 rounded-2xl flex items-center justify-center floating">
                            <i class="fas fa-warehouse text-3xl"></i>
                        </div>
                        <div>
                            <h1 class="text-4xl font-bold">StockApp Pro</h1>
                            <p class="text-white/80">Espace Administrateur</p>
                        </div>
                    </div>
                    
                    <h2 class="text-5xl font-bold mb-6 leading-tight">
                        Gérez Votre Stock
                        <span class="block text-3xl text-yellow-300 mt-2">Avec Intelligence</span>
                    </h2>
                    
                    <p class="text-xl text-white/90 mb-8 leading-relaxed">
                        Accédez à votre tableau de bord pour gérer vos produits, suivre les mouvements, 
                        analyser les prédictions et optimiser votre inventaire.
                    </p>
                </div>
                
                <!-- Features List -->
                <div class="space-y-6">
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center feature-icon">
                            <i class="fas fa-chart-line text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold mb-1">Dashboard Avancé</h4>
                            <p class="text-white/80">Indicateurs en temps réel et graphiques interactifs</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center feature-icon">
                            <i class="fas fa-brain text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold mb-1">Prédictions ML</h4>
                            <p class="text-white/80">Algorithmes d'intelligence artificielle pour anticiper les besoins</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center feature-icon">
                            <i class="fas fa-bell text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold mb-1">Alertes Intelligentes</h4>
                            <p class="text-white/80">Notifications automatiques pour stocks critiques et expirations</p>
                        </div>
                    </div>
                    
                    <div class="flex items-start space-x-4">
                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center feature-icon">
                            <i class="fas fa-file-export text-xl"></i>
                        </div>
                        <div>
                            <h4 class="text-xl font-semibold mb-1">Exports Complets</h4>
                            <p class="text-white/80">Rapports PDF, Excel et CSV personnalisables</p>
                        </div>
                    </div>
                </div>
                
                <!-- Stats -->
                <div class="mt-12 grid grid-cols-3 gap-6">
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-300">500+</div>
                        <div class="text-sm text-white/80">Entreprises</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-300">99.9%</div>
                        <div class="text-sm text-white/80">Uptime</div>
                    </div>
                    <div class="text-center">
                        <div class="text-3xl font-bold text-yellow-300">24/7</div>
                        <div class="text-sm text-white/80">Support</div>
                    </div>
                </div>
            </div>
            
            <!-- Right Side - Login Form -->
            <div class="slide-in-right">
                <div class="login-card glass-card p-10">
                    <!-- Logo and Title -->
                    <div class="text-center mb-8">
                        <div class="w-20 h-20 bg-gradient-to-r from-purple-600 to-blue-600 rounded-2xl flex items-center justify-center mx-auto mb-4 pulse">
                            <i class="fas fa-user-shield text-white text-3xl"></i>
                        </div>
                        <h2 class="text-3xl font-bold text-gray-800 mb-2">Connexion Admin</h2>
                        <p class="text-gray-600">Accédez à votre espace de gestion</p>
                    </div>
                    
                    <!-- Login Form -->
                    <form method="POST" action="{{ route('login.post') }}" id="loginForm" class="space-y-6">
                        @csrf
                        
                        <!-- Email Field -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2" for="email">
                                <i class="fas fa-envelope mr-2 text-purple-600"></i>Email Administrateur
                            </label>
                            <input 
                                type="email" 
                                name="email" 
                                id="email" 
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:outline-none input-glow transition-all" 
                                placeholder="admin@stockapp.com"
                                required 
                                autofocus 
                                value="{{ old('email') }}"
                            >
                            @error('email')
                                <div class="mt-2 text-red-500 text-sm flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Password Field -->
                        <div>
                            <label class="block text-gray-700 font-semibold mb-2" for="password">
                                <i class="fas fa-lock mr-2 text-purple-600"></i>Mot de Passe
                            </label>
                            <div class="relative">
                                <input 
                                    type="password" 
                                    name="password" 
                                    id="password" 
                                    class="w-full px-4 py-3 pr-12 border-2 border-gray-200 rounded-xl focus:border-purple-500 focus:outline-none input-glow transition-all" 
                                    placeholder="••••••••"
                                    required
                                >
                                <button type="button" class="password-toggle absolute right-4 top-1/2 transform -translate-y-1/2 text-gray-500" onclick="togglePassword()">
                                    <i class="fas fa-eye" id="passwordIcon"></i>
                                </button>
                            </div>
                            @error('password')
                                <div class="mt-2 text-red-500 text-sm flex items-center">
                                    <i class="fas fa-exclamation-circle mr-2"></i>{{ $message }}
                                </div>
                            @enderror
                        </div>
                        
                        <!-- Remember Me -->
                        <div class="flex items-center justify-between">
                            <label class="flex items-center cursor-pointer">
                                <input type="checkbox" name="remember" class="w-4 h-4 text-purple-600 border-gray-300 rounded focus:ring-purple-500">
                                <span class="ml-2 text-gray-700">Se souvenir de moi</span>
                            </label>
                            <a href="#" class="text-purple-600 hover:text-purple-700 text-sm font-medium">
                                Mot de passe oublié ?
                            </a>
                        </div>
                        
                        <!-- Submit Button -->
                        <button 
                            type="submit" 
                            class="btn-glow w-full bg-gradient-to-r from-purple-600 to-blue-600 text-white font-bold py-4 rounded-xl hover:shadow-2xl transition-all flex items-center justify-center space-x-2"
                            id="submitBtn"
                        >
                            <span class="btn-text">
                                <i class="fas fa-sign-in-alt mr-2"></i>
                                Se Connecter
                            </span>
                            <div class="loading-spinner"></div>
                        </button>
                    </form>
                    
                    <!-- Login Error Messages -->
                    @if ($errors->has('login'))
                        <div class="mt-4 p-4 bg-red-50 border border-red-200 rounded-xl">
                            <div class="flex items-center space-x-2 text-red-700">
                                <i class="fas fa-exclamation-triangle"></i>
                                <span>{{ $errors->first('login') }}</span>
                            </div>
                        </div>
                    @endif
                    
                    <!-- Quick Access Info -->
                    <div class="mt-8 p-4 bg-blue-50 rounded-xl border border-blue-200">
                        <div class="flex items-start space-x-3">
                            <i class="fas fa-info-circle text-blue-600 mt-1"></i>
                            <div>
                                <h4 class="font-semibold text-blue-900 mb-1">Accès Rapide</h4>
                                <p class="text-sm text-blue-700">
                                    Pour la démo : utilisez n'importe quel email et mot de passe. 
                                    Vous serez connecté en tant qu'administrateur.
                                </p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Security Info -->
                    <div class="mt-6 flex items-center justify-center space-x-6 text-sm text-gray-500">
                        <div class="flex items-center">
                            <i class="fas fa-shield-alt mr-2 text-green-600"></i>
                            <span>Connexion sécurisée</span>
                        </div>
                        <div class="flex items-center">
                            <i class="fas fa-lock mr-2 text-green-600"></i>
                            <span>Données protégées</span>
                        </div>
                    </div>
                </div>
                
                <!-- Additional Links -->
                <div class="mt-8 text-center">
                    <p class="text-white/80 mb-4">Nouveau sur StockApp Pro ?</p>
                    <a href="/" class="inline-flex items-center glass-effect text-white px-6 py-3 rounded-full hover:bg-white/20 transition-all">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Retour à l'accueil
                    </a>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Password toggle functionality
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const passwordIcon = document.getElementById('passwordIcon');
            
            if (passwordInput.type === 'password') {
                passwordInput.type = 'text';
                passwordIcon.classList.remove('fa-eye');
                passwordIcon.classList.add('fa-eye-slash');
            } else {
                passwordInput.type = 'password';
                passwordIcon.classList.remove('fa-eye-slash');
                passwordIcon.classList.add('fa-eye');
            }
        }
        
        // Form submission with loading state
        document.getElementById('loginForm').addEventListener('submit', function(e) {
            const submitBtn = document.getElementById('submitBtn');
            submitBtn.classList.add('loading');
            submitBtn.disabled = true;
        });
        
        // Auto-focus on email field
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('email').focus();
        });
        
        // Add some interactive effects
        document.querySelectorAll('.feature-icon').forEach(icon => {
            icon.addEventListener('mouseenter', function() {
                this.style.transform = 'scale(1.1) rotate(5deg)';
            });
            
            icon.addEventListener('mouseleave', function() {
                this.style.transform = 'scale(1) rotate(0deg)';
            });
        });
        
        // Particle animation enhancement
        document.querySelectorAll('.particle').forEach((particle, index) => {
            particle.style.animationDelay = `${index * 0.5}s`;
            particle.style.animationDuration = `${10 + index}s`;
        });
        
        // Form validation feedback
        const emailInput = document.getElementById('email');
        const passwordInput = document.getElementById('password');
        
        emailInput.addEventListener('blur', function() {
            if (this.value && !this.value.includes('@')) {
                this.classList.add('border-red-500');
            } else {
                this.classList.remove('border-red-500');
            }
        });
        
        passwordInput.addEventListener('input', function() {
            if (this.value.length > 0 && this.value.length < 6) {
                this.classList.add('border-yellow-500');
            } else {
                this.classList.remove('border-yellow-500');
            }
        });
        
        // Add keyboard navigation
        document.addEventListener('keydown', function(e) {
            if (e.key === 'Enter') {
                const activeElement = document.activeElement;
                if (activeElement.id === 'email') {
                    e.preventDefault();
                    document.getElementById('password').focus();
                }
            }
        });
    </script>
</body>
</html>
