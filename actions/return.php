<?php
require_once __DIR__ . '/../includes/helpers.php'; require_login();
$id=(int)($_GET['id'] ?? 0); $uid=(int)$_SESSION['user_id']; if($id<=0) redirect('/public/my_loans.php');
$row=$conn->query("SELECT book_id FROM loans WHERE id=$id AND user_id=$uid AND return_date IS NULL")->fetch_assoc();
if($row){ $book_id=(int)$row['book_id']; $conn->query("UPDATE loans SET return_date=CURDATE() WHERE id=$id"); $conn->query("UPDATE books SET available_copies=available_copies+1 WHERE id=$book_id"); }
redirect('/public/my_loans.php');
