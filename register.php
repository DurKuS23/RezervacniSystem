
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
    // prochazeni vsech dat v poli
    foreach ($data as $key => $value) {
        if (empty(trim($value))) {
            return false;
        }
    }
    return true;
}

// Problém 1,2
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['odeslat'])) {

        $dataKUlozeni = array(
            'Jmeno' => $Name,
            'Prijemni' => $Surname,
            'Email' => $Email,
            'Heslo' => $hashHesla
        );

        if (kontrolaNeprazdnychDat($dataKUlozeni)) {
            $sql = "INSERT INTO uzivatele (Jmeno,Prijmeni,Email,Heslo) VALUES ('$Name', '$Surname','$Email','$hashHesla')";
            $conn->query($sql);
        } else {
            echo '<script>alert("Chyba: Některá pole jsou prázdná nebo obsahují pouze mezery.")</script>';
        }
    }
}




$conn->close();
?>