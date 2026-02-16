<?php
session_start();

if (!isset($_SESSION['logged_in']) || $_SESSION['ruolo'] !== 'bibliotecario') {
    header('Location: login.php');
    exit();
}
include 'includes/db.php';
include 'includes/functions.php';

$message = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['return'])) {
    $loanId = $_POST['loan_id'];
    $userId = $_POST['user_id'];
    $bookId = $_POST['book_id'];
    
    $stmt = mysqli_prepare($conn, "SELECT dataScad FROM prestiti WHERE idPr = ?");
    mysqli_stmt_bind_param($stmt, "i", $loanId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $dataScad);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    
    $multa = calculateFine($dataScad);
    
    mysqli_begin_transaction($conn);
    try {
        $stmt = mysqli_prepare($conn, "UPDATE prestiti SET dataRest = CURDATE(), multa = ?, idU_bibliotecario = ? WHERE idPr = ?");
        mysqli_stmt_bind_param($stmt, "dii", $multa, $_SESSION['idU'], $loanId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        $stmt = mysqli_prepare($conn, "UPDATE libri SET copieDis = copieDis + 1 WHERE idL = ?");
        mysqli_stmt_bind_param($stmt, "i", $bookId);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_close($stmt);
        
        if ($multa > 0) {
            $stmt = mysqli_prepare($conn, "SELECT portafoglio FROM utenti WHERE idU = ? AND ruolo = 'studente'");
            mysqli_stmt_bind_param($stmt, "i", $userId);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_bind_result($stmt, $portafoglio);
            mysqli_stmt_fetch($stmt);
            mysqli_stmt_close($stmt);
            
            if ($portafoglio >= $multa) {
                $stmt = mysqli_prepare($conn, "UPDATE utenti SET portafoglio = portafoglio - ? WHERE idU = ?");
                mysqli_stmt_bind_param($stmt, "di", $multa, $userId);
                mysqli_stmt_execute($stmt);
                mysqli_stmt_close($stmt);
                $message = "Restituzione registrata. Multa di €" . $multa . " dedotta dal portafoglio.";
            } else {
                $message = "Restituzione registrata, ma portafoglio insufficiente per multa di €" . $multa . ". Contatta l'utente.";
            }
        } else {
            $message = "Restituzione registrata senza multa.";
        }
        
        notifyPendingRequests($bookId);
        mysqli_commit($conn);
    } catch (Exception $e) {
        mysqli_rollback($conn);
        $message = "Errore nella restituzione: " . $e->getMessage();
    }
}


$stmt = mysqli_prepare($conn, "SELECT p.idPr, p.idU, p.idL, p.dataPres, u.nome, u.cogn, l.titolo FROM prestiti p JOIN utenti u ON p.idU = u.idU JOIN libri l ON p.idL = l.idL WHERE p.dataRest IS NULL");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$loans = [];
while ($row = mysqli_fetch_assoc($result)) {
    $loans[] = $row;
}
mysqli_stmt_close($stmt);

$stmt = mysqli_prepare($conn, "SELECT r.idR, r.idU, r.idL, r.dataRichiesta, u.nome, u.cogn, l.titolo FROM richieste r JOIN utenti u ON r.idU = u.idU JOIN libri l ON r.idL = l.idL WHERE r.stato = 'pendente'");
mysqli_stmt_execute($stmt);
$result = mysqli_stmt_get_result($stmt);
$requests = [];
while ($row = mysqli_fetch_assoc($result)) {
    $requests[] = $row;
}
mysqli_stmt_close($stmt);
?>
<!DOCTYPE html>
<html lang="it">
<head>
    <meta charset="UTF-8">
    <title>Gestione Restituzioni - BiblioTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Gestione Restituzioni</h1>
    <p>Benvenuto, <?php echo $_SESSION['nome']; ?> | <a href="logout.php">Logout</a></p>
    <?php if (isset($message)) echo "<div class='alert alert-info'>$message</div>"; ?>
    
    <h2>Prestiti Attivi</h2>
    <table class="table">
        <thead>
            <tr><th>Utente</th><th>Libro</th><th>Data Inizio</th><th>Azione</th></tr>
        </thead>
        <tbody>
            <?php foreach ($loans as $loan): ?>
                <tr>
                    <td><?php echo $loan['nome'] . ' ' . $loan['cogn']; ?></td>
                    <td><?php echo $loan['titolo']; ?></td>
                    <td><?php echo $loan['dataPres']; ?></td>
                    <td>
                        <form method="POST" style="display:inline;">
                            <input type="hidden" name="loan_id" value="<?php echo $loan['idPr']; ?>">
                            <input type="hidden" name="user_id" value="<?php echo $loan['idU']; ?>">
                            <input type="hidden" name="book_id" value="<?php echo $loan['idL']; ?>">
                            <button type="submit" name="return" class="btn btn-danger">Restituisci</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
    
    <h2>Richieste Pendenti</h2>
    <table class="table">
        <thead>
            <tr><th>Utente</th><th>Libro</th><th>Data Richiesta</th><th>Azione</th></tr>
        </thead>
        <tbody>
            <?php foreach ($requests as $req): ?>
                <tr>
                    <td><?php echo $req['nome'] . ' ' . $req['cogn']; ?></td>
                    <td><?php echo $req['titolo']; ?></td>
                    <td><?php echo $req['dataRichiesta']; ?></td>
                    <td>
                        <button class="btn btn-info" onclick="alert('Notifica inviata a <?php echo $req['nome']; ?>')">Notifica</button>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
</body>
</html>