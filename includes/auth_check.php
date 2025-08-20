<?php
// includes/auth_check.php
require_once __DIR__.'/config.php';
require_once __DIR__.'/functions.php';
try_restore_session($pdo);
if(!isset($_SESSION['user_id'])){
    header('Location: /raamatukogu/auth/login.php');
    exit;
}
?>