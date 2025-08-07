<?php
declare(strict_types=1);
$config = include __DIR__ . '/./config/config.php';

$pdo = new PDO(
  "mysql:host=" . $config['DB_HOST'] . ";dbname=" . $config['DB_NAME'],
  $config['DB_USER'],
  $config['DB_PASS']
);

// Simple way to run a SQL file
function runMigration(string $file, PDO $pdo) {
    $sql = file_get_contents($file);
    $pdo->exec($sql);
    echo "Ran: $file\n";
}

$migrations = [
    "migrations/001_create_test_table.sql"
];

foreach ($migrations as $migration) {
    runMigration($migration, $pdo);
}
