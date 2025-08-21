<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Raamatukogu</title>
    <link href="/raamatukogu/public/css/custom.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">
        <a class="navbar-brand" href="/raamatukogu/public/">📚 Raamatukogu</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="mainNav">
            <ul class="navbar-nav me-auto">
                <li class="nav-item">
                    <a class="nav-link" href="/raamatukogu/public/">Avaleht</a>
                </li>

                <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'staff'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/raamatukogu/admin/books_list.php">Admin</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/raamatukogu/admin/loans_history.php">Laenutuste ajalugu</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/raamatukogu/admin/users_list.php">Kasutajad</a>
                    </li>
                <?php endif; ?>

                <?php if (!empty($_SESSION['role']) && $_SESSION['role'] === 'user'): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/raamatukogu/user/loans.php">Minu laenutused</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/raamatukogu/user/reservations.php">Minu broneeringud</a>
                    </li>
                <?php endif; ?>
            </ul>

            <ul class="navbar-nav">
                <?php if (empty($_SESSION['user_id'])): ?>
                    <li class="nav-item">
                        <a class="nav-link" href="/raamatukogu/auth/login.php">Logi sisse</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/raamatukogu/auth/register.php">Registreeru</a>
                    </li>
                <?php else: ?>
                    <li class="nav-item">
                        <span class="navbar-text me-2">Tere, <?= htmlspecialchars($_SESSION['username'] ?? '') ?>!</span>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="/raamatukogu/auth/logout.php">Logi välja</a>
                    </li>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</nav>
