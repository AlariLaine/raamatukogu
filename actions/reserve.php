<?php
require_once __DIR__ . '/../includes/helpers.php'; require_login();
$book_id=(int)($_GET['book_id'] ?? 0); if($book_id<=0) redirect('/public/');
$uid=(int)$_SESSION['user_id'];
$dup=$conn->query("SELECT id FROM reservations WHERE user_id=$uid AND book_id=$book_id AND status='active'");
if($dup && $dup->num_rows>0){ redirect('/public/my_loans.php'); }
$conn->query("INSERT INTO reservations (user_id, book_id, reserved_at, expires_at, status) VALUES ($uid,$book_id,NOW(),DATE_ADD(NOW(), INTERVAL 2 DAY),'active')");
redirect('/public/my_loans.php');
