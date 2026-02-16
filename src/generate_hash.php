<?php

// Questo file serve solo a generare gli hash per le password da inserire nel database.
// seppur non sia più necessario, lo lascio per eventuali future aggiunte di utenti o per test e per vedere cosa mettere come passsword, 
// In caso di vero programma esso verrebbe eliminato senza alcuna ripercussione sull'applicazione visto che le password non dovrebbero risultare visibili.

$hash1 = password_hash('123', PASSWORD_DEFAULT);
echo "Hash generato: " . $hash1; 
echo "<br>";
$hash2 = password_hash('ciao', PASSWORD_DEFAULT);
echo "Hash generato: " . $hash2;
echo "<br>";
$hash3 = password_hash('tpsit', PASSWORD_DEFAULT);
echo "Hash generato: " . $hash3;
echo "<br>";
$hash4 = password_hash('bari', PASSWORD_DEFAULT);
echo "Hash generato: " . $hash4;
echo "<br>";
$hash5 = password_hash('admin', PASSWORD_DEFAULT);
echo "Hash generato: " . $hash5;


?>