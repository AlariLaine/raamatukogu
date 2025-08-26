<?php
require_once __DIR__.'/../includes/staff_check.php';
require_once __DIR__.'/../templates/header.php';

if($_SERVER['REQUEST_METHOD']==='POST'){
    $title = trim($_POST['title'] ?? '');
    $isbn = trim($_POST['isbn'] ?? '');
    $authors = trim($_POST['authors'] ?? '');
    $copies = max(1, intval($_POST['total_copies'] ?? 1));
    $csrf = $_POST['csrf'] ?? '';
    $errors = [];

    if(!csrf_check($csrf)) $errors[] = "Invalid form.";
    if($title==='') $errors[] = "Title is required.";
    if($copies < 1) $errors[] = "Number of copies must be at least 1.";

    if(empty($errors)){
        $pdo->beginTransaction();
        try{
            $ins = $pdo->prepare("INSERT INTO books (title,isbn,total_copies,available_copies) VALUES (?,?,?,?)");
            $ins->execute([$title,$isbn,$copies,$copies]);
            $book_id = $pdo->lastInsertId();
            
            foreach(array_filter(array_map('trim', explode(',', $authors))) as $aname){
                
                $s = $pdo->prepare("SELECT id FROM authors WHERE name = ?");
                $s->execute([$aname]);
                $aid = $s->fetchColumn();
                if(!$aid){
                    $pdo->prepare("INSERT INTO authors (name) VALUES (?)")->execute([$aname]);
                    $aid = $pdo->lastInsertId();
                }
                $pdo->prepare("INSERT IGNORE INTO book_authors (book_id, author_id) VALUES (?,?)")->execute([$book_id,$aid]);
            }
            $pdo->commit();
            header('Location: /raamatukogu/admin/books_list.php');
            exit;
        } catch(Exception $e){
            $pdo->rollBack();
            echo '<div class="alert alert-danger">Error: '.e($e->getMessage()).'</div>';
        }
    } else {
        echo '<div class="alert alert-danger"><ul class="mb-0"><li>'.implode('</li><li>', array_map('e',$errors)).'</li></ul></div>';
    }
}
?>
<h1 class="h4 mb-3">Lisa raamat</h1>
<form method="post">
  <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
  <div class="mb-2"><label class="form-label">Pealkiri</label><input class="form-control" name="title" required></div>
  <div class="mb-2"><label class="form-label">Autorid (komadega)</label><input class="form-control" name="authors"></div>
  <div class="mb-2"><label class="form-label">ISBN</label><input class="form-control" name="isbn"></div>
  <div class="mb-3"><label class="form-label">Eksemplaride arv</label><input class="form-control" type="number" min="1" name="total_copies" value="1"></div>
  <button class="btn btn-primary">Salvesta</button>
</form>
<?php require_once __DIR__.'/../templates/footer.php'; ?>
