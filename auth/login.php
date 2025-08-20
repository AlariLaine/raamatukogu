<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $email = trim($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $remember = isset($_POST['remember']);
    $csrf = $_POST['csrf'] ?? '';

    if(!csrf_check($csrf)) die("Invalid CSRF");

    $stmt = $pdo->prepare("SELECT id, password_hash, role FROM users WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();
    if($user && password_verify($password, $user['password_hash'])){
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['last_active'] = time();

        if($remember){
            $token = bin2hex(random_bytes(32));
            $token_hash = hash('sha256', $token);
            $expires = (new DateTime('+4 hours'))->format('Y-m-d H:i:s');
            $ua = $_SERVER['HTTP_USER_AGENT'] ?? '';
            $ins = $pdo->prepare("INSERT INTO auth_tokens (user_id, token_hash, expires_at, user_agent) VALUES (?,?,?,?)");
            $ins->execute([$user['id'], $token_hash, $expires, $ua]);
            setcookie('remember_token', $token, time()+14400, "/","",false,true);
        }

        header("Location: /raamatukogu/public/");
        exit;
    } else {
        echo '<div class="alert alert-danger">Vigane sisselogimine.</div>';
    }
}
?>
<div class="row justify-content-center">
  <div class="col-md-5">
    <div class="card shadow-sm">
      <div class="card-body">
        <h1 class="h4 mb-3">Logi sisse</h1>
        <form method="post">
          <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
          <div class="mb-2"><label class="form-label">E-post</label><input class="form-control" type="email" name="email" required></div>
          <div class="mb-2"><label class="form-label">Parool</label><input class="form-control" type="password" name="password" required></div>
          <div class="form-check mb-3">
            <input class="form-check-input" type="checkbox" name="remember" id="remember">
            <label class="form-check-label" for="remember">Mäleta mind (4h)</label>
          </div>
          <div class="d-grid"><button class="btn btn-primary">Logi sisse</button></div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
