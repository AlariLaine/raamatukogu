<?php
// templates/header.php
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/../includes/functions.php';
try_restore_session($pdo);
?><!doctype html>
<html lang="et">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>Raamatukogu</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
  <link href="/raamatukogu/public/css/custom.css" rel="stylesheet">
</head>
<body class="bg-light">
<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="/raamatukogu/public/">📚 Raamatukogu</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#nav">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div id="nav" class="collapse navbar-collapse">
      <ul class="navbar-nav me-auto">
        <li class="nav-item"><a class="nav-link" href="/raamatukogu/public/">Avaleht</a></li>
        <?php if(($_SESSION['role'] ?? '') === 'staff'): ?>
        <li class="nav-item"><a class="nav-link" href="/raamatukogu/admin/books_list.php">Admin</a></li>
        <?php endif; ?>
      </ul>
      <ul class="navbar-nav">
      <?php if(!isset($_SESSION['user_id'])): ?>
        <li class="nav-item"><a class="nav-link" href="/raamatukogu/auth/login.php">Logi sisse</a></li>
        <li class="nav-item"><a class="nav-link" href="/raamatukogu/auth/register.php">Registreeru</a></li>
      <?php else: ?>
        <li class="nav-item"><span class="navbar-text me-2">Tere!</span></li>
        <li class="nav-item"><a class="nav-link" href="/raamatukogu/auth/logout.php">Logi välja</a></li>
      <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>
<main class="container my-4">
