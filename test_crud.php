<?php

echo "=== Test du CRUD de catégories ===" . PHP_EOL;

require_once 'vendor/autoload.php';

// Simuler une requête HTTP
$_SERVER['REQUEST_METHOD'] = 'GET';
$_SERVER['REQUEST_URI'] = '/categories';

try {
    $app = require_once 'bootstrap/app.php';
    
    // Créer le controller
    $controller = new App\Http\Controllers\Web\CategoryController();
    
    echo "✅ Controller créé avec succès" . PHP_EOL;
    
    // Tester la méthode index
    $result = $controller->index();
    echo "✅ Index method exécutée avec succès" . PHP_EOL;
    echo "Type de retour: " . gettype($result) . PHP_EOL;
    
    // Vérifier si c'est une vue Laravel
    if (method_exists($result, 'render')) {
        echo "✅ Vue Laravel valide" . PHP_EOL;
    }
    
} catch (Exception $e) {
    echo "❌ Erreur: " . $e->getMessage() . PHP_EOL;
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . PHP_EOL;
    echo "Trace: " . $e->getTraceAsString() . PHP_EOL;
}

echo PHP_EOL . "=== Test terminé ===" . PHP_EOL;
