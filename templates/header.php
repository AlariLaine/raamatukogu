<?php require_once __DIR__ . '/../includes/helpers.php'; ?>
<!DOCTYPE html><html lang="et"><head>
<meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1">
<title>Raamatukogu</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
<link href="<?= BASE_URL ?>/assets/css/custom.css" rel="stylesheet">
</head><body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="<?= BASE_URL ?>/public/">📚 Raamatukogu</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav"><span class="navbar-toggler-icon"></span></button>
    <div class="collapse navbar-collapse" id="mainNav">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/public/">Avaleht</a></li>
        <?php if (!empty($_SESSION['role']) && $_SESSION['role']==='staff'): ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/dashboard.php">Admin</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/books.php">Raamatud</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/admin/users.php">Kasutajad</a></li>
        <?php endif; ?>
        <?php if (!empty($_SESSION['role']) && $_SESSION['role']==='user'): ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/public/my_loans.php">Minu laenutused</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
        <?php if (empty($_SESSION['user_id'])): ?>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/login.php">Logi sisse</a></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/auth/register.php">Registreeru</a></li>
        <?php else: ?>
          <li class="nav-item"><span class="navbar-text me-2">Tere, <?= sanitize($_SESSION['firstname'] ?? '') ?>!</span></li>
          <li class="nav-item"><a class="nav-link" href="<?= BASE_URL ?>/public/logout.php">Logi välja</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<div class="container py-4">
