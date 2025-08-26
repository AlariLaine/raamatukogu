<?php
require_once __DIR__ . '/../includes/helpers.php';
require_staff();

if ($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['id'])) {
    $id = (int)$_POST['id'];

    if ($id !== (int)$_SESSION['user_id']) {
        $stmt = $conn->prepare("UPDATE users SET status='deleted' WHERE id=?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
    }
}

header("Location: ".BASE_URL."/admin/users.php");
exit;
