<?php
    $conn = new mysqli("db", "root", "root_password", "biblioteca");
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

?>