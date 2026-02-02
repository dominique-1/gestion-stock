<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Liste des Inventaires</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        h1 { border-bottom: 2px solid #333; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
        .status { padding: 2px 8px; border-radius: 3px; font-size: 0.9em; }
        .status.completed { background-color: #d4edda; color: #155724; }
        .status.in_progress { background-color: #fff3cd; color: #856404; }
    </style>
</head>
<body>
    <h1>Liste des Inventaires</h1>
    <p><strong>Généré le :</strong> {{ now()->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Opérateur</th>
                <th>Statut</th>
                <th>Note</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventories as $inventory)
                <tr>
                    <td>{{ $inventory->id }}</td>
                    <td>{{ $inventory->performed_at->format('d/m/Y H:i') }}</td>
                    <td>{{ $inventory->user->name }}</td>
                    <td>
                        <span class="status {{ $inventory->status }}">
                            {{ $inventory->status }}
                        </span>
                    </td>
                    <td>{{ $inventory->note ?? '-' }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
