<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
<title>Rapport de Gestion de Stock</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { border-bottom: 2px solid #333; padding-bottom: 5px; }
        h2 { border-bottom: 1px solid #666; padding-bottom: 3px; margin-top: 30px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .summary { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin: 20px 0; }
        .stock-low { color: red; font-weight: bold; }
        .stock-optimal { color: green; }
        .stock-high { color: orange; }
    </style>
</head>
<body>
    <h1>Rapport de Gestion de Stock</h1>
    <p><strong>Généré le :</strong> {{ now()->format('d/m/Y H:i') }}</p>

    <div class="summary">
        <h3>Résumé du Stock</h3>
        <p><strong>Total produits :</strong> {{ $products->count() }}</p>
        <p><strong>Valeur totale du stock :</strong> {{ number_format($products->sum(function($p) { return $p->stockValue(); }), 2) }} €</p>
        <p><strong>Produits en stock faible :</strong> {{ $products->where('current_stock', '<=', 'stock_min')->count() }}</p>
        <p><strong>Mouvements récents (100 derniers) :</strong> {{ $movements->count() }}</p>
    </div>

    <h2>Produits en Stock Faible</h2>
    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Stock actuel</th>
                <th>Stock min</th>
                <th>Valeur</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products->where('current_stock', '<=', 'stock_min') as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td class="stock-low">{{ $product->current_stock }}</td>
                    <td>{{ $product->stock_min }}</td>
                    <td>{{ number_format($product->stockValue(), 2) }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <h2>Mouvements Récents</h2>
    <table>
        <thead>
            <tr>
                <th>Date</th>
                <th>Produit</th>
                <th>Type</th>
                <th>Quantité</th>
                <th>Utilisateur</th>
            </tr>
        </thead>
        <tbody>
            @foreach($movements as $movement)
                <tr>
                    <td>{{ $movement->moved_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $movement->product->name }}</td>
                    <td>{{ $movement->type }}</td>
                    <td>{{ $movement->quantity }}</td>
                    <td>{{ $movement->user->name }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
