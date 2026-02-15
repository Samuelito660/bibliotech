<?php
    require_once "db.php";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $email = $_POST["email"];
        $pass = $_POST["pass"];

        $res = $conn->query("SELECT * FROM utenti WHERE email='$email'");
        $u = $res->fetch_assoc();

        if ($u && password_verify($pass, $u["pass"])) {
           setcookie("idU", $u["idU"
        } else {
            echo "Email o password errati";
        }

    }


?>