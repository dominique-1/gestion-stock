<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Inventaire #{{ $inventory->id }}</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { border-bottom: 2px solid #333; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .diff { font-weight: bold; }
        .diff.positive { color: green; }
        .diff.negative { color: red; }
    </style>
</head>
<body>
    <h1>Inventaire #{{ $inventory->id }}</h1>
    <p><strong>Date :</strong> {{ $inventory->performed_at->format('d/m/Y H:i') }}</p>
    <p><strong>Opérateur :</strong> {{ $inventory->user->name }}</p>
    <p><strong>Note :</strong> {{ $inventory->note ?? '-' }}</p>
    <p><strong>Statut :</strong> {{ $inventory->status }}</p>

    <h2>Détail des lignes</h2>
    <table>
        <thead>
            <tr>
                <th>Produit</th>
                <th>Quantité attendue</th>
                <th>Quantité comptée</th>
                <th>Écart</th>
                <th>Justification</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventory->lines as $line)
                <tr>
                    <td>{{ $line->product->name }}</td>
                    <td>{{ $line->qty_expected }}</td>
                    <td>{{ $line->qty_counted }}</td>
                    <td class="diff {{ $line->qty_diff > 0 ? 'positive' : ($line->qty_diff < 0 ? 'negative' : '') }}">
                        {{ $line->qty_diff > 0 ? '+' : '' }}{{ $line->qty_diff }}
                    </td>
                    <td>{{ $line->justification ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
