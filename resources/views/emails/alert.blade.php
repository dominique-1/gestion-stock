<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Alerte Stock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f4f4f4;
        }
        .container {
            background: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 20px;
            border-radius: 10px 10px 0 0;
            text-align: center;
        }
        .alert-critical {
            border-left: 5px solid #dc3545;
            background-color: #f8d7da;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .alert-warning {
            border-left: 5px solid #ffc107;
            background-color: #fff3cd;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .alert-info {
            border-left: 5px solid #17a2b8;
            background-color: #d1ecf1;
            padding: 15px;
            margin: 20px 0;
            border-radius: 5px;
        }
        .product-info {
            background: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 5px;
            margin: 10px 5px;
            text-align: center;
        }
        .footer {
            text-align: center;
            margin-top: 30px;
            color: #666;
            font-size: 12px;
        }
        .icon {
            font-size: 24px;
            margin-right: 10px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>🚨 Alerte Stock</h1>
            <p>Gestion de Stock - Système d'Alertes Automatiques</p>
        </div>

        <div class="alert-{{ $alert->level }}">
            <h3>
                <span class="icon">
                    @if($alert->level === 'critical')⚠️@elseif($alert->level === 'warning')⚡@elseℹ️@endif
                </span>
                {{ ucfirst($alert->level) }}
            </h3>
            <p>{{ $alert->message }}</p>
            <p><strong>Date :</strong> {{ $alert->created_at->format('d/m/Y H:i') }}</p>
        </div>

        @if($product)
        <div class="product-info">
            <h3>📦 Informations sur le produit</h3>
            <p><strong>Nom :</strong> {{ $product->name }}</p>
            <p><strong>Référence :</strong> {{ $product->reference ?? 'N/A' }}</p>
            <p><strong>Catégorie :</strong> {{ $product->category->name ?? 'N/A' }}</p>
            <p><strong>Stock actuel :</strong> {{ $product->current_stock }}</p>
            <p><strong>Stock minimum :</strong> {{ $product->stock_min }}</p>
            <p><strong>Stock optimal :</strong> {{ $product->stock_optimal }}</p>
            @if($product->expires_at)
            <p><strong>Date d'expiration :</strong> {{ $product->expires_at->format('d/m/Y') }}</p>
            @endif
        </div>
        @endif

        <div style="text-align: center; margin: 30px 0;">
            <a href="{{ url('/alerts') }}" class="btn">Voir toutes les alertes</a>
            <a href="{{ url('/products') }}" class="btn">Gérer les produits</a>
        </div>

        <div class="footer">
            <p>Cet email a été généré automatiquement par le système de gestion de stock.</p>
            <p>Si vous ne souhaitez plus recevoir ces alertes, veuillez contacter votre administrateur.</p>
        </div>
    </div>
</body>
</html>
