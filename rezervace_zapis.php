<?php
session_start();
$servername = "localhost";
$username = "root";
$password = '';
$dbname = "rezervace";

if (!isset($_SESSION['user_email'])) {
    $_SESSION['message'] = "Nejste přihlášen/a.";
    header("Location: rezervace.php");
    exit();
} else {
    $email = $_SESSION['user_email'];
}

$operatorName = $_POST['selectedCService'];
$serviceName = $_POST['selectedService'];
$serviceDate = $_POST['selectedDate'];
$serviceTime = $_POST['selectedTime'];
if (empty($operatorName) || empty($serviceName) || empty($serviceDate) || empty($serviceTime)) {
    $_SESSION['message'] = "Prosím vyplňte všechna pole formuláře";
    header("Location: rezervace.php");
    exit();
}

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
} else {
    $sqlUserId = "SELECT id FROM uzivatele WHERE Email = '$email'";
    $resultUserId = $conn->query($sqlUserId);
    $rowUserId = $resultUserId->fetch_assoc();
    $user_id = $rowUserId['id'];


    $formattedDate = date('Y-m-d', strtotime($serviceDate));

    $sqlOperator = "SELECT id FROM operator WHERE jmeno = '$operatorName'";
    $resultOperator = $conn->query($sqlOperator);

    $sqlService = "SELECT id, casSluzby FROM sluzba WHERE typ_sluzby = '$serviceName'";
    $resultService = $conn->query($sqlService);

    if ($resultOperator->num_rows > 0 && $resultService->num_rows > 0) {
        $rowOperator = $resultOperator->fetch_assoc();
        $operatorId = $rowOperator['id'];

        $rowService = $resultService->fetch_assoc();
        $serviceId = $rowService['id'];
        $serviceDuration = $rowService['casSluzby'];

        $sqlInsert = "INSERT INTO reservations (uzivatel_id, operator_id, sluzba_id, cas_sluzby, datum_sluzby, casSluzby) 
              VALUES ('$user_id', '$operatorId', '$serviceId', '$serviceTime', '$formattedDate', '$serviceDuration')";

        if ($conn->query($sqlInsert)) {
            $_SESSION['message'] = "Rezerváce úspešně odeslána.";
            header("Location: index.php");
            exit();
        } else {
            header("Location: index.php");
            exit();
        }
    }
}
