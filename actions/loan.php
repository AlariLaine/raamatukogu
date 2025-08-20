<?php
require_once __DIR__ . '/../includes/auth_check.php';
require_once __DIR__ . '/../includes/functions.php';
if($_SERVER['REQUEST_METHOD']!=='POST') { http_response_code(405); exit; }
if(!csrf_check($_POST['csrf'] ?? '')) die("CSRF");

$user_id = $_SESSION['user_id'];
$book_id = intval($_POST['book_id'] ?? 0);
$start = new DateTime();
$due = (clone $start)->modify('+14 days');

if(has_unreturned_loans($pdo, $user_id)){
    die("Teil on tagastamata laenutusi - laenutus ei ole lubatud.");
}

$pdo->beginTransaction();
try{
    expire_reservations($pdo);
    // lukusta rida
    $s = $pdo->prepare("SELECT available_copies FROM books WHERE id=? FOR UPDATE");
    $s->execute([$book_id]);
    $b = $s->fetch();
    if(!$b || $b['available_copies'] < 1) throw new Exception("Raamat ei ole saadaval.");

    $pdo->prepare("INSERT INTO loans (user_id, book_id, start_date, due_date) VALUES (?,?,?,?)")
        ->execute([$user_id,$book_id,$start->format('Y-m-d'),$due->format('Y-m-d')]);
    $pdo->prepare("UPDATE books SET available_copies = available_copies - 1 WHERE id=?")->execute([$book_id]);

    // kui oli aktiivne broneering, märgi fulfilled
    $pdo->prepare("UPDATE reservations SET status='fulfilled' WHERE user_id=? AND book_id=? AND status='active'")->execute([$user_id,$book_id]);

    $pdo->commit();
    header('Location: /raamatukogu/public/?msg=loan_ok');
} catch(Exception $e){
    $pdo->rollBack();
    die("Laenutus ebaõnnestus: ".$e->getMessage());
}
