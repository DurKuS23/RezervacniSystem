<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rezervace";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentDate = date('Y-m-d');
$nextDate = date('Y-m-d', strtotime($currentDate . ' + 1 day'));
$nextDayName = date('D', strtotime($nextDate));

$dayNamesMapping = array(
    'Mon' => 'Po',
    'Tue' => 'Út',
    'Wed' => 'St',
    'Thu' => 'Čt',
    'Fri' => 'Pá',
    'Sat' => 'So',
    'Sun' => 'Ne'
);

$nextDayNameCzech = $dayNamesMapping[$nextDayName];

$sql = "INSERT INTO dny (Datum, Nazev) VALUES ('$nextDate', '$nextDayNameCzech') ON DUPLICATE KEY UPDATE Nazev = '$nextDayNameCzech'";

if ($conn->query($sql) === TRUE) {
    echo "Záznam úspěšně aktualizován.";
} else {
    echo "Chyba při aktualizaci záznamu: " . $conn->error;
}

$conn->close();
?>
