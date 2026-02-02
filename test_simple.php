<?php

echo "Test du CategoryController\n";

try {
    // Simuler une requête HTTP simple
    $_SERVER['REQUEST_METHOD'] = 'GET';
    $_SERVER['REQUEST_URI'] = '/categories';
    
    // Créer une application Laravel minimale
    $app = require_once 'bootstrap/app.php';
    
    // Créer le controller
    $controller = new App\Http\Controllers\Web\CategoryController();
    
    echo "Controller créé avec succès\n";
    
    // Tester la méthode index
    $result = $controller->index();
    echo "Index method exécutée avec succès\n";
    echo "Type de retour: " . gettype($result) . "\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
}
