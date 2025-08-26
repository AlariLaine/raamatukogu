<?php
require_once __DIR__ . '/../includes/helpers.php'; 
require_login();

$book_id = (int)($_GET['book_id'] ?? 0); 
$uid = (int)$_SESSION['user_id']; 
if ($book_id <= 0) redirect('/public/');

// kontrolli kas sama raamat on juba laenutatud
$dup = $conn->query("SELECT id FROM loans WHERE user_id=$uid AND book_id=$book_id AND return_date IS NULL");
if ($dup && $dup->num_rows > 0) {
  set_flash("Sul on see raamat juba laenutatud.", "warning");
  redirect('/public/my_loans.php');
}

// kontrolli, et kasutajal poleks muid lahtiseid laene (kui tahad piirangut 1 korraga)
$open = $conn->query("SELECT COUNT(*) c FROM loans WHERE user_id=$uid AND return_date IS NULL")->fetch_assoc();
if ((int)$open['c'] > 0) {
  set_flash("Sul on juba üks laen aktiivne. Tagasta see enne uue laenamist.", "warning");
  redirect('/public/my_loans.php');
}

// kontrolli kas raamat on saadaval
$bk = $conn->query("SELECT available_copies FROM books WHERE id=$book_id")->fetch_assoc();
if (!$bk || (int)$bk['available_copies'] <= 0) {
  set_flash("Raamat pole hetkel saadaval laenutuseks.", "danger");
  redirect('/public/');
}

// kui kõik ok → tee laenutus
$conn->query("INSERT INTO loans (user_id, book_id, loan_date, due_date) 
              VALUES ($uid,$book_id,CURDATE(),DATE_ADD(CURDATE(), INTERVAL 14 DAY))");
$conn->query("UPDATE books SET available_copies=available_copies-1 WHERE id=$book_id");
$conn->query("UPDATE reservations SET status='converted' 
              WHERE user_id=$uid AND book_id=$book_id AND status='active'");

set_flash("Raamat laenutatud edukalt.", "success");
redirect('/public/my_loans.php');
