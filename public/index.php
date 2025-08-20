<?php
require_once __DIR__.'/../templates/header.php';
require_once __DIR__.'/../includes/config.php';
require_once __DIR__.'/../includes/functions.php';

expire_reservations($pdo);

// Otsing
$q = trim($_GET['q'] ?? '');
$sql = "SELECT b.id, b.title, b.isbn, b.available_copies, GROUP_CONCAT(a.name SEPARATOR ', ') AS authors
        FROM books b
        LEFT JOIN book_authors ba ON ba.book_id = b.id
        LEFT JOIN authors a ON a.id = ba.author_id";
$params = [];
if($q !== ''){
    $sql .= " WHERE b.title LIKE ? OR a.name LIKE ? OR b.isbn LIKE ?";
    $like = "%$q%";
    $params = [$like,$like,$like];
}
$sql .= " GROUP BY b.id ORDER BY b.title ASC";
$stmt = $pdo->prepare($sql);
$stmt->execute($params);
$books = $stmt->fetchAll();
?>
<div class="card shadow-sm">
  <div class="card-body">
    <form class="row g-2 mb-3" method="get">
      <div class="col-md-10">
        <input class="form-control" type="search" name="q" placeholder="Otsi pealkirja, autori või ISBN-i järgi" value="<?php echo e($q); ?>">
      </div>
      <div class="col-md-2 d-grid">
        <button class="btn btn-primary">Otsi</button>
      </div>
    </form>
    <div class="table-responsive">
      <table class="table table-hover align-middle">
        <thead><tr><th>Pealkiri</th><th>Autor(id)</th><th>ISBN</th><th>Saadaval</th><th>Tegevus</th></tr></thead>
        <tbody>
        <?php foreach($books as $b): ?>
          <tr>
            <td><?php echo e($b['title']); ?></td>
            <td><?php echo e($b['authors']); ?></td>
            <td><?php echo e($b['isbn']); ?></td>
            <td><?php echo (int)$b['available_copies']; ?></td>
            <td>
              <?php if(isset($_SESSION['user_id'])): ?>
                <form class="d-inline" method="post" action="/raamatukogu/actions/reserve.php">
                  <input type="hidden" name="book_id" value="<?php echo (int)$b['id']; ?>">
                  <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
                  <button class="btn btn-sm btn-outline-secondary">Broneeri (2p)</button>
                </form>
                <form class="d-inline" method="post" action="/raamatukogu/actions/loan.php">
                  <input type="hidden" name="book_id" value="<?php echo (int)$b['id']; ?>">
                  <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
                  <button class="btn btn-sm btn-primary">Laenuta</button>
                </form>
              <?php else: ?>
                <a class="btn btn-sm btn-outline-primary" href="/raamatukogu/auth/login.php">Logi sisse</a>
              <?php endif; ?>
            </td>
          </tr>
        <?php endforeach; ?>
        </tbody>
      </table>
    </div>
  </div>
</div>
<?php require_once __DIR__.'/../templates/footer.php'; ?>
