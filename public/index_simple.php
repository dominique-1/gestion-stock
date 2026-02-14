<?php

// Version simplifiée qui contourne les problèmes Laravel
echo "<!DOCTYPE html>";
echo "<html><head><title>Gestion Stock</title>";
echo "<style>";
echo "body { font-family: Arial; margin: 40px; background: #f5f5f5; }";
echo ".container { background: white; padding: 30px; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }";
echo ".success { color: #28a745; }";
echo ".info { color: #17a2b8; }";
echo "</style></head><body>";

echo "<div class='container'>";
echo "<h1>🏢 Gestion Stock</h1>";
echo "<h2 class='success'>✅ Serveur fonctionne!</h2>";

echo "<div class='info'>";
echo "<h3>📊 État de l'application:</h3>";
echo "<ul>";

// Vérifier la connexion à la base de données
try {
    $pdo = new PDO('mysql:host=127.0.0.1;dbname=stock', 'root', '');
    echo "<li>✅ Base de données MySQL connectée</li>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM products");
    $result = $stmt->fetch();
    echo "<li>📦 Produits en base: " . $result['count'] . "</li>";
    
    $stmt = $pdo->query("SELECT COUNT(*) as count FROM categories");
    $result = $stmt->fetch();
    echo "<li>📂 Catégories en base: " . $result['count'] . "</li>";
    
} catch (Exception $e) {
    echo "<li>❌ Erreur base de données: " . $e->getMessage() . "</li>";
}

echo "</ul>";
echo "</div>";

echo "<h3>🚀 Actions disponibles:</h3>";
echo "<p><a href='index.php' style='background: #007bff; color: white; padding: 10px 20px; text-decoration: none; border-radius: 5px;'>Accéder à l'application Laravel</a></p>";

echo "<p><small>Serveur simple sur port 9000 - Évite les conflits de ports</small></p>";
echo "</div>";

echo "</body></html>";
?>
