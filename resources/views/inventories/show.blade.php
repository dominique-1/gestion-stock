@extends('layouts.app')

@section('title', 'Inventaire du ' . $inventory->performed_at->format('d/m/Y'))

@section('content')
<div class="flex justify-between items-center mb-6">
    <div class="flex items-center">
        <a href="{{ route('inventories.index') }}" class="bg-gray-600 text-white px-4 py-2 rounded hover:bg-gray-700 mr-4">
            <i class="fas fa-arrow-left mr-2"></i>Retour
        </a>
        <h1 class="text-2xl font-bold">Inventaire du {{ $inventory->performed_at->format('d/m/Y H:i') }}</h1>
    </div>
    <div>
        @if($inventory->status === 'draft')
            <form method="POST" action="{{ route('inventories.close', $inventory->id) }}" class="inline">
                @csrf
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded hover:bg-green-700" onclick="return confirm('Clôturer cet inventaire et appliquer les ajustements ?')">Clôturer</button>
            </form>
        @endif
        <form method="POST" action="{{ route('inventories.destroy', $inventory->id) }}" class="inline ml-2">
            @csrf
            @method('DELETE')
            <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded hover:bg-red-700" onclick="return confirm('Supprimer cet inventaire ?')">Supprimer</button>
        </form>
    </div>
</div>

<div class="bg-white p-6 rounded shadow mb-6">
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div><strong>Date/Heure :</strong> {{ $inventory->performed_at->format('d/m/Y H:i') }}</div>
        <div><strong>Opérateur :</strong> {{ $inventory->user->name }}</div>
        <div><strong>Statut :</strong> <span class="px-2 py-1 text-xs rounded {{ $inventory->status === 'closed' ? 'bg-green-100 text-green-800' : 'bg-yellow-100 text-yellow-800' }}">{{ $inventory->status === 'closed' ? 'Clôturé' : 'Brouillon' }}</span></div>
        @if($inventory->note)
            <div class="md:col-span-2"><strong>Note :</strong> {{ $inventory->note }}</div>
        @endif
    </div>
</div>

@if($inventory->status === 'draft')
    <div class="bg-white p-6 rounded shadow mb-6">
        <h2 class="text-xl font-bold mb-4">Ajouter une ligne</h2>
        <form method="POST" action="{{ route('inventories.lines.store', $inventory) }}" class="grid grid-cols-1 md:grid-cols-4 gap-4">
            @csrf
            <select name="product_id" class="px-3 py-2 border rounded" required>
                <option value="">Produit</option>
                @foreach(\App\Models\Product::orderBy('name')->pluck('name', 'id') as $id => $name)
                    <option value="{{ $id }}">{{ $name }}</option>
                @endforeach
            </select>
            <input type="number" name="qty_expected" placeholder="Qté attendue" class="px-3 py-2 border rounded" required>
            <input type="number" name="qty_counted" placeholder="Qté comptée" class="px-3 py-2 border rounded" required>
            <button type="submit" class="bg-blue-600 text-white px-4 py-2 rounded hover:bg-blue-700">Ajouter</button>
        </form>
    </div>
@endif

<div class="bg-white rounded shadow overflow-hidden">
    <h2 class="text-xl font-bold p-6 pb-0">Lignes</h2>
    <table class="w-full">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Produit</th>
                <th class="p-3 text-right">Attendu</th>
                <th class="p-3 text-right">Compté</th>
                <th class="p-3 text-right">Écart</th>
                <th class="p-3 text-left">Justification</th>
                @if($inventory->status === 'draft')
                    <th class="p-3 text-left">Actions</th>
                @endif
            </tr>
        </thead>
        <tbody>
            @forelse($inventory->lines as $line)
                <tr class="border-b">
                    <td class="p-3">{{ $line->product->name }}</td>
                    <td class="p-3 text-right">{{ $line->qty_expected }}</td>
                    <td class="p-3 text-right">{{ $line->qty_counted }}</td>
                    <td class="p-3 text-right font-bold {{ $line->qty_diff > 0 ? 'text-green-600' : ($line->qty_diff < 0 ? 'text-red-600' : '') }}">
                        {{ $line->qty_diff > 0 ? '+' : '' }}{{ $line->qty_diff }}
                    </td>
                    <td class="p-3">{{ $line->justification ?? '-' }}</td>
                    @if($inventory->status === 'draft')
                        <td class="p-3">
                            <form method="POST" action="{{ route('inventories.lines.destroy', [$inventory, $line]) }}" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="bg-red-600 text-white px-2 py-1 rounded text-xs hover:bg-red-700">Supprimer</button>
                            </form>
                        </td>
                    @endif
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="p-3 text-center text-gray-500">Aucune ligne</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection
