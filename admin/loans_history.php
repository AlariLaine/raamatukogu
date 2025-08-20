<?php
require_once __DIR__.'/../includes/staff_check.php';
require_once __DIR__.'/../templates/header.php';
$loans = $pdo->query("SELECT l.*, b.title, u.first_name, u.last_name
FROM loans l 
JOIN books b ON b.id=l.book_id 
JOIN users u ON u.id=l.user_id 
ORDER BY l.start_date DESC")->fetchAll();
?>
<h1 class="h4 mb-3">Laenutuste ajalugu</h1>
<div class="table-responsive">
<table class="table table-bordered">
  <thead><tr><th>Kasutaja</th><th>Raamat</th><th>Algus</th><th>Tähtaeg</th><th>Tagastatud</th><th>Olek</th></tr></thead>
  <tbody>
  <?php foreach($loans as $l): ?>
  <tr>
    <td><?php echo e($l['first_name'].' '.$l['last_name']); ?></td>
    <td><?php echo e($l['title']); ?></td>
    <td><?php echo e($l['start_date']); ?></td>
    <td><?php echo e($l['due_date']); ?></td>
    <td><?php echo e($l['returned_date'] ?? '-'); ?></td>
    <td><?php echo e($l['status']); ?></td>
  </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
<?php require_once __DIR__.'/../templates/footer.php'; ?>
