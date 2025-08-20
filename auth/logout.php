<?php
require_once __DIR__ . '/../includes/config.php';
// kustuta session
$_SESSION = [];
session_destroy();
// kustuta remember cookie
if(!empty($_COOKIE['remember_token'])){
    setcookie('remember_token', '', time()-3600, '/', '', false, true);
}
header('Location: /raamatukogu/public/');
exit;
