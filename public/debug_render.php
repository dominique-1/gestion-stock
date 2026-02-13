<?php

// Test simple pour vérifier si PHP fonctionne
echo "✅ PHP fonctionne<br>";

// Test de connexion à la base de données
try {
    $dbPath = '/var/data/database.sqlite';
    echo "📁 Chemin BDD: " . $dbPath . "<br>";
    
    if (file_exists($dbPath)) {
        echo "✅ Fichier BDD existe<br>";
    } else {
        echo "❌ Fichier BDD n'existe pas<br>";
        // Créer le fichier
        $dir = dirname($dbPath);
        if (!is_dir($dir)) {
            mkdir($dir, 0755, true);
            echo "📁 Répertoire créé<br>";
        }
        touch($dbPath);
        echo "📄 Fichier BDD créé<br>";
    }
    
    // Test PDO
    $pdo = new PDO('sqlite:' . $dbPath);
    echo "✅ Connexion PDO réussie<br>";
    
    // Test requête simple
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM sqlite_master WHERE type='table'");
    $result = $stmt->fetch();
    echo "📊 Tables trouvées: " . $result['count'] . "<br>";
    
} catch (Exception $e) {
    echo "❌ Erreur BDD: " . $e->getMessage() . "<br>";
}

// Test Laravel bootstrap
try {
    require_once __DIR__ . '/../vendor/autoload.php';
    $app = require_once __DIR__ . '/../bootstrap/app.php';
    echo "✅ Laravel bootstrap OK<br>";
    
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    echo "✅ Laravel kernel OK<br>";
    
} catch (Exception $e) {
    echo "❌ Erreur Laravel: " . $e->getMessage() . "<br>";
}

echo "🎉 Test terminé";
?>
