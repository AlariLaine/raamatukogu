<?php
// includes/staff_check.php
require_once __DIR__.'/auth_check.php';
if(($_SESSION['role'] ?? 'user') !== 'staff'){
    http_response_code(403);
    echo "Ligipääs keelatud (ainult töötajale).";
    exit;
}
?>