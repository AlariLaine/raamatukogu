<?php
require_once __DIR__ . '/../includes/config.php';
$_SESSION = [];
session_destroy();

if(!empty($_COOKIE['remember_me'])){
    setcookie('remember_me', '', time()-3600, '/', '', false, true);
    if (isset($_SESSION['user_id'])) {
        $st = $conn->prepare("UPDATE users SET remember_token=NULL WHERE id=?");
        $st->bind_param("i", $_SESSION['user_id']);
        $st->execute();
    }
}
header('Location: /raamatukogu/public/');
exit;
