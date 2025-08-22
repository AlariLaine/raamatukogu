<?php
require_once __DIR__ . '/../includes/helpers.php';
require_login();
expire_old_reservations($conn);
$uid = (int)$_SESSION['user_id'];
$loans = $conn->query("SELECT l.id, b.title, l.loan_date, l.due_date, l.return_date FROM loans l JOIN books b ON b.id=l.book_id WHERE l.user_id=$uid ORDER BY l.loan_date DESC");
$res = $conn->query("SELECT r.id, b.title, r.reserved_at, r.expires_at, r.status FROM reservations r JOIN books b ON b.id=r.book_id WHERE r.user_id=$uid ORDER BY r.reserved_at DESC");
include __DIR__ . '/../templates/header.php';
?>
<h3>Minu broneeringud</h3>
<table class="table table-striped"><thead><tr><th>Raamat</th><th>Broneeritud</th><th>Aegub</th><th>Staatus</th><th></th></tr></thead><tbody>
<?php while($r=$res->fetch_assoc()): ?>
<tr>
  <td><?= sanitize($r['title']) ?></td>
  <td><?= sanitize($r['reserved_at']) ?></td>
  <td><?= sanitize($r['expires_at']) ?></td>
  <td><?= sanitize($r['status']) ?></td>
  <td><?php if($r['status']==='active'): ?><a class="btn btn-sm btn-outline-danger" href="<?= BASE_URL ?>/actions/cancel_reserve.php?id=<?= (int)$r['id'] ?>">Tühista</a><?php endif; ?></td>
</tr>
<?php endwhile; ?></tbody></table>

<h3 class="mt-4">Minu laenutused</h3>
<table class="table table-striped"><thead><tr><th>Raamat</th><th>Laenutatud</th><th>Tähtaeg</th><th>Tagastatud</th><th></th></tr></thead><tbody>
<?php while($l=$loans->fetch_assoc()): ?>
<tr>
  <td><?= sanitize($l['title']) ?></td>
  <td><?= sanitize($l['loan_date']) ?></td>
  <td><?= sanitize($l['due_date']) ?></td>
  <td><?= sanitize($l['return_date'] ?? '') ?></td>
  <td><?php if(empty($l['return_date'])): ?><a class="btn btn-sm btn-outline-success" href="<?= BASE_URL ?>/actions/return.php?id=<?= (int)$l['id'] ?>">Tagasta</a><?php endif; ?></td>
</tr>
<?php endwhile; ?></tbody></table>
<?php include __DIR__ . '/../templates/footer.php'; ?>
