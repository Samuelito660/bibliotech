<?php
session_start();
if (!isset($_SESSION['logged_in'])) {
    header('Location: login.php');
    exit();
}
include 'includes/db.php';
include 'includes/functions.php';

$userId = $_SESSION['idU'];
$activeLoans = countActiveLoans($userId);
$canBorrow = $activeLoans < 2;

$stmt = mysqli_prepare($conn, "SELECT * FROM libri ORDER BY titolo");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$books = [];
while ($row = mysqli_fetch_assoc($result)) {
    $books[] = $row;
}
mysqli_stmt_close($stmt);

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['borrow'])) {
    $idL = $_POST['idL'];
    $filtered = array_values(array_filter($books, function($b) use ($idL) { return $b['idL'] == $idL; }));
    $book = isset($filtered[0]) ? $filtered[0] : null;
    if ($book && $book['copieDis'] > 0 && $canBorrow) {
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
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['request'])) {
    $idL = $_POST['idL'];
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
    <title>Catalogo Libri - BiblioTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h2>Catalogo Libri</h2>
    <?php if ($message) echo "<div class='alert alert-info'>$message</div>"; ?>
    <p>Prestiti attivi: <?php echo $activeLoans; ?>/2</p>
    <table class="table">
        <thead>
            <tr><th>Titolo</th><th>Autore</th><th>Copie Disponibili</th><th>Azione</th></tr>
        </thead>
        <tbody>
            <?php foreach ($books as $book): ?>
                <tr>
                    <td><a href="libro.php?idL=<?= $book['idL'] ?>"><?= $book['titolo'] ?></a></td>
                    <td><?= $book['autore'] ?></td>
                    <td><?= $book['copieDis'] ?>/<?= $book['copieTot'] ?></td>
                    <td>
                        <?php if ($book['copieDis'] > 0 && $canBorrow): ?>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="idL" value="<?= $book['idL'] ?>">
                                <button type="submit" name="borrow" class="btn btn-success">Prendi in Prestito</button>
                            </form>
                        <?php else: ?>
                            <form method="post" style="display:inline;">
                                <input type="hidden" name="idL" value="<?= $book['idL'] ?>">
                                <button type="submit" name="request" class="btn btn-warning">Richiedi</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    <a href="prestiti.php" class="btn btn-secondary">I Miei Prestiti</a> | <a href="index.php" class="btn btn-secondary">Home Page</a>
</div>
</body>
</html>