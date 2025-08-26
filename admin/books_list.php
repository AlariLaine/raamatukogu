<?php
require_once __DIR__.'/../includes/staff_check.php';
require_once __DIR__.'/../templates/header.php';
expire_reservations($pdo);

$stmt = $pdo->query("SELECT id, title, isbn, total_copies, available_copies FROM books ORDER BY title");
$books = $stmt->fetchAll();
?>
<div class="d-flex justify-content-between align-items-center mb-3">
  <h1 class="h4">Books</h1>
  <a class="btn btn-success" href="books_add.php">+ Add book</a>
</div>
<div class="table-responsive">
<table class="table table-striped">
  <thead><tr><th>Title</th><th>ISBN</th><th>Total</th><th>Available</th><th></th></tr></thead>
  <tbody>
  <?php foreach($books as $b): ?>
    <tr>
      <td><?php echo e($b['title']); ?></td>
      <td><?php echo e($b['isbn']); ?></td>
      <td><?php echo (int)$b['total_copies']; ?></td>
      <td><?php echo (int)$b['available_copies']; ?></td>
      <td>
        <a class="btn btn-sm btn-outline-primary" href="books_edit.php?id=<?php echo (int)$b['id']; ?>">Edit</a>
      </td>
    </tr>
  <?php endforeach; ?>
  </tbody>
</table>
</div>
<?php require_once __DIR__.'/../templates/footer.php'; ?>
