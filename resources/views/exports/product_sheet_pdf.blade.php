<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Fiche produit : {{ $product->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { border-bottom: 2px solid #333; padding-bottom: 5px; }
        .section { margin-top: 20px; }
        .field { margin-bottom: 8px; }
        .field strong { display: inline-block; width: 150px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>{{ $product->name }}</h1>

    <div class="section">
        <div class="field"><strong>Référence :</strong> {{ $product->barcode ?? '-' }}</div>
        <div class="field"><strong>Catégorie :</strong> {{ $product->category->name ?? '-' }}</div>
        <div class="field"><strong>Fournisseur :</strong> {{ $product->supplier ?? '-' }}</div>
        <div class="field"><strong>Prix unitaire :</strong> {{ number_format($product->price, 2) }} €</div>
        <div class="field"><strong>Stock actuel :</strong> {{ $product->current_stock }}</div>
        <div class="field"><strong>Stock minimum :</strong> {{ $product->stock_min }}</div>
        <div class="field"><strong>Stock optimal :</strong> {{ $product->stock_optimal }}</div>
        <div class="field"><strong>Valeur stock :</strong> {{ number_format($product->stockValue(), 2) }} €</div>
        <div class="field"><strong>Date d'expiration :</strong> {{ $product->expires_at?->format('d/m/Y') ?? '-' }}</div>
        <div class="field"><strong>Description :</strong> {{ $product->description ?? '-' }}</div>
    </div>

    <div class="section">
        <h2>Mouvements récents</h2>
        <table>
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Type</th>
                    <th>Raison</th>
                    <th>Quantité</th>
                    <th>Utilisateur</th>
                </tr>
            </thead>
            <tbody>
                @foreach($product->stockMovements->take(10) as $m)
                    <tr>
                        <td>{{ $m->moved_at->format('d/m/Y H:i') }}</td>
                        <td>{{ $m->type }}</td>
                        <td>{{ $m->reason }}</td>
                        <td>{{ $m->quantity }}</td>
                        <td>{{ $m->user->name }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    @if($product->documents->count())
        <div class="section">
            <h2>Documents</h2>
            <ul>
                @foreach($product->documents as $doc)
                    <li>{{ $doc->original_name }} ({{ $doc->mime }}, {{ $doc->size }} octets)</li>
                @endforeach
            </ul>
        </div>
    @endif
</body>
</html>
