<?php
session_start();

require_once('dbconnect.php');

if (isset($_SESSION["logged_in"]) && $_SESSION["logged_in"] === true) {
    header("Location: spravce.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $enteredEmail = $_POST["email"];
    $enteredPassword = $_POST["password"];

    $stmt = $conn->prepare("SELECT email, password_hash FROM admins WHERE email = ?");
    $stmt->bind_param("s", $enteredEmail);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        $storedEmail = $row["email"];
        $storedPasswordHash = $row["password_hash"];

        $enteredPasswordHash = hash("sha256", $enteredPassword);

        if ($enteredEmail == $storedEmail && $enteredPasswordHash == $storedPasswordHash) {
            $_SESSION["logged_in"] = true;
            header("Location: spravce.php");
            exit();
        } else {
            $error = "Neplatné přihlašovací údaje";
        }
    } else {
        $error = "Uživatel s tímto e-mailem neexistuje";
    }
    $stmt->close();
}
?>




<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Přihlášení</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="style.css">
</head>

<body>
    <div class="logo">
        <img src="image/logo.png" alt="logo" onclick="closeWindow()">
    </div>
    <div class="container2">
        <h1>Přihlášení</h1>
        <form action="" method="post">
            <input type="email" name="email" placeholder="email" required>
            <input type="password" name="password" placeholder="password" required>
            <button type="submit">Přihlásit</button>
        </form>
    </div>
</body>

</html>