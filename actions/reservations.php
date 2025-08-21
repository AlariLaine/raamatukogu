<?php
session_start();
require_once("../includes/config.php");
require_once("../includes/auth_check.php");

// Ainult user saab siia
if ($_SESSION['role'] !== 'user') {
    header("Location: ../public/");
    exit();
}

$stmt = $pdo->prepare("
    SELECT r.id, b.title, r.reservation_date, r.expires_at
    FROM reservations r
    JOIN books b ON r.book_id = b.id
    WHERE r.user_id = ?
    ORDER BY r.reservation_date DESC
");
$stmt->execute([$_SESSION['user_id']]);
$reservations = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include("../templates/header.php"); ?>

<div class="container mt-4">
    <h3>Minu broneeringud</h3>
    <?php if (count($reservations) > 0): ?>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Raamat</th>
                    <th>Broneeritud</th>
                    <th>Aegub</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($reservations as $res): ?>
                <tr>
                    <td><?= htmlspecialchars($res['title']) ?></td>
                    <td><?= htmlspecialchars($res['reservation_date']) ?></td>
                    <td><?= htmlspecialchars($res['expires_at']) ?></td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Sul pole hetkel ühtegi aktiivset broneeringut.</p>
    <?php endif; ?>
</div>

<?php include("../templates/footer.php"); ?>
