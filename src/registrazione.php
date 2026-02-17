<?php
include 'includes/db.php';

$errori = [];
$successo = "";

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nome = trim($_POST['nome']);
    $cogn = trim($_POST['cogn']); 
    $email = trim($_POST['email']);
    $pass = $_POST['pass'];
    $conferma_pass = $_POST['confirm_pass'];

    if (empty($nome) || empty($cogn) || empty($email) || empty($pass)) {
        $errori[] = "Tutti i campi sono obbligatori.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errori[] = "Formato email non valido.";
    } elseif ($pass !== $conferma_pass) {
        $errori[] = "Le password non coincidono.";
    }

    if (empty($errori)) {
        $stmt = mysqli_prepare($conn, "SELECT idU FROM utenti WHERE email = ?");
        mysqli_stmt_bind_param($stmt, "s", $email);
        mysqli_stmt_execute($stmt);
        mysqli_stmt_store_result($stmt);
        if (mysqli_stmt_num_rows($stmt) > 0) {
            $errori[] = "Questa email è già registrata.";
        }
        mysqli_stmt_close($stmt);
    }

    if (empty($errori)) {
        $password_hash = password_hash($pass, PASSWORD_DEFAULT);
        
        $stmt = mysqli_prepare($conn, "INSERT INTO utenti (nome, cogn, email, pass, ruolo, portafoglio) VALUES (?, ?, ?, ?, 'studente', 0.00)");
        mysqli_stmt_bind_param($stmt, "ssss", $nome, $cogn, $email, $password_hash);
        if (mysqli_stmt_execute($stmt)) {
            $successo = "Registrazione completata! Ora puoi effettuare il login.";
        } else {
            $errori[] = "Errore durante il salvataggio: " . mysqli_error($conn);
        }
        mysqli_stmt_close($stmt);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registrazione Studente - BiblioTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
</head>
<body class="bg-light">
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="card shadow">
                <div class="card-body">
                    <h2 class="card-title text-center">Registrazione Studente</h2>
                    <hr>

                    <?php if ($successo): ?>
                        <div class='alert alert-success'><?php echo $successo; ?></div>
                    <?php endif; ?>

                    <?php if (!empty($errori)): ?>
                        <div class='alert alert-danger'>
                            <ul class="mb-0">
                                <?php foreach ($errori as $e) echo "<li>$e</li>"; ?>
                            </ul>
                        </div>
                    <?php endif; ?>

                    <form method="POST" action="registrazione.php">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Nome</label>
                                <input type="text" name="nome" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Cognome (cogn)</label>
                                <input type="text" name="cogn" class="form-control" required>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Email</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Password (pass)</label>
                            <input type="password" name="pass" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Conferma Password</label>
                            <input type="password" name="confirm_pass" class="form-control" required>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary">Crea Account</button>
                        </div>
                    </form>
                    <p class="mt-3 text-center">Hai già un account? <a href="login.php">Accedi qui</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
</body>
</html>