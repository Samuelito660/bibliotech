<?php
include 'db.php';

function calculateFine($dataScad) {
    if (empty($dataScad)) return 0;
    $oggi = new DateTime();
    $scadenza = new DateTime($dataScad);
    if ($oggi <= $scadenza) return 0;
    $diff = $oggi->diff($scadenza);
    $minuti = ($diff->days * 24 * 60) + ($diff->h * 60) + $diff->i;
    if ($minuti > 5) return 10;
    elseif ($minuti > 2) return 5;
    elseif ($minuti > 1) return 2;
    else return 0;
}

function countActiveLoans($userId) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT COUNT(*) FROM prestiti WHERE idU = ? AND dataRest IS NULL");
    mysqli_stmt_bind_param($stmt, "i", $userId);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $count);
    mysqli_stmt_fetch($stmt);
    mysqli_stmt_close($stmt);
    return $count;
}

function calculateDueDate($dataPres) {
    if (empty($dataPres)) return date('Y-m-d H:i:s', strtotime('+1 minute'));
    $inizio = new DateTime($dataPres);
    $inizio->modify('+1 minute');
    return $inizio->format('Y-m-d H:i:s');
}

function updateOldDueDates() {
    global $conn;
    $stmt = mysqli_prepare($conn, "UPDATE prestiti SET dataScad = NOW() + INTERVAL 1 MINUTE WHERE dataRest IS NULL");
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
    echo "Scadenze aggiornate per prestiti attivi.";
}

function notifyPendingRequests($bookId) {
    global $conn;
    $stmt = mysqli_prepare($conn, "SELECT idR FROM richieste WHERE idL = ? AND stato = 'pendente'");
    mysqli_stmt_bind_param($stmt, "i", $bookId);
    mysqli_stmt_execute($stmt);
    $result = mysqli_stmt_get_result($stmt);
    while ($row = mysqli_fetch_assoc($result)) {
        $updateStmt = mysqli_prepare($conn, "UPDATE richieste SET stato = 'notificato' WHERE idR = ?");
        mysqli_stmt_bind_param($updateStmt, "i", $row['idR']);
        mysqli_stmt_execute($updateStmt);
        mysqli_stmt_close($updateStmt);
        logNotification("Notifica inviata per richiesta ID {$row['idR']} su libro $bookId");
    }
    mysqli_stmt_close($stmt);
}

function logNotification($message) {
    $logFile = __DIR__ . '/../logs/notifications.log';
    if (!is_dir(dirname($logFile))) mkdir(dirname($logFile), 0755, true);
    file_put_contents($logFile, date('Y-m-d H:i:s') . " - $message\n", FILE_APPEND);
}
?>