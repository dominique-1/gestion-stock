<?php
echo "PHP Version: " . phpversion() . "\n";
echo "Extensions chargées:\n";
if (extension_loaded('pdo_mysql')) {
    echo "✅ pdo_mysql est chargée\n";
} else {
    echo "❌ pdo_mysql n'est PAS chargée\n";
}

echo "\nToutes les extensions PDO:\n";
$pdo_extensions = array_filter(get_loaded_extensions(), function($ext) {
    return strpos($ext, 'pdo') === 0;
});
foreach ($pdo_extensions as $ext) {
    echo "- $ext\n";
}

echo "\nTest de connexion:\n";
try {
    $pdo = new PDO('mysql:host=localhost;dbname=stock', 'root', '');
    echo "✅ Connexion MySQL réussie\n";
} catch (PDOException $e) {
    echo "❌ Erreur de connexion: " . $e->getMessage() . "\n";
}
?>
