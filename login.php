<?php
session_start();


require_once('dbconnect.php');

$Email = $_POST['email'];
$Password = $_POST['heslo'];

$Email = mysqli_real_escape_string($conn, $Email);

$sql = "SELECT Email, Heslo FROM uzivatele WHERE Email = '$Email'";
$result = $conn->query($sql);

$Logged;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (hash("sha256", $Password) == $row['Heslo']) {
        $Logged = 0;
        $_SESSION['user_email'] = $Email; // Uložení e-mailu do relace

        echo "<script> alert('Úspěšně přihlášen') </script>";
    } else {
        $Logged = 1;
        echo "<script> alert('Chybné heslo.') </script>";
    }
} else {
    $Logged = 2;
    echo "<script> alert('Uživatel s tímto e-mailem neexistuje.') </script>";
}

switch ($Logged) {
    case 0: {
            echo "<script>window.opener.location.reload();</script>";
            echo "<script>window.close();</script>";
        }
        break;
    case 1: {
            echo "<script> window.location.href = 'login.html'; </script>";
        }
        break;
    case 2: {
            echo "<script> window.location.href = 'login.html'; </script>";
        }
        break;
}

$conn->close();
?>