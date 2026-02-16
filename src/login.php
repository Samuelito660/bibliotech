<?php
ob_start();
session_start();
include 'includes/db.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    
    $stmt = mysqli_prepare($conn, "SELECT idU, nome, cogn, pass, ruolo FROM utenti WHERE email = ?");
    mysqli_stmt_bind_param($stmt, "s", $email);
    mysqli_stmt_execute($stmt);
    mysqli_stmt_bind_result($stmt, $idU, $nome, $cogn, $hashedPassword, $ruolo);
    
    if (mysqli_stmt_fetch($stmt)) {
        mysqli_stmt_close($stmt);  
        if (password_verify($password, $hashedPassword)) {
            $stmtSess = mysqli_prepare($conn, "INSERT INTO sessioni (idU, dataInizio, stato) VALUES (?, NOW(), 'attiva')");
            mysqli_stmt_bind_param($stmtSess, "i", $idU);
            mysqli_stmt_execute($stmtSess);
            $idSess = mysqli_insert_id($conn);
            mysqli_stmt_close($stmtSess);
            
            $_SESSION['idU'] = $idU;
            $_SESSION['nome'] = $nome;
            $_SESSION['ruolo'] = $ruolo;
            $_SESSION['logged_in'] = true;
            $_SESSION['idSess'] = $idSess;
            header('Location: index.php');
            exit();
        } else {
            $error = "Password errata.";
        }
    } else {
        mysqli_stmt_close($stmt);  
        $error = "Email non trovata.";
    }
}
ob_end_flush();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - BiblioTech</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<div class="container mt-5">
    <h1>Login</h1>
    <?php if (isset($error)) echo "<div class='alert alert-danger'>$error</div>"; ?>
    <form method="POST">
        <div class="mb-3">
            <label>Email</label>
            <input type="email" name="email" class="form-control" required>
        </div>
        <div class="mb-3">
            <label>Password</label>
            <input type="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-primary">Accedi</button>
    </form>
    <p>Non hai un account? <a href="registrazione.php">Registrati</a></p>
</div>
</body>
</html>