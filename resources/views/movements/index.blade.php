@extends('layouts.app')

@section('title', 'Mouvements - Design Spectaculaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-orange-900 via-red-900 to-pink-900">
    <!-- Header spectaculaire -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-orange-600/30 via-red-600/30 to-pink-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-orange-400 via-red-500 to-pink-600 bg-clip-text text-transparent animate-gradient">
                            Mouvements de Stock
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Historique complet des entrées et sorties</p>
                </div>
                <div class="flex items-center space-x-4">
                    <!-- Messages flash avec classes pour les toasts -->
                    @if(session()->has('success'))
                    <div class="alert-success hidden">{{ session('success') }}</div>
                    @endif
                    @if(session()->has('error'))
                    <div class="alert-error hidden">{{ session('error') }}</div>
                    @endif
                    <a href="{{ route('movements.create') }}" class="group bg-gradient-to-r from-orange-500 to-red-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-orange-500/50">
                        <i class="fas fa-plus-circle mr-3 group-hover:rotate-90 transition-transform duration-300"></i>
                        Nouveau Mouvement
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-4 h-4 bg-orange-400 rounded-full animate-bounce"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-red-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-10 left-1/4 w-3 h-3 bg-pink-400 rounded-full animate-pulse"></div>
            <div class="absolute top-1/3 right-1/4 w-5 h-5 bg-yellow-400 rounded-full animate-bounce animation-delay-2000"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <!-- Filtres spectaculaires -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 mb-8 border border-white/20">
            <form method="GET" class="grid grid-cols-1 md:grid-cols-5 gap-6">
                <div>
                    <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Produit</label>
                    <select name="product_id" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                        <option value="">Tous les produits</option>
                        <option value="1" {{ request('product_id') == '1' ? 'selected' : '' }}>Laptop Pro 15"</option>
                        <option value="2" {{ request('product_id') == '2' ? 'selected' : '' }}>Moniteur 27"</option>
                        <option value="3" {{ request('product_id') == '3' ? 'selected' : '' }}>Pack stylos</option>
                    </select>
                </div>

                <div>
                    <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Type</label>
                    <select name="type" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                        <option value="">Tous</option>
                        <option value="in" {{ request('type') == 'in' ? 'selected' : '' }}>📥 Entrée</option>
                        <option value="out" {{ request('type') == 'out' ? 'selected' : '' }}>📤 Sortie</option>
                    </select>
                </div>

                <div>
                    <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Date début</label>
                    <input type="date" name="from" value="{{ request('from') }}" 
                           class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                </div>

                <div>
                    <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Date fin</label>
                    <input type="date" name="to" value="{{ request('to') }}" 
                           class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                </div>

                <div class="flex items-end">
                    <button type="submit" class="w-full bg-gradient-to-r from-orange-600 to-red-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-orange-500/50">
                        <i class="fas fa-filter mr-3"></i>
                        Filtrer
                    </button>
                </div>
            </form>
        </div>

        <!-- Statistiques rapides -->
        <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-8">
            <div class="bg-gradient-to-br from-green-500/20 to-emerald-500/20 rounded-3xl p-6 border border-green-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-green-400 text-sm font-bold uppercase tracking-wider mb-1">Entrées</p>
                        <p class="text-3xl font-black text-white">{{ collect($movements)->where('type', 'in')->count() ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500 to-emerald-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-arrow-down text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-red-500/20 to-pink-500/20 rounded-3xl p-6 border border-red-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-red-400 text-sm font-bold uppercase tracking-wider mb-1">Sorties</p>
                        <p class="text-3xl font-black text-white">{{ collect($movements)->where('type', 'out')->count() ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-arrow-up text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-blue-500/20 to-indigo-500/20 rounded-3xl p-6 border border-blue-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-blue-400 text-sm font-bold uppercase tracking-wider mb-1">Solde</p>
                        <p class="text-3xl font-black text-white">{{ (collect($movements)->where('type', 'in')->sum('quantity') ?? 0) - (collect($movements)->where('type', 'out')->sum('quantity') ?? 0) }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-indigo-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-balance-scale text-white"></i>
                    </div>
                </div>
            </div>

            <div class="bg-gradient-to-br from-purple-500/20 to-pink-500/20 rounded-3xl p-6 border border-purple-500/30">
                <div class="flex items-center justify-between">
                    <div>
                        <p class="text-purple-400 text-sm font-bold uppercase tracking-wider mb-1">Total</p>
                        <p class="text-3xl font-black text-white">{{ count($movements) ?? 0 }}</p>
                    </div>
                    <div class="w-12 h-12 bg-gradient-to-br from-purple-500 to-pink-500 rounded-xl flex items-center justify-center">
                        <i class="fas fa-exchange-alt text-white"></i>
                    </div>
                </div>
            </div>
        </div>

        <!-- Liste des mouvements -->
        <div class="bg-white/10 backdrop-blur-xl rounded-3xl border border-white/20 overflow-hidden">
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b border-white/20">
                            <th class="text-left p-6 text-white/80 font-bold uppercase tracking-wider text-sm">Date</th>
                            <th class="text-left p-6 text-white/80 font-bold uppercase tracking-wider text-sm">Produit</th>
                            <th class="text-left p-6 text-white/80 font-bold uppercase tracking-wider text-sm">Type</th>
                            <th class="text-left p-6 text-white/80 font-bold uppercase tracking-wider text-sm">Quantité</th>
                            <th class="text-left p-6 text-white/80 font-bold uppercase tracking-wider text-sm">Motif</th>
                            <th class="text-center p-6 text-white/80 font-bold uppercase tracking-wider text-sm">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movements as $movement)
                        <tr class="border-b border-white/10 hover:bg-white/5 transition-all duration-300">
                            <td class="p-6">
                                @if(isset($movement->moved_at) && $movement->moved_at)
                                    @if($movement->moved_at instanceof \Carbon\Carbon)
                                        <div class="text-white font-semibold">{{ $movement->moved_at->format('d/m/Y') }}</div>
                                        <div class="text-gray-400 text-sm">{{ $movement->moved_at->format('H:i') }}</div>
                                    @else
                                        <div class="text-white font-semibold">{{ $movement->moved_at }}</div>
                                        <div class="text-gray-400 text-sm">N/A</div>
                                    @endif
                                @else
                                    <div class="text-white font-semibold">N/A</div>
                                    <div class="text-gray-400 text-sm">N/A</div>
                                @endif
                            </td>
                            <td class="p-6">
                                <div class="flex items-center space-x-3">
                                    <div class="w-10 h-10 bg-white/10 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-box text-white/60"></i>
                                    </div>
                                    <div>
                                        <div class="text-white font-semibold">{{ $movement->product->name ?? 'N/A' }}</div>
                                        <div class="text-gray-400 text-sm">SKU: {{ $movement->product->barcode ?? 'N/A' }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-6">
                                @if($movement->type == 'in')
                                    <div class="inline-flex items-center bg-green-500/20 text-green-400 px-4 py-2 rounded-full text-sm font-bold">
                                        <i class="fas fa-arrow-down mr-2"></i>Entrée
                                    </div>
                                @else
                                    <div class="inline-flex items-center bg-red-500/20 text-red-400 px-4 py-2 rounded-full text-sm font-bold">
                                        <i class="fas fa-arrow-up mr-2"></i>Sortie
                                    </div>
                                @endif
                            </td>
                            <td class="p-6">
                                <div class="text-2xl font-black {{ $movement->type == 'in' ? 'text-green-400' : 'text-red-400' }}">
                                    {{ $movement->type == 'in' ? '+' : '-' }}{{ $movement->quantity }}
                                </div>
                            </td>
                            <td class="p-6">
                                <div class="text-white font-medium">{{ $movement->reason }}</div>
                                @if(isset($movement->note) && $movement->note)
                                <div class="text-gray-400 text-sm mt-1">{{ Str::limit($movement->note, 50) }}</div>
                                @endif
                            </td>
                            <td class="p-6">
                                <div class="flex items-center justify-center space-x-2">
                                    <a href="{{ route('movements.show', $movement->id) }}" 
                                       class="w-10 h-10 bg-blue-500/20 hover:bg-blue-500/30 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-eye text-blue-400"></i>
                                    </a>
                                    <a href="{{ route('movements.edit', $movement->id) }}" 
                                       class="w-10 h-10 bg-purple-500/20 hover:bg-purple-500/30 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                        <i class="fas fa-edit text-purple-400"></i>
                                    </a>
                                    <form method="POST" action="{{ route('movements.destroy', $movement->id) }}" class="inline" id="deleteForm-{{ $movement->id }}">
                                        @csrf
                                        @method('DELETE')
                                        <button type="button" onclick="testSuppression({{ $movement->id }})" class="w-10 h-10 bg-red-500/20 hover:bg-red-500/30 rounded-xl flex items-center justify-center transition-all duration-300 group-hover:scale-110">
                                            <i class="fas fa-trash text-red-400"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-20">
                                <div class="w-32 h-32 mx-auto mb-6 relative">
                                    <div class="absolute inset-0 bg-gradient-to-r from-gray-600 to-gray-700 rounded-full animate-pulse"></div>
                                    <div class="absolute inset-2 bg-slate-900 rounded-full flex items-center justify-center">
                                        <i class="fas fa-exchange-alt text-4xl text-gray-400"></i>
                                    </div>
                                </div>
                                <h3 class="text-2xl font-black text-white mb-4">Aucun mouvement trouvé</h3>
                                <p class="text-gray-400 text-lg mb-8">Essayez de modifier vos filtres ou enregistrez un premier mouvement</p>
                                <a href="{{ route('movements.create') }}" class="inline-block bg-gradient-to-r from-orange-500 to-red-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-orange-500/50">
                                    <i class="fas fa-plus-circle mr-3"></i>Enregistrer un mouvement
                                </a>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
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

<!-- Inclure le script du modal de confirmation -->
<script src="{{ asset('js/confirm-modal.js') }}"></script>
<script>
function testSuppression(id) {
    console.log('Test suppression pour mouvement ID:', id);
    alert('Test: Vous voulez supprimer le mouvement ID ' + id + ' ?');
    
    // Test direct de soumission du formulaire
    if (confirm('Confirmer la suppression du mouvement ' + id + ' ?')) {
        console.log('Envoi du formulaire:', 'deleteForm-' + id);
        document.getElementById('deleteForm-' + id).submit();
    }
}
</script>

@endsection
