<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/functions.php';
if($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); exit; }
if(!csrf_check($_POST['csrf'] ?? '')) die("CSRF");

$user_id = $_SESSION['user_id'];
$book_id = intval($_POST['book_id'] ?? 0);
if(!$book_id) die("Viga: puuduv raamat.");

// Ära luba mitu aktiivset reserve samale raamatule
$stmt = $pdo->prepare("SELECT id FROM reservations WHERE user_id=? AND book_id=? AND status='active'");
$stmt->execute([$user_id,$book_id]);
if($stmt->fetch()) die("Teil on juba aktiivne broneering sellele raamatule.");

$pdo->beginTransaction();
try{
    // kas koopiad on saadaval, siis hoiame 1 koopiat broneeringul
    $s = $pdo->prepare("SELECT available_copies FROM books WHERE id=? FOR UPDATE");
    $s->execute([$book_id]);
    $b = $s->fetch();
    if(!$b) throw new Exception("Raamatut ei leitud.");
    if($b['available_copies'] < 1) throw new Exception("Raamat pole saadaval.");

    $expires = (new DateTime('+2 days'))->format('Y-m-d H:i:s');
    $pdo->prepare("INSERT INTO reservations (user_id, book_id, expires_at) VALUES (?,?,?)")->execute([$user_id,$book_id,$expires]);
    $pdo->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE id=?")->execute([$book_id]);

    $pdo->commit();
    header('Location: /raamatukogu/public/?msg=reserve_ok');
} catch(Exception $e){
    $pdo->rollBack();
    die("Broneerimine ebaõnnestus: ".$e->getMessage());
}
