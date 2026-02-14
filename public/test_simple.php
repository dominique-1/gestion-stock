<?php

echo "<!DOCTYPE html>";
echo "<html><head><title>Application Stock - Test</title></head><body>";
echo "<h1>🎉 Application fonctionne!</h1>";
echo "<p>Serveur PHP opérationnel</p>";
echo "<p>Heure: " . date('Y-m-d H:i:s') . "</p>";
echo "<p>Version PHP: " . phpversion() . "</p>";
echo "<p>Répertoire: " . __DIR__ . "</p>";

echo "<h2>📋 Extensions PHP</h2>";
$extensions = ['pdo', 'sqlite3', 'mysqli', 'curl', 'json'];
foreach ($extensions as $ext) {
    $status = extension_loaded($ext) ? '✅' : '❌';
    echo "<p>$status $ext</p>";
}

echo "<h2>🗄️ Test Base de Données SQLite</h2>";
try {
    $dbPath = __DIR__ . '/../database/database.sqlite';
    if (!file_exists(dirname($dbPath))) {
        mkdir(dirname($dbPath), 0755, true);
    }
    
    $pdo = new PDO("sqlite:$dbPath");
    echo "<p>✅ Connexion SQLite réussie</p>";
    
    $pdo->exec("CREATE TABLE IF NOT EXISTS test (id INTEGER PRIMARY KEY, name TEXT)");
    $stmt = $pdo->prepare("INSERT INTO test (name) VALUES (?)");
    $stmt->execute(['Test ' . date('H:i:s')]);
    echo "<p>✅ Insertion réussie</p>";
    
} catch (Exception $e) {
    echo "<p>❌ Erreur: " . $e->getMessage() . "</p>";
}

echo "<h2>🔗 Liens utiles</h2>";
echo "<p><a href='/'>Page d'accueil</a></p>";
echo "<p><a href='/test_db.php'>Test DB complet</a></p>";

echo "</body></html>";
?>
