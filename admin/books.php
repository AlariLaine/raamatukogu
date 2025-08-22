<?php
require_once __DIR__ . '/../includes/helpers.php'; require_staff();
if($_SERVER['REQUEST_METHOD']==='POST'){
  if(isset($_POST['create'])){
    $title=$conn->real_escape_string($_POST['title']); $author=$conn->real_escape_string($_POST['author']);
    $isbn=$conn->real_escape_string($_POST['isbn']); $copies=(int)$_POST['copies'];
    $conn->query("INSERT INTO books (title,author,isbn,available_copies) VALUES ('$title','$author','$isbn',$copies)");
  }
  if(isset($_POST['update'])){
    $id=(int)$_POST['id']; $title=$conn->real_escape_string($_POST['title']); $author=$conn->real_escape_string($_POST['author']);
    $isbn=$conn->real_escape_string($_POST['isbn']); $copies=(int)$_POST['copies'];
    $conn->query("UPDATE books SET title='$title',author='$author',isbn='$isbn',available_copies=$copies WHERE id=$id");
  }
  if(isset($_POST['delete'])){ $id=(int)$_POST['id']; $conn->query("DELETE FROM books WHERE id=$id"); }
  header("Location: ".BASE_URL."/admin/books.php"); exit();
}
$books=$conn->query("SELECT * FROM books ORDER BY title");
include __DIR__ . '/../templates/header.php'; ?>
<h3>Raamatute haldus</h3>
<div class="card mb-3"><div class="card-header">Lisa uus raamat</div><div class="card-body">
<form method="post" class="row g-2">
  <div class="col-md-4"><input required class="form-control" name="title" placeholder="Pealkiri"></div>
  <div class="col-md-3"><input required class="form-control" name="author" placeholder="Autor"></div>
  <div class="col-md-3"><input required class="form-control" name="isbn" placeholder="ISBN"></div>
  <div class="col-md-2"><input required type="number" min="0" class="form-control" name="copies" placeholder="Eksemplare"></div>
  <div class="col-12"><button class="btn btn-success" name="create">Lisa</button></div>
</form></div></div>
<table class="table table-striped"><thead><tr><th>Pealkiri</th><th>Autor</th><th>ISBN</th><th>Saadaval</th><th>Toiming</th></tr></thead><tbody>
<?php while($b=$books->fetch_assoc()): ?>
<tr>
  <form method="post" class="row g-2 align-items-center">
    <td class="col-md-3"><input class="form-control" name="title" value="<?= sanitize($b['title']) ?>"></td>
    <td class="col-md-3"><input class="form-control" name="author" value="<?= sanitize($b['author']) ?>"></td>
    <td class="col-md-3"><input class="form-control" name="isbn" value="<?= sanitize($b['isbn']) ?>"></td>
    <td class="col-md-1"><input type="number" min="0" class="form-control" name="copies" value="<?= (int)$b['available_copies'] ?>"></td>
    <td class="col-md-2 d-flex gap-2">
      <input type="hidden" name="id" value="<?= (int)$b['id'] ?>">
      <button class="btn btn-sm btn-primary" name="update">Uuenda</button>
      <button class="btn btn-sm btn-outline-danger" name="delete" onclick="return confirm('Kustuta raamat?')">Kustuta</button>
    </td>
  </form>
</tr>
<?php endwhile; ?></tbody></table>
<?php include __DIR__ . '/../templates/footer.php'; ?>
