@extends('layouts.app')

@section('title', 'Modifier Inventaire - Design Spectaculaire')

@section('content')
<div class="min-h-screen bg-gradient-to-br from-blue-900 via-indigo-900 to-purple-900">
    <!-- Header spectaculaire -->
    <div class="relative overflow-hidden">
        <div class="absolute inset-0 bg-gradient-to-r from-blue-600/30 via-indigo-600/30 to-purple-600/30 animate-pulse"></div>
        <div class="relative z-10 px-8 py-12">
            <div class="flex items-center justify-between">
                <div>
                    <h1 class="text-5xl font-black text-white mb-2 tracking-tight">
                        <span class="bg-gradient-to-r from-blue-400 via-indigo-500 to-purple-600 bg-clip-text text-transparent animate-gradient">
                            Modifier Inventaire
                        </span>
                    </h1>
                    <p class="text-gray-300 text-lg">Mettez à jour les informations de l'inventaire</p>
                </div>
                <div class="flex items-center space-x-4">
                    <a href="{{ route('inventories.index') }}" class="bg-white/10 backdrop-blur-md text-white px-6 py-3 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300">
                        <i class="fas fa-arrow-left mr-2"></i>Retour
                    </a>
                </div>
            </div>
        </div>
        
        <!-- Éléments décoratifs animés -->
        <div class="absolute top-0 left-0 w-full h-full">
            <div class="absolute top-10 left-10 w-4 h-4 bg-blue-400 rounded-full animate-bounce"></div>
            <div class="absolute top-20 right-20 w-6 h-6 bg-indigo-400 rounded-full animate-ping"></div>
            <div class="absolute bottom-10 left-1/4 w-3 h-3 bg-purple-400 rounded-full animate-pulse"></div>
        </div>
    </div>

    <div class="px-8 py-8">
        <div class="max-w-6xl mx-auto">
            <!-- Formulaire d'édition spectaculaire -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20">
                <form method="POST" action="{{ route('inventories.update', $inventory->id) }}" class="space-y-6">
                    @csrf
                    @method('PUT')
                    
                    <!-- Messages d'erreur -->
                    @if ($errors->any())
                        <div class="bg-red-500/20 backdrop-blur-md border border-red-500/30 text-red-300 px-6 py-4 rounded-2xl mb-6">
                            <div class="flex items-center mb-2">
                                <i class="fas fa-exclamation-triangle mr-2"></i>
                                <span class="font-bold">Erreurs de validation</span>
                            </div>
                            <ul class="space-y-1">
                                @foreach ($errors->all() as $error)
                                    <li>• {{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <!-- Informations principales -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Référence</label>
                            <input type="text" value="{{ $inventory->reference }}" readonly 
                                   class="w-full bg-white/5 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white/60 cursor-not-allowed">
                        </div>
                        
                        <div>
                            <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Statut</label>
                            <select name="status" class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                                <option value="in_progress" {{ $inventory->status == 'in_progress' ? 'selected' : '' }}>En cours</option>
                                <option value="completed" {{ $inventory->status == 'completed' ? 'selected' : '' }}>Terminé</option>
                                <option value="archived" {{ $inventory->status == 'archived' ? 'selected' : '' }}>Archivé</option>
                            </select>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Date de réalisation</label>
                            <input type="datetime-local" name="performed_at" 
                                   value="{{ $inventory->performed_at->format('Y-m-d\TH:i') }}"
                                   class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300">
                        </div>
                        
                        <div>
                            <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Opérateur</label>
                            <input type="text" value="{{ $inventory->user->name ?? 'Admin' }}" readonly 
                                   class="w-full bg-white/5 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white/60 cursor-not-allowed">
                        </div>
                    </div>

                    <div>
                        <label class="text-white/80 text-sm font-bold uppercase tracking-wider mb-2 block">Notes</label>
                        <textarea name="note" rows="4" 
                                  class="w-full bg-white/10 backdrop-blur-md border border-white/30 rounded-2xl px-6 py-4 text-white placeholder-white/50 focus:outline-none focus:border-white/60 focus:bg-white/20 transition-all duration-300"
                                  placeholder="Ajoutez des notes ou observations...">{{ $inventory->note ?? '' }}</textarea>
                    </div>

                    <!-- Boutons d'action -->
                    <div class="flex justify-end space-x-4 pt-6">
                        <a href="{{ route('inventories.show', $inventory->id) }}" 
                           class="bg-white/10 backdrop-blur-md text-white px-8 py-4 rounded-2xl border border-white/20 hover:bg-white/20 transition-all duration-300">
                            <i class="fas fa-eye mr-2"></i>Voir les détails
                        </a>
                        <button type="submit" 
                                class="bg-gradient-to-r from-blue-600 to-indigo-600 text-white px-8 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-blue-500/50">
                            <i class="fas fa-save mr-3"></i>Enregistrer les modifications
                        </button>
                    </div>
                </form>
            </div>

            <!-- Actions rapides -->
            <div class="bg-white/10 backdrop-blur-xl rounded-3xl p-8 border border-white/20 mt-6">
                <h3 class="text-xl font-bold text-white mb-6">Actions Rapides</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <a href="{{ route('inventories.show', $inventory->id) }}" 
                       class="bg-gradient-to-r from-green-600 to-emerald-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-green-500/50 text-center block">
                        <i class="fas fa-eye mr-2"></i>Voir les lignes
                    </a>
                    
                    @if($inventory->status == 'completed')
                        <form action="{{ route('inventories.close', $inventory->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-gradient-to-r from-yellow-600 to-orange-600 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-yellow-500/50">
                                <i class="fas fa-check mr-2"></i>Clore l'inventaire
                            </button>
                        </form>
                    @endif
                    
                    @if($inventory->status == 'completed')
                        <form action="{{ route('inventories.archive', $inventory->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" 
                                    class="bg-gradient-to-r from-gray-600 to-gray-700 text-white px-6 py-4 rounded-2xl font-bold hover:scale-105 transition-all duration-300 shadow-2xl hover:shadow-gray-500/50">
                                <i class="fas fa-archive mr-2"></i>Archiver
                            </button>
                        </form>
                    @endif
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
