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
            // Créer la base de données SQLite si elle n'existe pas
            $databasePath = database_path('database.sqlite');
            if (!File::exists($databasePath)) {
                File::put($databasePath, '');
            }
            
            // Exécuter les migrations
            Artisan::call('migrate', ['--force' => true]);
            
            return redirect()->route('install.index')->with('success', 'Base de données installée avec succès !');
            
        } catch (\Exception $e) {
            return redirect()->route('install.index')->with('error', 'Erreur: ' . $e->getMessage());
        }
    }
}
