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
    SELECT l.id, b.title, l.loan_date, l.due_date, l.returned
    FROM loans l
    JOIN books b ON l.book_id = b.id
    WHERE l.user_id = ?
    ORDER BY l.loan_date DESC
");
$stmt->execute([$_SESSION['user_id']]);
$loans = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>
<?php include("../templates/header.php"); ?>

<div class="container mt-4">
    <h3>Minu laenutused</h3>
    <?php if (count($loans) > 0): ?>
        <table class="table table-striped mt-3">
            <thead>
                <tr>
                    <th>Raamat</th>
                    <th>Laenutatud</th>
                    <th>Tagastamise tähtaeg</th>
                    <th>Staatus</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?= htmlspecialchars($loan['title']) ?></td>
                    <td><?= htmlspecialchars($loan['loan_date']) ?></td>
                    <td><?= htmlspecialchars($loan['due_date']) ?></td>
                    <td>
                        <?php if ($loan['returned']): ?>
                            ✅ Tagastatud
                        <?php else: ?>
                            ⏳ Laenutatud
                        <?php endif; ?>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php else: ?>
        <p>Sul pole hetkel ühtegi laenutust.</p>
    <?php endif; ?>
</div>

<?php include("../templates/footer.php"); ?>
