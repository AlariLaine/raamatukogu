<?php
require_once __DIR__ . '/../includes/auth_check.php';
if($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); exit; }
if(!csrf_check($_POST['csrf'] ?? '')) die("CSRF");
$rid = intval($_POST['reservation_id'] ?? 0);
$stmt = $pdo->prepare("SELECT id, book_id FROM reservations WHERE id=? AND user_id=? AND status='active'");
$stmt->execute([$rid, $_SESSION['user_id']]);
if(!$r = $stmt->fetch()) die("Broneeringut ei leitud.");
$pdo->beginTransaction();
try{
    $pdo->prepare("UPDATE reservations SET status='cancelled' WHERE id=?")->execute([$rid]);
    $pdo->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE id=?")->execute([$r['book_id']]);
    $pdo->commit();
    header('Location: /raamatukogu/public/?msg=reserve_cancelled');
} catch(Exception $e){
    $pdo->rollBack();
    die("Viga: ".$e->getMessage());
}
