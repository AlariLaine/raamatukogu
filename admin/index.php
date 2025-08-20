<?php
require_once __DIR__.'/../includes/staff_check.php';
require_once __DIR__.'/../templates/header.php';
?>
<div class="row g-3">
  <div class="col-md-4">
    <a class="text-decoration-none" href="books_list.php">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h2 class="h5">Raamatud</h2>
          <p>Lisa, muuda ja kustuta raamatuid</p>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-4">
    <a class="text-decoration-none" href="users_list.php">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h2 class="h5">Kasutajad</h2>
          <p>Vaata ja halda kasutajaid</p>
        </div>
      </div>
    </a>
  </div>
  <div class="col-md-4">
    <a class="text-decoration-none" href="loans_history.php">
      <div class="card shadow-sm h-100">
        <div class="card-body">
          <h2 class="h5">Laenutuste ajalugu</h2>
          <p>Vaata kõiki laenutusi</p>
        </div>
      </div>
    </a>
  </div>
</div>
<?php require_once __DIR__.'/../templates/footer.php'; ?>
