<?php
ob_start(); 
session_start();

if (isset($_SESSION['logged_in'])) {
    if ($_SESSION['ruolo'] === 'bibliotecario') {
        header('Location: gestione_restituzioni.php');
    } else {
        header('Location: libri.php');
    }
} else {
    header('Location: login.php');
}
exit();
ob_end_flush(); 
?>