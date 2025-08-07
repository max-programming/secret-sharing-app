<?php
declare(strict_types=1);
$config = include __DIR__ . '/./config/config.php';

$conn = new mysqli(
    $config['DB_HOST'],
    $config['DB_USER'],
    $config['DB_PASS'],
    $config['DB_NAME']
);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$result = $conn->query("SELECT * FROM test_table");

echo "<h1>Test Table Rows - Made by Usman and Shivang at CKPCET</h1>";
while ($row = $result->fetch_assoc()) {
    echo "<pre>" . print_r($row, true) . "</pre>";
}

$conn->close();

