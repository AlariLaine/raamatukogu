<?php
require_once __DIR__ . '/../templates/header.php';
require_once __DIR__ . '/../includes/config.php';
require_once __DIR__ . '/../includes/functions.php';

if($_SERVER['REQUEST_METHOD'] === 'POST'){
    $first = trim($_POST['first_name'] ?? '');
    $last = trim($_POST['last_name'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $pc = trim($_POST['personal_code'] ?? '');
    $password = $_POST['password'] ?? '';
    $csrf = $_POST['csrf'] ?? '';
    $errors = [];

    if(!csrf_check($csrf)) $errors[] = "Vigane vormi esitamine.";
    if(empty($first) || empty($last)) $errors[] = "Nimi on nõutud.";
    if(!validate_email($email)) $errors[] = "Vigane e-post.";
    if(!validate_personal_code($pc)) $errors[] = "Vigane isikukood.";
    if(strlen($password) < 8) $errors[] = "Parool peab olema vähemalt 8 tähemärki.";

    $stmt = $pdo->prepare("SELECT id FROM users WHERE email = ? OR personal_code = ?");
    $stmt->execute([$email, $pc]);
    if($stmt->fetch()) $errors[] = "Kasutaja selle e-posti või isikukoodiga juba eksisteerib.";

    if(empty($errors)){
        $hash = password_hash($password, PASSWORD_DEFAULT);
        $ins = $pdo->prepare("INSERT INTO users (first_name,last_name,personal_code,email,password_hash) VALUES (?,?,?,?,?)");
        $ins->execute([$first,$last,$pc,$email,$hash]);
        echo '<div class="alert alert-success">Registreerimine õnnestus. Võid sisse logida.</div>';
    } else {
        echo '<div class="alert alert-danger"><ul class="mb-0"><li>'.implode('</li><li>', array_map('e',$errors)).'</li></ul></div>';
    }
}
?>
<div class="row justify-content-center">
  <div class="col-md-6">
    <div class="card shadow-sm">
      <div class="card-body">
        <h1 class="h4 mb-3">Registreeru</h1>
        <form method="post" id="registerForm" novalidate>
          <input type="hidden" name="csrf" value="<?php echo csrf_token(); ?>">
          <div class="mb-2"><label class="form-label">Eesnimi</label><input class="form-control" name="first_name" required></div>
          <div class="mb-2"><label class="form-label">Perekonnanimi</label><input class="form-control" name="last_name" required></div>
          <div class="mb-2"><label class="form-label">Isikukood</label><input class="form-control" name="personal_code" required></div>
          <div class="mb-2"><label class="form-label">E-post</label><input class="form-control" type="email" name="email" required></div>
          <div class="mb-3"><label class="form-label">Parool</label><input class="form-control" type="password" name="password" required></div>
          <div class="d-grid"><button class="btn btn-primary">Loo konto</button></div>
        </form>
      </div>
    </div>
  </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
