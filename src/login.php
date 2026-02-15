<?php
    require_once "db.php";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $pass = $_POST["pass"];

        $res = $conn->query("SELECT * FROM utenti WHERE email='$email'");
        $u = $res->fetch_assoc();

        if ($u && password_verify($pass, $u["pass"])) {
           setcookie("idU", $u["idU"],0,"/");
           setcookie("ruolo", $u["ruolo"],0,"/");
           header("Location: catalogo.php");
           exit();
        } else {
            echo "Email o password errati";
        }

    }


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <h2>Accesso al Sistema BiblioTech</h2>
    
    <form action="login.php" method="POST">
        
        <div class="form-group">
            <label for="email">Email:</label><br>
            
            <input type="email" id="email" name="email" required>
        </div>

        <br>

        <div class="form-group">
            <label for="pass">Password:</label><br>
           
            <input type="password" id="pass" name="pass" required>
        </div>

        <br>

        <button type="submit">Accedi</button>
    </form>
    
</body>
</html>