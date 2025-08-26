<?php
require_once __DIR__ . '/../includes/helpers.php'; 
require_login();

$book_id = (int)($_GET['book_id'] ?? 0); 
if ($book_id <= 0) redirect('/public/');

$uid = (int)$_SESSION['user_id'];

// kontrolli kas kasutajal juba aktiivne broneering
$dup = $conn->query("SELECT id FROM reservations WHERE user_id=$uid AND book_id=$book_id AND status='active'");
if ($dup && $dup->num_rows > 0) {
  set_flash("Sul on see raamat juba broneeritud.", "warning");
  redirect('/public/my_loans.php');
}

// kontrolli kas raamat on üldse saadaval või juba kõik broneeritud
$bk = $conn->query("SELECT available_copies FROM books WHERE id=$book_id")->fetch_assoc();
if (!$bk || (int)$bk['available_copies'] <= 0) {
  set_flash("Raamatut pole võimalik hetkel broneerida.", "danger");
  redirect('/public/');
}

// kui kõik ok → tee broneering
$conn->query("INSERT INTO reservations (user_id, book_id, reserved_at, expires_at, status) 
              VALUES ($uid,$book_id,NOW(),DATE_ADD(NOW(), INTERVAL 2 DAY),'active')");
set_flash("Raamat broneeritud edukalt.", "success");
redirect('/public/my_loans.php');
