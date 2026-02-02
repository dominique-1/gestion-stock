@extends('layouts.app')

@section('title', 'Ajouter une catégorie')

@section('content')
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
        {{ session('error') }}
    </div>
@endif

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold">Ajouter une catégorie</h1>
    <a href="{{ route('categories.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">
        <i class="fas fa-arrow-left mr-2"></i>Retour
    </a>
</div>

<form method="POST" action="{{ route('categories.store') }}" class="bg-white p-6 rounded shadow">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="name">Nom *</label>
            <input type="text" name="name" id="name" class="w-full px-3 py-2 border rounded" required value="{{ old('name') }}">
            @error('name')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
        <div>
            <label class="block text-gray-700 text-sm font-bold mb-2" for="parent_id">Catégorie parente</label>
            <select name="parent_id" id="parent_id" class="w-full px-3 py-2 border rounded">
                <option value="">Aucune (racine)</option>
                @foreach($parentOptions as $id => $name)
                    <option value="{{ $id }}" {{ old('parent_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                @endforeach
            </select>
            @error('parent_id')
                <span class="text-red-500 text-xs">{{ $message }}</span>
            @enderror
        </div>
    </div>
    <div class="mt-6">
        <label class="block text-gray-700 text-sm font-bold mb-2" for="description">Description</label>
        <textarea name="description" id="description" rows="3" class="w-full px-3 py-2 border rounded">{{ old('description') }}</textarea>
        @error('description')
            <span class="text-red-500 text-xs">{{ $message }}</span>
        @enderror
    </div>
    <div class="mt-6 flex justify-end space-x-4">
        <a href="{{ route('categories.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700">Annuler</a>
        <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Créer</button>
    </div>
</form>
@endsection
