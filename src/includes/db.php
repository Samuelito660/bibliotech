<?php
$host = 'db';
$dbname = 'bibliotech';
$user = 'user';
$pass = 'pass';

$conn = mysqli_connect($host, $user, $pass, $dbname);
if (!$conn) {
    die("Errore connessione DB: " . mysqli_connect_error());
}
mysqli_set_charset($conn, "utf8");
?>