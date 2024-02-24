<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rezervace";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Chyba připojení k databázi: " . $conn->connect_error);
}

$sql = "SELECT cas_otvirani, cas_zavirani FROM casrozpeti WHERE id = 1";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    $openingTime = $row["cas_otvirani"];
    $closingTime = $row["cas_zavirani"];
    echo json_encode(array("openingTime" => $openingTime, "closingTime" => $closingTime));
} else {
    echo "Nepodařilo se získat časy z databáze.";
}

$conn->close();
?>
