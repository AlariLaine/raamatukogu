<?php
require_once __DIR__ . '/../includes/helpers.php';
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=trim($_POST['email'] ?? '');
  $pw=$_POST['password'] ?? '';
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ $err='E-posti vorming pole korrektne.'; }
  else{
    $st=$conn->prepare("SELECT id, firstname, password, role FROM users WHERE email=?");
    $st->bind_param("s",$email); $st->execute(); $r=$st->get_result();
    if($u=$r->fetch_assoc()){
      if(password_verify($pw, $u['password'])){
        $_SESSION['user_id']=$u['id']; $_SESSION['firstname']=$u['firstname']; $_SESSION['role']=$u['role'];
        redirect('/public/');
      } else $err='Vale parool.';
    } else $err='Kasutajat ei leitud.';
  }
}
include __DIR__ . '/../templates/header.php'; ?>
<div class="row justify-content-center"><div class="col-md-6">
<h3>Logi sisse</h3>
<?php if($err): ?><div class="alert alert-danger"><?= sanitize($err) ?></div><?php endif; ?>
<form method="post" novalidate>
  <div class="mb-3"><label class="form-label">E-post</label><input type="email" class="form-control" name="email" required></div>
  <div class="mb-3"><label class="form-label">Parool</label><input type="password" class="form-control" name="password" required></div>
  <button class="btn btn-primary">Logi sisse</button>
</form>
</div></div>
<?php include __DIR__ . '/../templates/footer.php'; ?>
