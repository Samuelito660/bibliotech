<?php
session_start();
include 'includes/db.php';  

if (isset($_SESSION['idSess'])) {
   
    $stmt = mysqli_prepare($conn, "UPDATE sessioni SET dataFine = NOW(), stato = 'chiusa' WHERE idSess = ?");
    mysqli_stmt_bind_param($stmt, "i", $_SESSION['idSess']);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_close($stmt);
}

session_destroy();  
header('Location: index.php');
exit();
?>