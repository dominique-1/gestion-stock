<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Installation - Gestion de Stock</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="row justify-content-center">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header bg-primary text-white">
                        <h4><i class="fas fa-database"></i> Installation de la base de données</h4>
                    </div>
                    <div class="card-body">
                        <p class="lead">Bienvenue sur votre application de gestion de stock !</p>
                        <p>Pour commencer, nous devons créer les tables nécessaires dans la base de données.</p>
                        
                        <form method="POST" action="{{ route('install.database') }}">
                            @csrf
                            <button type="submit" class="btn btn-primary btn-lg">
                                <i class="fas fa-play"></i> Créer les tables
                            </button>
                        </form>
                        
                        @if(session('success'))
                            <div class="alert alert-success mt-3">
                                <i class="fas fa-check-circle"></i> {{ session('success') }}
                                <hr>
                                <a href="/" class="btn btn-success">Aller à l'application</a>
                            </div>
                        @endif
                        
                        @if(session('error'))
                            <div class="alert alert-danger mt-3">
                                <i class="fas fa-exclamation-triangle"></i> {{ session('error') }}
                            </div>
                        @endif
                        
                        <div class="mt-4">
                            <small class="text-muted">
                                Cette étape ne prendra que quelques secondes et ne doit être effectuée qu'une seule fois.
                            </small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
