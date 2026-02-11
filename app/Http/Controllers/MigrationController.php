<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

class MigrationController extends Controller
{
    public function migrate()
    {
        try {
            Artisan::call('migrate', ['--force' => true]);
            return response()->json([
                'success' => true,
                'message' => 'Migrations exécutées avec succès',
                'output' => Artisan::output()
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Erreur lors des migrations',
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
