<?php
try {
    $pdo = new PDO("mysql:host=127.0.0.1;port=3306;dbname=qlnv", "root", "", [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION]);
    $stmt = $pdo->query("SHOW TABLES");
    $tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
    echo "DB tables count: " . count($tables) . "\n";
    foreach ($tables as $t) echo "- $t\n";
} catch (PDOException $e) {
    echo "ERR: " . $e->getMessage() . "\n";
}
