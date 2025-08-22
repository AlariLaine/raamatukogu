<?php
require_once __DIR__ . '/../includes/helpers.php'; require_staff(); expire_old_reservations($conn);
$loans=$conn->query("SELECT l.id,b.title,u.firstname,u.lastname,l.loan_date,l.due_date,l.return_date FROM loans l JOIN books b ON b.id=l.book_id JOIN users u ON u.id=l.user_id ORDER BY l.loan_date DESC LIMIT 50");
$res=$conn->query("SELECT r.id,b.title,u.firstname,u.lastname,r.reserved_at,r.expires_at,r.status FROM reservations r JOIN books b ON b.id=r.book_id JOIN users u ON u.id=r.user_id ORDER BY r.reserved_at DESC LIMIT 50");
include __DIR__ . '/../templates/header.php'; ?>
<h3>Admin – ülevaade</h3>
<div class="row">
  <div class="col-lg-6"><div class="card mb-3"><div class="card-header">Viimased broneeringud</div><div class="card-body p-0">
  <table class="table mb-0"><thead><tr><th>Raamat</th><th>Kasutaja</th><th>Broneeritud</th><th>Aegub</th><th>Staatus</th></tr></thead><tbody>
  <?php while($r=$res->fetch_assoc()): ?><tr><td><?= sanitize($r['title']) ?></td><td><?= sanitize($r['firstname'].' '.$r['lastname']) ?></td><td><?= sanitize($r['reserved_at']) ?></td><td><?= sanitize($r['expires_at']) ?></td><td><?= sanitize($r['status']) ?></td></tr><?php endwhile; ?>
  </tbody></table></div></div></div>
  <div class="col-lg-6"><div class="card mb-3"><div class="card-header">Viimased laenutused</div><div class="card-body p-0">
  <table class="table mb-0"><thead><tr><th>Raamat</th><th>Kasutaja</th><th>Laenutatud</th><th>Tähtaeg</th><th>Tagastatud</th></tr></thead><tbody>
  <?php while($l=$loans->fetch_assoc()): ?><tr><td><?= sanitize($l['title']) ?></td><td><?= sanitize($l['firstname'].' '.$l['lastname']) ?></td><td><?= sanitize($l['loan_date']) ?></td><td><?= sanitize($l['due_date']) ?></td><td><?= sanitize($l['return_date'] ?? '') ?></td></tr><?php endwhile; ?>
  </tbody></table></div></div></div>
</div>
<?php include __DIR__ . '/../templates/footer.php'; ?>
