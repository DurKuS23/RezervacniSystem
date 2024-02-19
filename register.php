
<?php
$servername = "localhost";
$username = "root";
$password = '';
$dbname = "rezervace";

$conn = new mysqli($servername, $username, '', $dbname);

if ($conn->connect_error) {
    die("Chyba připojení k databázi: " . $conn->connect_error);
} else {
}

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
                $sql = "INSERT INTO uzivatele (Jmeno,Prijmeni,Email,Heslo) VALUES ('$Name', '$Surname','$Email','$hashHesla')";
                $conn->query($sql);
                echo '<script>alert("Úspěšně registrován !");</script>';
                session_start();
                $_SESSION['user_email'] = $Email;
                echo "<script>window.opener.location.reload();</script>";
                echo "<script>window.close();</script>";
            } else {
                echo '<script>alert("Chyba: Některá pole jsou prázdná nebo obsahují pouze mezery.")</script>';
                echo "<script> window.location.href = 'register.html'; </script>";
            }
        }
    }
}
$conn->close();
?>