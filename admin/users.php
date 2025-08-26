<?php
require_once __DIR__ . '/../includes/helpers.php'; 
require_staff();

if($_SERVER['REQUEST_METHOD']==='POST' && isset($_POST['id'],$_POST['role'])){
  $id=(int)$_POST['id']; 
  $role=$_POST['role']==='staff'?'staff':'user';
  $conn->query("UPDATE users SET role='$role' WHERE id=$id"); 
  header("Location: ".BASE_URL."/admin/users.php"); 
  exit();
}

$users=$conn->query("SELECT id, firstname, lastname, email, role, status 
                     FROM users 
                     WHERE status='active'
                     ORDER BY lastname, firstname");


include __DIR__ . '/../templates/header.php'; ?>
<h3>Kasutajate haldus</h3>
<table class="table table-striped">
    <thead>
        <tr>
            <th>Nimi</th>
            <th>E-post</th>
            <th>Roll</th>
            <th>Muuda rolli / Kustuta</th>
        </tr>
    </thead>
    <tbody>
        <?php while($u=$users->fetch_assoc()): ?>
        <tr>
            <td><?= sanitize($u['firstname'].' '.$u['lastname']) ?></td>
            <td><?= sanitize($u['email']) ?></td>
            <td><span class="badge bg-secondary"><?= sanitize($u['role']) ?></span></td>
            <td>
                <form method="post" class="d-flex gap-2 mb-1">
                    <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                    <select name="role" class="form-select form-select-sm" style="width:auto">
                        <option value="user" <?= $u['role']==='user'?'selected':'' ?>>user</option>
                        <option value="staff" <?= $u['role']==='staff'?'selected':'' ?>>staff</option>
                    </select>
                    <button class="btn btn-sm btn-primary">Salvesta</button>
                </form>

                <?php if ($_SESSION['user_id'] != $u['id']): ?>
                <form method="post" action="delete_user.php"
                    onsubmit="return confirm('Kas oled kindel, et soovid kustutada kasutaja?');">
                    <input type="hidden" name="id" value="<?= (int)$u['id'] ?>">
                    <button class="btn btn-sm btn-danger">Kustuta</button>
                </form>
                <?php endif; ?>
            </td>
        </tr>
        <?php endwhile; ?>
    </tbody>
</table>
<?php include __DIR__ . '/../templates/footer.php'; ?>