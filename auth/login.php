<?php
require_once __DIR__ . '/../includes/helpers.php';
$err='';

if (!isset($_SESSION['user_id']) && isset($_COOKIE['remember_me'])) {
  [$uid, $token] = explode(':', $_COOKIE['remember_me'], 2);
  $st = $conn->prepare("SELECT id, firstname, role, remember_token FROM users WHERE id=?");
  $st->bind_param("i", $uid);
  $st->execute();
  $r = $st->get_result();
  if ($u = $r->fetch_assoc()) {
    if (hash_equals($u['remember_token'], hash('sha256', $token))) {
      $_SESSION['user_id'] = $u['id'];
      $_SESSION['firstname'] = $u['firstname'];
      $_SESSION['role'] = $u['role'];
      redirect('/public/');
    }
  }
}

if($_SERVER['REQUEST_METHOD']==='POST'){
  $email=trim($_POST['email'] ?? '');
  $pw=$_POST['password'] ?? '';
  $remember = isset($_POST['remember']);
  if(!filter_var($email, FILTER_VALIDATE_EMAIL)){ $err='Invalid email format.'; }
  else{
    $st = $conn->prepare("SELECT id, firstname, password, role FROM users WHERE email=? AND status='active'");
    $st->bind_param("s", $email);
    $st->execute();
    $r = $st->get_result();
    if($u = $r->fetch_assoc()){
      if(password_verify($pw, $u['password'])){
        $_SESSION['user_id']=$u['id']; $_SESSION['firstname']=$u['firstname']; $_SESSION['role']=$u['role'];

        if ($remember) {
          $token = bin2hex(random_bytes(32));
          $hash = hash('sha256', $token);
          $st2 = $conn->prepare("UPDATE users SET remember_token=? WHERE id=?");
          $st2->bind_param("si", $hash, $u['id']);
          $st2->execute();
          setcookie("remember_me", $u['id'].":".$token, time()+60*60*4, "/", "", false, true);
        }

        redirect('/public/');
      } else $err='Wrong password.';
    } else $err='User not found.';
  }
}
include __DIR__ . '/../templates/header.php'; ?>
<div class="row justify-content-center"><div class="col-md-6">
<h3>Login</h3>
<?php if($err): ?><div class="alert alert-danger"><?= sanitize($err) ?></div><?php endif; ?>
<form method="post" novalidate>
  <div class="mb-3"><label class="form-label">Email</label><input type="email" class="form-control" name="email" required></div>
  <div class="mb-3"><label class="form-label">Password</label><input type="password" class="form-control" name="password" required></div>
  <div class="mb-3 form-check">
    <input type="checkbox" class="form-check-input" name="remember" id="remember">
    <label class="form-check-label" for="remember">Remember me (4 days)</label>
  </div>
  <button class="btn btn-primary">Login</button>
</form>
</div></div>
<?php include __DIR__ . '/../templates/footer.php'; ?>
