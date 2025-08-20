<?php
// includes/config.php
// DEMO: kohanda vastavalt oma MySQL seadetele
$db_host = 'localhost';
$db_name = 'raamatukogu';
$db_user = 'root';
$db_pass = '';

$dsn = "mysql:host=$db_host;dbname=$db_name;charset=utf8mb4";
$options = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
];

try {
    $pdo = new PDO($dsn, $db_user, $db_pass, $options);
} catch (PDOException $e) {
    die("DB connection failed: " . $e->getMessage());
}
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>