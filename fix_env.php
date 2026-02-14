<?php

// Corriger la configuration .env pour MySQL
$envFile = '.env';
$content = file_get_contents($envFile);

echo "Configuration actuelle:\n";
preg_match('/DB_CONNECTION=(.+)/', $content, $matches);
echo "DB_CONNECTION: " . ($matches[1] ?? 'Non trouvé') . "\n";

// Forcer MySQL
$content = preg_replace('/DB_CONNECTION=.+/', 'DB_CONNECTION=mysql', $content);
$content = preg_replace('/DB_DATABASE=.+/', 'DB_DATABASE=stock', $content);

// S'assurer que les autres lignes MySQL sont présentes
if (strpos($content, 'DB_HOST=') === false) {
    $content .= "\nDB_HOST=127.0.0.1";
}
if (strpos($content, 'DB_PORT=') === false) {
    $content .= "\nDB_PORT=3306";
}
if (strpos($content, 'DB_USERNAME=') === false) {
    $content .= "\nDB_USERNAME=root";
}
if (strpos($content, 'DB_PASSWORD=') === false) {
    $content .= "\nDB_PASSWORD=";
}

file_put_contents($envFile, $content);

echo "\nNouvelle configuration:\n";
echo "DB_CONNECTION=mysql\n";
echo "DB_DATABASE=stock\n";
echo "DB_HOST=127.0.0.1\n";
echo "DB_PORT=3306\n";
echo "DB_USERNAME=root\n";
echo "DB_PASSWORD=\n";

echo "\nConfiguration MySQL appliquée!\n";
?>
