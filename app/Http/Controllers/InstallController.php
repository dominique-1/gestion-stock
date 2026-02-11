<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\File;

class InstallController extends Controller
{
    public function index()
    {
        return view('install');
    }
    
    public function installDatabase()
    {
        try {
            // Créer le dossier database s'il n'existe pas
            $databaseDir = database_path();
            if (!File::exists($databaseDir)) {
                File::makeDirectory($databaseDir, 0755, true);
            }
            
            // Créer la base de données SQLite si elle n'existe pas
            $databasePath = database_path('database.sqlite');
            if (!File::exists($databasePath)) {
                File::put($databasePath, '');
                // Donner les permissions nécessaires
                chmod($databasePath, 0644);
            }
            
            // Vérifier que le fichier est bien créé
            if (!File::exists($databasePath)) {
                throw new \Exception("Impossible de créer le fichier de base de données");
            }
            
            // Exécuter les migrations
            Artisan::call('migrate', ['--force' => true]);
            
            return redirect()->route('install.index')->with('success', 'Base de données installée avec succès !');
            
        } catch (\Exception $e) {
            return redirect()->route('install.index')->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
