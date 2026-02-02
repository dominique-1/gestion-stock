<?php

require_once 'vendor/autoload.php';

$app = require_once 'bootstrap/app.php';

try {
    $controller = new App\Http\Controllers\Web\CategoryController();
    echo "Controller créé avec succès\n";
    
    $result = $controller->index();
    echo "Index method exécuté avec succès\n";
    
} catch (Exception $e) {
    echo "Erreur: " . $e->getMessage() . "\n";
    echo "Fichier: " . $e->getFile() . ":" . $e->getLine() . "\n";
}
