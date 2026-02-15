<?php
require_once 'db.php';

if (isset($_POST['registra_restituzione'])) {
    $idPr = $_POST['idPr'];
    $idL = $_POST['idL'];
    $idU = $_POST['idU'];
    
    
    $res = $conn->query("SELECT DATEDIFF(NOW(), dataScad) as ritardo FROM PRESTITO WHERE idPr = $idPr");
    $ritardo = $res->fetch_array()['ritardo'];
    $valoreMulta = ($ritardo > 0) ? 10.00 : 0.00;

    
    $conn->query("UPDATE PRESTITO SET dataRest = NOW(), multa = $valoreMulta WHERE idPr = $idPr");
    $conn->query("UPDATE LIBRO SET copieDis = copieDis + 1 WHERE idL = $idL");
    $conn->query("UPDATE UTENTE SET portafoglio = portafoglio - $valoreMulta WHERE idU = $idU");
}
?>