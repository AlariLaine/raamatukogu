<?php
require_once __DIR__ . '/../includes/auth_check.php';
if($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); exit; }
if(!csrf_check($_POST['csrf'] ?? '')) die("CSRF");
$loan_id = intval($_POST['loan_id'] ?? 0);
$stmt = $pdo->prepare("SELECT id, book_id FROM loans WHERE id=? AND user_id=? AND returned_date IS NULL");
$stmt->execute([$loan_id, $_SESSION['user_id']]);
if(!$loan = $stmt->fetch()) die("Laenutust ei leitud.");
$pdo->beginTransaction();
try{
    $pdo->prepare("UPDATE loans SET returned_date = ?, status='returned' WHERE id=?")
        ->execute([(new DateTime())->format('Y-m-d'), $loan_id]);
    $pdo->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE id=?")->execute([$loan['book_id']]);
    $pdo->commit();
    header('Location: /raamatukogu/public/?msg=return_ok');
} catch(Exception $e){
    $pdo->rollBack();
    die("Viga: ".$e->getMessage());
}
