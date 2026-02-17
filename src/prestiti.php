<?php
session_start();
if (!isset($_SESSION['logged_in']) || $_SESSION['ruolo'] !== 'studente') {
    header('Location: login.php');
    exit();
}
include 'includes/db.php';

$userId = $_SESSION['idU'];
$stmt = mysqli_prepare($conn, "SELECT p.*, l.titolo FROM prestiti p JOIN libri l ON p.idL = l.idL WHERE p.idU = ?");
mysqli_stmt_bind_param($stmt, "i", $userId);
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$loans = [];
while ($row = mysqli_fetch_assoc($result)) {
    $loans[] = $row;
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>I Miei Prestiti - BiblioTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>I Miei Prestiti</h1>
    <table class="table">
        <thead>
            <tr><th>Titolo</th><th>Data Inizio</th><th>Data Fine</th><th>Multa</th></tr>
        </thead>
        <tbody>
            <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?php echo $loan['titolo']; ?></td>
                    <td><?php echo $loan['dataPres']; ?></td>
                    <td><?php echo $loan['dataRest'] ?: 'Attivo'; ?></td>
                    <td><?php echo $loan['multa']; ?>€</td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="libri.php" class="btn btn-secondary">Torna al Catalogo</a>
</div>
</body>
</html>