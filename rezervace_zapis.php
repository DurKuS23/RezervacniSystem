<?php
session_start();
require_once('dbconnect.php');

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

$sqlUserId = "SELECT id FROM uzivatele WHERE Email = '$email'";
$resultUserId = $conn->query($sqlUserId);
$rowUserId = $resultUserId->fetch_assoc();
$user_id = $rowUserId['id'];


$formattedDate = date('Y-m-d', strtotime($serviceDate));

$sqlOperator = "SELECT id FROM operator WHERE jmeno = '$operatorName'";
$resultOperator = $conn->query($sqlOperator);

$sqlService = "SELECT id, casSluzby FROM sluzba WHERE typ_sluzby = '$serviceName'";
$resultService = $conn->query($sqlService);

$email = false;
if ($resultOperator->num_rows > 0 && $resultService->num_rows > 0) {
    $rowOperator = $resultOperator->fetch_assoc();
    $operatorId = $rowOperator['id'];

    $rowService = $resultService->fetch_assoc();
    $serviceId = $rowService['id'];
    $serviceDuration = $rowService['casSluzby'];

    $sqlInsert = "INSERT INTO reservations (uzivatel_id, operator_id, sluzba_id, cas_sluzby, datum_sluzby, casSluzby) 
              VALUES ('$user_id', '$operatorId', '$serviceId', '$serviceTime', '$formattedDate', '$serviceDuration')";

    if ($conn->query($sqlInsert)) {
        $email = true;
        $_SESSION['message'] = "Rezerváce úspešně odeslána.";
    } else {
        header("Location: index.php");
        exit();
    }
}

if ($email == true) {
    $sqlEmail = "SELECT r.reservation_id, o.jmeno AS operator_jmeno, r.datum_sluzby, r.cas_sluzby, s.typ_sluzby AS nazev_sluzby, s.casSluzby, u.Jmeno, u.Prijmeni, u.Email
        FROM reservations r 
        INNER JOIN operator o ON r.operator_id = o.id
        INNER JOIN sluzba s ON r.sluzba_id = s.id
        INNER JOIN uzivatele u ON r.uzivatel_id = u.id
        ORDER BY r.datum_sluzby DESC, r.cas_sluzby DESC
        LIMIT 1";

    $resultEmail = $conn->query($sqlEmail);
    $rowEmail = $resultEmail->fetch_assoc();
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require 'phpmailer/src/Exception.php';
require 'phpmailer/src/PHPMailer.php';
require 'phpmailer/src/SMTP.php';

$mail = new PHPMailer(true);

$mail->isSMTP();
$mail->Host = 'smtp.gmail.com';
$mail->SMTPAuth = true;
$mail->Username = 'rezervacerop@gmail.com';
$mail->Password = 'suergljpuvavmech';
$mail->SMTPSecure = 'ssl';
$mail->Port = 465;

$mail->setFrom('rezervacerop@gmail.com');
$mail->addAddress($rowEmail["Email"]);

$mail->isHTML(true);
$mail->Subject = 'Potvrzení rezervace';
$mail->Body    = 'Vaše ID rezervace: ' . $rowEmail["reservation_id"] . '<br>' .
    'Zvolená obsluha: ' . $rowEmail["operator_jmeno"] . '<br>' .
    'Datum rezervace: ' . $rowEmail["datum_sluzby"] . '<br>' .
    'Čas rezervace: ' . $rowEmail["cas_sluzby"] . '<br>' .
    'Zvolená služba: ' . $rowEmail["nazev_sluzby"] . '<br>' .
    'Doba trvání rezervace: ' . $rowEmail["casSluzby"] . '<br>' . '<br>' .
    'Rezervace vytvořená na jméno' . '<br>' .
    'Jméno: ' . $rowEmail["Jmeno"] . '<br>' .
    'Příjmení: ' . $rowEmail["Prijmeni"] . '<br>' .
    'Email: ' . $rowEmail["Email"] . '<br> ' . '<br>' .
    'V případě změn nás prosím kontaktujte na email rezervacerop@gmail.com';

$mail->send();
echo "
<script> 
alert('Podrobnosti naleznete v emailu');
document.location.href='index.php';
</script>
";
