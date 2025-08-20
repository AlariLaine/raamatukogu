<?php
// includes/functions.php

function e($s){ return htmlspecialchars((string)$s, ENT_QUOTES, 'UTF-8'); }

function validate_email($email){
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Eesti isikukoodi kontroll (vorming + kontrollsumma)
function validate_personal_code($pc){
    if(!preg_match('/^[1-6]\d{10}$/', $pc)) return false;
    $digits = str_split($pc);
    $w1 = [1,2,3,4,5,6,7,8,9,1];
    $w2 = [3,4,5,6,7,8,9,1,2,3];

    $sum = 0;
    for($i=0;$i<10;$i++) $sum += intval($digits[$i]) * $w1[$i];
    $mod = $sum % 11;
    if($mod < 10) return $mod == intval($digits[10]);

    $sum = 0;
    for($i=0;$i<10;$i++) $sum += intval($digits[$i]) * $w2[$i];
    $mod = $sum % 11;
    if($mod < 10) return $mod == intval($digits[10]);
    return intval($digits[10]) === 0;
}

function has_unreturned_loans($pdo, $user_id){
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM loans WHERE user_id = ? AND returned_date IS NULL");
    $stmt->execute([$user_id]);
    return $stmt->fetchColumn() > 0;
}

function csrf_token(){
    if(empty($_SESSION['csrf'])) $_SESSION['csrf'] = bin2hex(random_bytes(16));
    return $_SESSION['csrf'];
}
function csrf_check($token){
    return isset($_SESSION['csrf']) && hash_equals($_SESSION['csrf'], $token ?? '');
}

// Aegunud broneeringute aegumine ja koopiate taastamine
function expire_reservations($pdo){
    $now = (new DateTime())->format('Y-m-d H:i:s');
    $stmt = $pdo->prepare("SELECT id, book_id FROM reservations WHERE status='active' AND expires_at < ?");
    $stmt->execute([$now]);
    $expired = $stmt->fetchAll();
    foreach($expired as $r){
        // kui reserveerisime koopiat, taastame selle (selles demos vähendame reserveerimisel koopiat)
        $pdo->prepare("UPDATE books SET available_copies = available_copies + 1 WHERE id = ?")->execute([$r['book_id']]);
        $pdo->prepare("UPDATE reservations SET status='expired' WHERE id = ?")->execute([$r['id']]);
    }
}

// Autentimise abi: taastab sessiooni remember-tokeni põhjal
function try_restore_session($pdo){
    if(!isset($_SESSION['user_id']) && !empty($_COOKIE['remember_token'])){
        $token = $_COOKIE['remember_token'];
        $hash = hash('sha256', $token);
        $stmt = $pdo->prepare("SELECT user_id, expires_at FROM auth_tokens WHERE token_hash = ?");
        $stmt->execute([$hash]);
        if($row = $stmt->fetch()){
            if(new DateTime($row['expires_at']) > new DateTime()){
                $_SESSION['user_id'] = $row['user_id'];
                // rolli laadimine
                $s = $pdo->prepare("SELECT role FROM users WHERE id = ?");
                $s->execute([$row['user_id']]);
                if($u = $s->fetch()){ $_SESSION['role'] = $u['role']; }
            } else {
                setcookie('remember_token', '', time()-3600, '/', '', false, true);
            }
        }
    }
}
?>