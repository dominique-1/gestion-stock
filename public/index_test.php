<?php

// Page ultra-simple pour tester
echo "<h1>🎉 Test Render</h1>";
echo "<p>✅ PHP fonctionne</p>";
echo "<p>📅 Date: " . date('Y-m-d H:i:s') . "</p>";

// Test environnement
echo "<h2>🔍 Variables d'environnement</h2>";
echo "<p>APP_ENV: " . (getenv('APP_ENV') ?: 'NON DÉFINI') . "</p>";
echo "<p>DB_CONNECTION: " . (getenv('DB_CONNECTION') ?: 'NON DÉFINI') . "</p>";
echo "<p>DB_DATABASE: " . (getenv('DB_DATABASE') ?: 'NON DÉFINI') . "</p>";

// Test écriture fichier
echo "<h2>💾 Test écriture</h2>";
$testFile = '/var/data/test.txt';
if (file_put_contents($testFile, 'Test Render ' . date('Y-m-d H:i:s'))) {
    echo "<p>✅ Écriture réussie: $testFile</p>";
} else {
    echo "<p>❌ Écriture échouée</p>";
}

// Test base de données simple
echo "<h2>🗄️ Test SQLite</h2>";
try {
    $db = new PDO('sqlite:/var/data/database.sqlite');
    $db->exec("CREATE TABLE IF NOT EXISTS test (id INTEGER PRIMARY KEY, message TEXT)");
    $db->exec("INSERT INTO test (message) VALUES ('Test Render')");
    echo "<p>✅ SQLite fonctionne</p>";
} catch (Exception $e) {
    echo "<p>❌ Erreur SQLite: " . $e->getMessage() . "</p>";
}

echo "<hr>";
echo "<a href='/debug_render.php'>🔧 Debug complet</a>";
echo "<br>";
echo "<a href='/'>🏠 Accueil application</a>";

?>
