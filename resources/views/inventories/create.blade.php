@extends('layouts.app')

@section('title', 'Nouvel inventaire')

@section('content')
<div class="max-w-2xl mx-auto">
    <h1 class="text-2xl font-bold mb-6">Nouvel inventaire</h1>

    <form method="POST" action="{{ route('inventories.store') }}" class="bg-white p-6 rounded shadow">
        @csrf
        
        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="reference">Référence *</label>
                <input type="text" name="reference" id="reference" class="w-full px-3 py-2 border rounded" required value="{{ old('reference', 'INV-' . date('Y') . '-' . str_pad((session()->get('inventories_count', 0) + 1), 3, '0', STR_PAD_LEFT)) }}" placeholder="INV-2025-001">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="performed_at">Date/Heure *</label>
                <input type="datetime-local" name="performed_at" id="performed_at" class="w-full px-3 py-2 border rounded" required value="{{ old('performed_at', now()->format('Y-m-d\TH:i')) }}">
            </div>
        </div>
        
        <div class="mt-6">
            <label class="block text-gray-700 text-sm font-bold mb-2" for="note">Note</label>
            <textarea name="note" id="note" rows="3" class="w-full px-3 py-2 border rounded" placeholder="Description de l'inventaire...">{{ old('note') }}</textarea>
        </div>
        
        <div class="mt-4">
            <label class="flex items-center">
                <input type="checkbox" name="archive" value="1" class="mr-2">
                <span class="text-gray-700">Archiver cet inventaire directement</span>
            </label>
        </div>
        
        <div class="mt-6 flex justify-end space-x-4">
            <a href="{{ route('inventories.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Annuler</a>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer</button>
        </div>
    </form>
</div>
@endsection
