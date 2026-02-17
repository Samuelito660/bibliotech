<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}
include 'includes/db.php';
include 'includes/functions.php';

$idL = isset($_GET['idL']) ? $_GET['idL'] : null;
$userId = $_SESSION['idU'];
$activeLoans = countActiveLoans($userId);
$canBorrow = $activeLoans < 2;

$book = null;
if ($idL) {
    $stmt = mysqli_prepare($conn, "SELECT * FROM libri WHERE idL = ?");
    mysqli_stmt_bind_param($stmt, "i", $idL);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    $book = mysqli_fetch_assoc($result);
    mysqli_stmt_close($stmt);
}

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['borrow']) && $book && $canBorrow && $book['copieDis'] > 0) {
    $stmt = mysqli_prepare($conn, "UPDATE libri SET copieDis = copieDis - 1 WHERE idL = ?");
    mysqli_stmt_bind_param($stmt, "i", $idL);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    
    $dataScad = calculateDueDate(date('Y-m-d H:i:s'));
    $stmt = mysqli_prepare($conn, "INSERT INTO prestiti (idU, idL, dataPres, dataScad, idU_bibliotecario) VALUES (?, ?, NOW(), ?, NULL)");
    mysqli_stmt_bind_param($stmt, "iss", $userId, $idL, $dataScad);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    $message = 'Prestito effettuato!';
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request']) && $book) {
    $stmt = mysqli_prepare($conn, "INSERT INTO richieste (idU, idL) VALUES (?, ?)");
    mysqli_stmt_bind_param($stmt, "ii", $userId, $idL);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    logNotification("Richiesta per libro ID $idL da utente $userId");
    $message = 'Richiesta inviata!';
}
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Dettagli Libro - BiblioTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <?php if ($book): ?>
        <h1><?php echo $book['titolo']; ?></h1>
        <p>Autore: <?php echo $book['autore']; ?></p>
        <p>ISBN: <?php echo $book['isbn']; ?></p>
        <p>Copie: <?php echo $book['copieDis']; ?>/<?php echo $book['copieTot']; ?></p>
        <?php if ($book['copieDis'] > 0 && $canBorrow): ?>
            <form method="POST">
                <button type="submit" name="borrow" class="btn btn-success">Prendi in Prestito</button>
            </form>
        <?php else: ?>
            <form method="POST">
                <button type="submit" name="request" class="btn btn-warning">Richiedi Notifica Disponibilità</button>
            </form>
        <?php endif; ?>
    <?php else: ?>
        <h1>Libro non trovato</h1>
        <p>Il libro richiesto non esiste o l'ID è sbagliato.</p>
    <?php endif; ?>
    <?php if ($message) echo "<div class='alert alert-info'>$message</div>"; ?>
    <a href="libri.php" class="btn btn-secondary">Torna al Catalogo</a>
</div>
</body>
</html>