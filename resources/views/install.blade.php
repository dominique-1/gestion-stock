@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h4>Installation de la base de données</h4>
                </div>
                <div class="card-body">
                    <p>Cliquez sur le bouton ci-dessous pour créer les tables nécessaires à l'application.</p>
                    
                    <form method="POST" action="{{ route('install.database') }}">
                        @csrf
                        <button type="submit" class="btn btn-primary">
                            <i class="fas fa-database"></i> Créer les tables
                        </button>
                    </form>
                    
                    @if(session('success'))
                        <div class="alert alert-success mt-3">
                            {{ session('success') }}
                        </div>
                    @endif
                    
                    @if(session('error'))
                        <div class="alert alert-danger mt-3">
                            {{ session('error') }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
