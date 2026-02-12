<?php
$path = __DIR__ . "/../database/database.sqlite";
if (!file_exists($path)) { echo "sqlite not found\n"; exit; }
$pdo = new PDO("sqlite:$path");
$stmt = $pdo->query("SELECT name FROM sqlite_master WHERE type='table' AND name NOT LIKE 'sqlite_%';");
$tables = $stmt->fetchAll(PDO::FETCH_COLUMN);
echo "SQLite tables count: " . count($tables) . "\n";
foreach ($tables as $t) echo "- $t\n";
