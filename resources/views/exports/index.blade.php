@extends('layouts.app')

@section('title', 'Exports - Design Spectaculaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-indigo-900 via-blue-900 to-purple-900">
    <!-- Header spectaculaire -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-indigo-600/30 via-blue-600/30 to-purple-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-indigo-400 via-blue-500 to-purple-600 bg-clip-text text-transparent animate-gradient">
                            Centre d'Exports
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Téléchargez vos données en plusieurs formats</p>
                </div>
                <div class="flex items-center space-x-4">
                    <div class="bg-white/10 backdrop-blur-md rounded-2xl px-6 py-3 border border-white/20">
                        <span class="text-white font-semibold">{{ now()->format('d/m/Y H:i') }}</span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-4 h-4 bg-indigo-400 rounded-full animate-bounce"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-blue-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-10 left-1/4 w-3 h-3 bg-purple-400 rounded-full animate-pulse"></div>
            <div class="absolute top-1/3 right-1/4 w-5 h-5 bg-cyan-400 rounded-full animate-bounce animation-delay-2000"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-12">
            <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-3xl p-6 border border-green-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-400 text-sm font-bold uppercase tracking-wider mb-1">Total Produits</p>
                        <p class="text-3xl font-black text-white">{{ $stats['total_products'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-box text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-3xl p-6 border border-blue-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-400 text-sm font-bold uppercase tracking-wider mb-1">Mouvements</p>
                        <p class="text-3xl font-black text-white">{{ $stats['total_movements'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exchange-alt text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-3xl p-6 border border-purple-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-400 text-sm font-bold uppercase tracking-wider mb-1">Inventaires</p>
                        <p class="text-3xl font-black text-white">{{ $stats['total_inventories'] ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-clipboard-list text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Exports par catégorie -->
        @foreach($available_exports as $category => $exports)
        <div class="mb-12">
            <div class="flex items-center mb-6">
                <div class="w-3 h-3 bg-gradient-to-r {{ $category == 'CSV' ? 'from-green-400 to-emerald-400' : ($category == 'Excel' ? 'from-blue-400 to-indigo-400' : 'from-red-400 to-pink-400') }} rounded-full mr-3 animate-pulse"></div>
                <h2 class="text-2xl font-black text-white">{{ $category }}</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                @foreach($exports as $export)
                <a href="{{ route($export['route']) }}" class="group relative">
                    <!-- Fond animé -->
                    <div class="absolute inset-0 bg-gradient-to-br {{ $category == 'CSV' ? 'from-green-600/20 to-emerald-600/20' : ($category == 'Excel' ? 'from-blue-600/20 to-indigo-600/20' : 'from-red-600/20 to-pink-600/20') }} rounded-3xl blur-xl opacity-0 group-hover:opacity-100 transition-all duration-500"></div>
                    
                    <!-- Carte export -->
                    <div class="relative bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 hover:border-white/40 transition-all duration-500 hover:scale-105">
                        <div class="flex items-center justify-between mb-6">
                            <div class="w-16 h-16 bg-gradient-to-br {{ $category == 'CSV' ? 'from-green-500 to-emerald-500' : ($category == 'Excel' ? 'from-blue-500 to-indigo-500' : 'from-red-500 to-pink-500') }} rounded-2xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                                <i class="fas {{ $export['icon'] }} text-white text-2xl"></i>
                            </div>
                            <div class="w-12 h-12 bg-white/10 backdrop-blur-md rounded-xl flex items-center justify-center group-hover:rotate-180 transition-transform duration-500">
                                <i class="fas fa-download text-white"></i>
                            </div>
                        </div>
                        
                        <h3 class="text-xl font-black text-white mb-3 group-hover:text-indigo-300 transition-colors duration-300">
                            {{ $export['name'] }}
                        </h3>
                        
                        <p class="text-gray-400 text-sm leading-relaxed">
                            {{ $export['description'] }}
                        </p>
                        
                        <div class="mt-6 flex items-center text-white/80 text-sm">
                            <i class="fas fa-file-alt mr-2"></i>
                            <span>{{ $category }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endforeach

        <!-- Actions rapides -->
        <div class="bg-gradient-to-r from-purple-600/20 to-blue-600/20 rounded-3xl p-8 border border-white/20">
            <h3 class="text-2xl font-bold text-white mb-6">Actions Rapides</h3>
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                <a href="{{ route('dashboard') }}" class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <i class="fas fa-chart-line text-3xl text-blue-400 mb-3 group-hover:scale-110 transition-transform duration-300"></i>
                    <p class="text-white font-semibold">Dashboard</p>
                </a>
                <a href="{{ route('products.index') }}" class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <i class="fas fa-box text-3xl text-green-400 mb-3 group-hover:scale-110 transition-transform duration-300"></i>
                    <p class="text-white font-semibold">Produits</p>
                </a>
                <a href="{{ route('movements.index') }}" class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <i class="fas fa-exchange-alt text-3xl text-yellow-400 mb-3 group-hover:scale-110 transition-transform duration-300"></i>
                    <p class="text-white font-semibold">Mouvements</p>
                </a>
                <a href="{{ route('alerts.index') }}" class="group bg-white/10 backdrop-blur-xl rounded-2xl p-6 hover:bg-white/20 transition-all duration-300 hover:scale-105 border border-white/20">
                    <i class="fas fa-bell text-3xl text-red-400 mb-3 group-hover:scale-110 transition-transform duration-300"></i>
                    <p class="text-white font-semibold">Alertes</p>
                </a>
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

.animation-delay-2000 {
    animation-delay: 2s;
}
</style>
@endsection
