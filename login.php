<?php
session_start();

require_once('dbconnect.php');

$Email = $_POST['email'];
$Password = $_POST['heslo'];

$Email = mysqli_real_escape_string($conn, $Email);

$sql = "SELECT Email, Heslo FROM uzivatele WHERE Email = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $Email);
$stmt->execute();
$result = $stmt->get_result();

$Logged;
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    if (hash("sha256", $Password) == $row['Heslo']) {
        $Logged = 0;
        $_SESSION['user_email'] = $Email;
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
            echo "<script> window.location.href = 'index.php'; </script>";
            echo "<script>window.opener.location.reload();</script>";
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

$stmt->close();
$conn->close();
?>
