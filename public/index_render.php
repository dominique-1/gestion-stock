<?php

define('LARAVEL_START', microtime(true));

// Forcer les variables d'environnement AVANT de charger Laravel
$_ENV['DB_CONNECTION'] = 'sqlite';
$_ENV['DB_DATABASE'] = '/var/data/database.sqlite';
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=/var/data/database.sqlite');

// Charger Laravel normalement
require_once __DIR__ . '/../index.php';

?>
