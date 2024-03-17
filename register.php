<?php
require_once('dbconnect.php');

$Name = $_POST['jmeno'];
$Surname = $_POST['prijmeni'];
$Email = $_POST['email'];
$Password = $_POST['heslo'];
$hashHesla = hash("sha256", $Password);

function kontrolaNeprazdnychDat($data)
{
    foreach ($data as $key => $value) {
        if (empty(trim($value))) {
            return false;
        }
    }
    return true;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['odeslat'])) {
        $query = "SELECT COUNT(*) FROM uzivatele WHERE Email = ?";
        $stmt = $conn->prepare($query);
        $stmt->bind_param("s", $Email);
        $stmt->execute();
        $stmt->bind_result($count);
        $stmt->fetch();
        $stmt->close();

        if ($count > 0) {
            echo '<script>alert("Tato emailová adresa je již registrovaná.");</script>';
            echo "<script> window.location.href = 'register.html'; </script>";
        } else {
            $dataKUlozeni = array(
                'Jmeno' => $Name,
                'Prijemni' => $Surname,
                'Email' => $Email,
                'Heslo' => $hashHesla
            );

            if (kontrolaNeprazdnychDat($dataKUlozeni)) {
                $sql = "INSERT INTO uzivatele (Jmeno,Prijmeni,Email,Heslo) VALUES (?,?,?,?)";
                $stmt = $conn->prepare($sql);
                $stmt->bind_param("ssss", $Name, $Surname, $Email, $hashHesla);
                $stmt->execute();
                $stmt->close();

                echo '<script>alert("Úspěšně registrován !");</script>';
                session_start();
                $_SESSION['user_email'] = $Email;
                echo "<script> window.location.href = 'index.php'; </script>";
                echo "<script>window.opener.location.reload();</script>";
            } else {
                echo '<script>alert("Chyba: Některá pole jsou prázdná nebo obsahují pouze mezery.")</script>';
                echo "<script> window.location.href = 'register.html'; </script>";
            }
        }
    }
}
$conn->close();
?>
