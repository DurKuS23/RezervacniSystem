<?php
header('Content-Type: application/json');

$response = array();

if (isset($_POST['selectedDate'])) {
    $selectedDate = $_POST['selectedDate'];
    echo 'Vybrané datum: ' . $selectedDate;
} else {
    $response['error'] = 'Chyba: Data nebyla přijata.';
}
?>

<?php
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["openingTime"]) && isset($_POST["closingTime"])) {
    require_once('dbconnect.php');

    $openingTime = $_POST["openingTime"];
    $closingTime = $_POST["closingTime"];

    if ($openingTime < $closingTime) {
        $sql = "UPDATE casrozpeti SET cas_otvirani='$openingTime', cas_zavirani='$closingTime' WHERE id=1";

        if ($conn->query($sql) === TRUE) {
            echo "<p class='dbinfo'> Aktuální nastavený čas v databázi: Otevírací čas -  $openingTime, Zavírací čas - $closingTime </p>";
        } else {
            echo "Chyba při aktualizaci dat: " . $conn->error;
        }
    } else {
        echo "<script>alert('Čas nebyl aktualizován, nemůžeš mít otevírací čas větší než zavírací.');</script>";
    }

    $conn->close();
}
?>
