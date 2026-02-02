<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Produits</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { border-bottom: 2px solid #333; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .stock-low { color: red; font-weight: bold; }
        .stock-optimal { color: green; }
        .stock-high { color: orange; }
    </style>
</head>
<body>
    <h1>Liste des Produits</h1>
    <p><strong>Généré le :</strong> {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Nom</th>
                <th>Code-barres</th>
                <th>Catégorie</th>
                <th>Fournisseur</th>
                <th>Prix</th>
                <th>Stock actuel</th>
                <th>Stock min</th>
                <th>Stock optimal</th>
                <th>Valeur stock</th>
            </tr>
        </thead>
        <tbody>
            @foreach($products as $product)
                <tr>
                    <td>{{ $product->name }}</td>
                    <td>{{ $product->barcode }}</td>
                    <td>{{ $product->category->name ?? '' }}</td>
                    <td>{{ $product->supplier }}</td>
                    <td>{{ number_format($product->price, 2) }} €</td>
                    <td class="{{ $product->current_stock <= $product->stock_min ? 'stock-low' : ($product->current_stock >= $product->stock_optimal ? 'stock-high' : 'stock-optimal') }}">
                        {{ $product->current_stock }}
                    </td>
                    <td>{{ $product->stock_min }}</td>
                    <td>{{ $product->stock_optimal }}</td>
                    <td>{{ number_format($product->stockValue(), 2) }} €</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
