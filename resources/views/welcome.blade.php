<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Gestion de Stock - Accueil</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css" integrity="sha512-iecdL0lUU0a6GkKZ0j6r2zvfKfyHigFCK/UlKgCnzuEjLxA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
    <style>
        .fa-box::before { content: "📦"; font-size: 1.5em; }
        .fa-exchange-alt::before { content: "🔄"; font-size: 1.5em; }
        .fa-clipboard-check::before { content: "📋"; font-size: 1.5em; }
        .fa-chart-line::before { content: "📈"; font-size: 1.5em; }
        .fa-warehouse::before { content: "🏢"; font-size: 1.5em; }
        .fa-sign-in-alt::before { content: "🔑"; font-size: 1.5em; }
    </style>
</head>
<body class="bg-gray-100">
    <!-- Header -->
    <header class="bg-blue-600 text-white shadow-lg">
        <div class="container mx-auto px-4 py-6">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-3">
                    <i class="fas fa-warehouse text-2xl"></i>
                    <h1 class="text-2xl font-bold">Gestion de Stock</h1>
                </div>
                <nav class="flex space-x-6">
                    <a href="{{ route('login') }}" class="hover:text-blue-200 transition">
                        <i class="fas fa-sign-in-alt mr-2"></i>Connexion
                    </a>
                </nav>
            </div>
        </div>
    </header>

    <!-- Hero Section -->
    <main class="container mx-auto px-4 py-12">
        <div class="text-center mb-12">
            <h2 class="text-4xl font-bold text-gray-800 mb-4">
                Bienvenue dans votre Système de Gestion de Stock
            </h2>
            <p class="text-xl text-gray-600 max-w-3xl mx-auto">
                Gérez efficacement vos produits, catégories, mouvements et inventaires avec notre solution complète.
            </p>
        </div>

        <!-- Features Grid -->
        <div class="grid md:grid-cols-2 lg:grid-cols-4 gap-6 mb-12">
            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="text-blue-600 text-3xl mb-4">
                    <i class="fas fa-box"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Gestion des Produits</h3>
                <p class="text-gray-600">Ajoutez, modifiez et suivez tous vos produits en temps réel.</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="text-green-600 text-3xl mb-4">
                    <i class="fas fa-exchange-alt"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Mouvements de Stock</h3>
                <p class="text-gray-600">Enregistrez les entrées et sorties de stock facilement.</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="text-purple-600 text-3xl mb-4">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Inventaires</h3>
                <p class="text-gray-600">Effectuez des inventaires périodiques pour vérifier vos stocks.</p>
            </div>

            <div class="bg-white rounded-lg shadow-md p-6 hover:shadow-lg transition">
                <div class="text-orange-600 text-3xl mb-4">
                    <i class="fas fa-chart-line"></i>
                </div>
                <h3 class="text-xl font-semibold mb-2">Analyses & Rapports</h3>
                <p class="text-gray-600">Exportez des rapports détaillés en CSV, Excel et PDF.</p>
            </div>
        </div>

        <!-- Call to Action -->
        <div class="text-center">
            <div class="bg-blue-50 rounded-lg p-8 max-w-2xl mx-auto">
                <h3 class="text-2xl font-bold text-gray-800 mb-4">
                    Prêt à gérer votre stock ?
                </h3>
                <p class="text-gray-600 mb-6">
                    Connectez-vous pour accéder à votre tableau de bord et commencer à utiliser l'application.
                </p>
                <a href="{{ route('login') }}" class="inline-block bg-blue-600 text-white px-8 py-3 rounded-lg font-semibold hover:bg-blue-700 transition">
                    <i class="fas fa-sign-in-alt mr-2"></i>
                    Se Connecter
                </a>
            </div>
        </div>
    </main>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-12">
        <div class="container mx-auto px-4 text-center">
            <p>&copy; {{ date('Y') }} Gestion de Stock. Tous droits réservés.</p>
        </div>
    </footer>
</body>
</html>
