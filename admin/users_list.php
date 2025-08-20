<?php
require_once __DIR__.'/../includes/staff_check.php';
require_once __DIR__.'/../templates/header.php';
$users = $pdo->query("SELECT id, first_name, last_name, email, personal_code, role FROM users ORDER BY created_at DESC")->fetchAll();
?>
<h1 class="h4 mb-3">Kasutajad</h1>
<div class="table-responsive">
<table class="table table-striped">
  <thead><tr><th>Nimi</th><th>E-post</th><th>Isikukood</th><th>Roll</th></tr></thead>
  <tbody>
  <?php foreach($users as $u): ?>
  <tr>
    <td><?php echo e($u['first_name'].' '.$u['last_name']); ?></td>
    <td><?php echo e($u['email']); ?></td>
    <td><?php echo e($u['personal_code']); ?></td>
    <td><?php echo e($u['role']); ?></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
<?php require_once __DIR__.'/../templates/footer.php'; ?>
