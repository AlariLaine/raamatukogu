<?php
require_once __DIR__ . '/../includes/helpers.php'; require_login();
$id=(int)($_GET['id'] ?? 0); $uid=(int)$_SESSION['user_id']; if($id<=0) redirect('/public/my_loans.php');
$conn->query("UPDATE reservations SET status='expired' WHERE id=$id AND user_id=$uid AND status='active'");
redirect('/public/my_loans.php');
