<?php

    require_once "db.php";
    $idU = $_COOKIE["idU"]; 
    
    if(isset($_POST["effettuaPrestito"])) {
        $idL = $_POST["idL"];
        $conn->query("UPDATE libri SET copieDis = copieDis - 1 WHERE idL = $idL AND copieDis > 0");

        if($conn->affected_rows > 0) {
            $conn->query("INSERT INTO prestiti (idU, idL) VALUES ($idU, $idL)");

        }
    }

    $libri = $conn->query("SELECT * FROM libri");
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Catalogo Libri</title>

     <style>
        table { width: 100%; border-collapse: collapse; }
        th, td { padding: 10px; border: 1px solid #ddd; text-align: left; }
        .disponibile { color: green; font-weight: bold; }
        .esaurito { color: red; }
    </style>


</head>
<body>
    <h1>Catalogo Libri BiblioTech</h1>
    <p>Benvenuto utente ID: <?= htmlspecialchars($_COOKIE['idU']) ?> | <a href="logout.php">Logout</a></p>

    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Titolo</th>
                <th>Autore</th>
                <th>Disponibilità</th>
                <th>Azioni</th>
            </tr>
        </thead>
        <tbody>
            <?php 
            
            while ($libro = $res->fetch_array()): 
            ?>
            <tr>
                <td><?= $libro['idL'] ?></td>
                <td><strong><?= htmlspecialchars($libro['titolo']) ?></strong></td>
                <td><?= htmlspecialchars($libro['autore']) ?></td>
                <td>
                    <?php if ($libro['copieDis'] > 0): ?>
                        <span class="disponibile"><?= $libro['copieDis'] ?> copie disponibili</span>
                    <?php else: ?>
                        <span class="esaurito">Non disponibile</span>
                    <?php endif; ?>
                </td>
                <td>
                   
                    <a href="libro.php?id=<?= $libro['idL'] ?>">Dettaglio</a> | 
                    
                   
                    <form action="prestito.php" method="POST" style="display:inline;">
                        <input type="hidden" name="idL" value="<?= $libro['idL'] ?>">
                        
                        <button type="submit" <?php if ($libro['copieDis'] <= 0) echo 'disabled'; ?>>
                            PRENDI IN PRESTITO
                        </button>
                    </form>
                </td>
            </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    
</body>
</html>