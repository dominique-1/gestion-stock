<?php

$host = '127.0.0.1';
$port = 3306;
$user = 'root';
$password = '';
$database = 'stock';

try {
    $pdo = new PDO("mysql:host=$host;port=$port;dbname=$database", $user, $password);
    echo "✅ MySQL connection successful!\n";
    
    // Test tables
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "Tables: " . implode(', ', $tables) . "\n";
    
} catch (PDOException $e) {
    echo "❌ MySQL connection failed: " . $e->getMessage() . "\n";
}

try {
    $pdo = new PDO("mysql:host=$host;port=$port", $user, $password);
    echo "✅ MySQL server connection successful!\n";
    
    // Create database if not exists
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $database");
    echo "✅ Database '$database' created/verified\n";
    
} catch (PDOException $e) {
    echo "❌ MySQL server connection failed: " . $e->getMessage() . "\n";
}
