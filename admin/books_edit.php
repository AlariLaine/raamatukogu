<?php
require_once __DIR__.'/../includes/staff_check.php';
require_once __DIR__.'/../templates/header.php';

$id = intval($_GET['id'] ?? 0);
$stmt = $pdo->prepare("SELECT * FROM books WHERE id = ?");
$stmt->execute([$id]);
$book = $stmt->fetch();
if(!$book){ echo "<div class='alert alert-warning'>Raamatut ei leitud.</div>"; require __DIR__.'/../templates/footer.php'; exit; }
if(!$book){ echo "<div class='alert alert-warning'>Book not found.</div>"; require __DIR__.'/../templates/footer.php'; exit; }

$as = $pdo->prepare("SELECT a.name FROM authors a JOIN book_authors ba ON ba.author_id=a.id WHERE ba.book_id=?");
$as = $pdo->prepare("SELECT a.name FROM authors a JOIN book_authors ba ON ba.author_id=a.id WHERE ba.book_id=?");
$as->execute([$id]);
$authors = implode(', ', array_column($as->fetchAll(), 'name'));

if($_SERVER['REQUEST_METHOD']==='POST'){
    $title = trim($_POST['title'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $copies = max(1, intval($_POST['total_copies'] ?? 1));
    $authors_in = trim($_POST['authors'] ?? '');
    $csrf = $_POST['csrf'] ?? '';
    if(!csrf_check($csrf)) die("Vigane vorm.");
        if(!csrf_check($csrf)) die("Invalid form.");
    $avail = $book['available_copies'] + ($copies - $book['total_copies']);
    if($avail < 0) $avail = 0;
    $pdo->beginTransaction();
    try{
        $pdo->prepare("UPDATE books SET title=?, isbn=?, total_copies=?, available_copies=? WHERE id=?")
            ->execute([$title,$isbn,$copies,$avail,$id]);
        // uuenda autoreid: lihtsuse mõttes kustuta ja lisa uuesti
        // update authors: for simplicity, delete and add again
        $pdo->prepare("DELETE FROM book_authors WHERE book_id=?")->execute([$id]);
        foreach(array_filter(array_map('trim', explode(',', $authors_in))) as $aname){
            $s = $pdo->prepare("SELECT id FROM authors WHERE name = ?");
            $s->execute([$aname]);
            $aid = $s->fetchColumn();
            if(!$aid){
                $pdo->prepare("INSERT INTO authors (name) VALUES (?)")->execute([$aname]);
                $aid = $pdo->lastInsertId();
            }
            $pdo->prepare("INSERT IGNORE INTO book_authors (book_id, author_id) VALUES (?,?)")->execute([$id,$aid]);
        }
        $pdo->commit();
        header('Location: /raamatukogu/admin/books_list.php');
        exit;
    } catch(Exception $e){
        $pdo->rollBack();
        echo '<div class="alert alert-danger">Viga: '.e($e->getMessage()).'</div>';
    }
}
?>
<h1 class="h4 mb-3">Muuda raamatut</h1>
<form method="post">
  <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
  <div class="mb-2"><label class="form-label">Pealkiri</label><input class="form-control" name="title" value="<?php echo e($book['title']); ?>"></div>
  <div class="mb-2"><label class="form-label">Autorid (komadega)</label><input class="form-control" name="authors" value="<?php echo e($authors); ?>"></div>
  <div class="mb-2"><label class="form-label">ISBN</label><input class="form-control" name="isbn" value="<?php echo e($book['isbn']); ?>"></div>
  <div class="mb-3"><label class="form-label">Eksemplaride arv</label><input class="form-control" type="number" min="1" name="total_copies" value="<?php echo (int)$book['total_copies']; ?>"></div>
  <button class="btn btn-primary">Uuenda</button>
</form>
<?php require_once __DIR__.'/../templates/footer.php'; ?>
