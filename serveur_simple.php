<?php

// Serveur PHP simple sans dépendances Laravel
// Contourne les problèmes de ports et extensions

echo "🚀 Démarrage du serveur simple...\n";
echo "📂 Répertoire: " . __DIR__ . "\n";

// Vérifier si public/index.php existe
$indexPath = __DIR__ . '/public/index.php';
if (!file_exists($indexPath)) {
    echo "❌ Erreur: public/index.php non trouvé!\n";
    exit(1);
}

// Démarrer le serveur PHP intégré sur un port différent
$port = 9000; // Port hors de la plage 8000-8010

echo "🌐 Serveur démarré sur: http://localhost:$port\n";
echo "⚠️  Appuyez sur Ctrl+C pour arrêter\n";
echo "📝 Accédez à votre application Gestion Stock\n\n";

// Démarrer le serveur
$command = "php -S localhost:$port -t public";
echo "Exécution: $command\n";
shell_exec($command);

?>
