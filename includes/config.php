<?php
if (session_status() === PHP_SESSION_NONE) {
    session_set_cookie_params([
        'lifetime' => 14400,
        'path' => '/',
        'httponly' => true,
        'samesite' => 'Lax'
    ]);
    session_start();
}
define('BASE_URL', '/raamatukogu');
$DB_HOST="localhost"; $DB_USER="root"; $DB_PASS=""; $DB_NAME="raamatukogu";
$conn = new mysqli($DB_HOST, $DB_USER, $DB_PASS, $DB_NAME);
if ($conn->connect_error) { die("Andmebaasi ühendus ebaõnnestus: " . $conn->connect_error); }
$conn->set_charset("utf8mb4");
?>