<?php
require_once __DIR__ . '/../includes/helpers.php';
expire_old_reservations($conn);
$term = trim($_GET['search'] ?? '');
if ($term !== '') {
  $like = '%' . $conn->real_escape_string($term) . '%';
  $sql = "SELECT * FROM books WHERE title LIKE '$like' OR author LIKE '$like' OR isbn LIKE '$like' ORDER BY title";
} else {
  $sql = "SELECT * FROM books ORDER BY title";
}
$res = $conn->query($sql);
include __DIR__ . '/../templates/header.php';
?>
<h3>Raamatukataloog</h3>
<form method="get" class="row g-2 mb-3">
  <div class="col-sm-9"><input name="search" class="form-control" placeholder="Otsi pealkirja, autori või ISBN järgi" value="<?= sanitize($term) ?>"></div>
  <div class="col-sm-3 d-grid"><button class="btn btn-primary">Otsi</button></div>
</form>
<table class="table table-striped align-middle">
<thead><tr><th>Pealkiri</th><th>Autor</th><th>ISBN</th><th>Eksemplare</th><th>Tegevus</th></tr></thead>
<tbody>
<?php while($b=$res->fetch_assoc()): ?>
<tr>
  <td><?= sanitize($b['title']) ?></td>
  <td><?= sanitize($b['author']) ?></td>
  <td><?= sanitize($b['isbn']) ?></td>
  <td><?= (int)$b['available_copies'] ?></td>
  <td>
    <?php if (is_logged_in()): ?>
      <a class="btn btn-sm btn-outline-secondary" href="<?= BASE_URL ?>/actions/reserve.php?book_id=<?= (int)$b['id'] ?>">Broneeri (2p)</a>
      <a class="btn btn-sm btn-primary" href="<?= BASE_URL ?>/actions/loan.php?book_id=<?= (int)$b['id'] ?>">Laenuta (14p)</a>
    <?php else: ?>
      <a class="btn btn-sm btn-outline-primary" href="<?= BASE_URL ?>/auth/login.php">Logi sisse</a>
    <?php endif; ?>
  </td>
</tr>
<?php endwhile; ?>
</tbody></table>
<?php include __DIR__ . '/../templates/footer.php'; ?>
