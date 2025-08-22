<?php
require_once __DIR__ . '/../includes/helpers.php';
$err='';
if($_SERVER['REQUEST_METHOD']==='POST'){
  $firstname=trim($_POST['firstname'] ?? '');
  $lastname=trim($_POST['lastname'] ?? '');
  $personal_code=preg_replace('/\D+/', '', $_POST['personal_code'] ?? '');
  $email=trim($_POST['email'] ?? '');
  $pw=$_POST['password'] ?? ''; $pw2=$_POST['password2'] ?? '';
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ $err='E-posti vorming pole korrektne.'; }
  elseif(strlen($personal_code)!==11){ $err='Isikukood peab olema 11 numbrit.'; }
  elseif($pw!==$pw2){ $err='Paroolid ei ühti.'; }
  else{
    $hash=password_hash($pw, PASSWORD_DEFAULT);
    $st=$conn->prepare("INSERT INTO users (firstname, lastname, personal_code, email, password, role) VALUES (?,?,?,?,?,'user')");
    $st->bind_param("sssss",$firstname,$lastname,$personal_code,$email,$hash);
    if($st->execute()){ $_SESSION['user_id']=$st->insert_id; $_SESSION['firstname']=$firstname; $_SESSION['role']='user'; redirect('/public/'); }
    else $err='Registreerimine ebaõnnestus: '.$conn->error;
  }
}
include __DIR__ . '/../templates/header.php'; ?>
<div class="row justify-content-center"><div class="col-md-8">
<h3>Registreeru</h3>
<?php if($err): ?><div class="alert alert-danger"><?= sanitize($err) ?></div><?php endif; ?>
<form method="post" novalidate>
  <div class="row g-2">
    <div class="col-md-6"><label class="form-label">Eesnimi</label><input class="form-control" name="firstname" required></div>
    <div class="col-md-6"><label class="form-label">Perekonnanimi</label><input class="form-control" name="lastname" required></div>
    <div class="col-md-6"><label class="form-label">Isikukood</label><input class="form-control" name="personal_code" pattern="\d{11}" required></div>
    <div class="col-md-6"><label class="form-label">E-post</label><input type="email" class="form-control" name="email" required></div>
    <div class="col-md-6"><label class="form-label">Parool</label><input type="password" class="form-control" name="password" minlength="8" required></div>
    <div class="col-md-6"><label class="form-label">Kinnita parool</label><input type="password" class="form-control" name="password2" minlength="8" required></div>
  </div>
  <button class="btn btn-primary mt-3">Loo konto</button>
</form>
</div></div>
<?php include __DIR__ . '/../templates/footer.php'; ?>
