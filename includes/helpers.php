<?php
require_once __DIR__ . '/config.php';
function redirect($path){ header('Location: ' . BASE_URL . $path); exit(); }
function is_logged_in(){ return isset($_SESSION['user_id']); }
function is_staff(){ return isset($_SESSION['role']) && $_SESSION['role']==='staff'; }
function require_login(){ if(!is_logged_in()) redirect('/auth/login.php'); }
function require_staff(){ if(!is_staff()) redirect('/public/'); }
function sanitize($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
function expire_old_reservations($conn){ $conn->query("UPDATE reservations SET status='expired' WHERE status='active' AND expires_at < NOW()"); }
?>