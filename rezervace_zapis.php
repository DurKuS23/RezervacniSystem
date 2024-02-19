<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="rezervace.js"></script>
</head>
<script>
    function submitForm() {

        var selectedTimeElement = document.getElementById("ZvolenyCas");
        var selectedCServiceElement = document.getElementById("ZvolenaObsluha");
        var selectedServiceElement = document.getElementById("ZvolenaSluzba");
        var selectedDateElement = document.getElementById("zvoleneDatum");

        var selectedTime = selectedTimeElement.textContent.replace("Zvolený čas:", "").trim();
        var selectedService = selectedServiceElement.textContent.replace("Zvolená služba:", "").trim();
        var selectedCService = selectedCServiceElement.textContent.replace("Zvolená obsluha:", "").trim();
        var selectedDate = selectedDateElement.textContent.replace("Datum:", "").trim();

        var isLoggedIn = <?php echo $isLoggedIn ? 'true' : 'false'; ?>;

        if (isLoggedIn) {
            if (selectedTime !== "Vyberte čas" && selectedService !== "Služba" && selectedCService !== "Preferovaná obsluha" && selectedDate !== "Datum") {
                document.getElementById("selectedTime").value = selectedTime;
                document.getElementById("selectedCService").value = selectedCService;
                document.getElementById("selectedService").value = selectedService;
                document.getElementById("selectedDate").value = selectedDate;
                alert("Vaše rezervace byla úspěšná !");
            } else {
                alert("Prosím vyplňte všechna pole formuláře.");
            }
        } else {
            alert("Pro provedení rezervace se prosím přihlaste.");
        }
    }
</script>

<body>
    <?php
    session_start();
    $servername = "localhost";
    $username = "root";
    $password = '';
    $dbname = "rezervace";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
        $sqlUserId = "SELECT id FROM uzivatele WHERE email = '$user_email'";
        $resultUserId = $conn->query($sqlUserId);
        $rowUserId = $resultUserId->fetch_assoc();
        $user_id = $rowUserId['id'];

        $operatorName = $_POST['selectedCService'];
        $serviceName = $_POST['selectedService'];
        $serviceDate = $_POST['selectedDate'];
        $serviceTime = $_POST['selectedTime'];
        $formattedDate = date('Y-m-d', strtotime($serviceDate));

        $sqlOperator = "SELECT id FROM operator WHERE jmeno = '$operatorName'";
        $resultOperator = $conn->query($sqlOperator);

        $sqlService = "SELECT id FROM sluzba WHERE typ_sluzby = '$serviceName'";
        $resultService = $conn->query($sqlService);

        if ($resultOperator->num_rows > 0 && $resultService->num_rows > 0) {
            $rowOperator = $resultOperator->fetch_assoc();
            $operatorId = $rowOperator['id'];

            $rowService = $resultService->fetch_assoc();
            $serviceId = $rowService['id'];

            $sqlInsert = "INSERT INTO reservations (uzivatel_id, operator_id, sluzba_id, cas_sluzby, datum_sluzby) 
              VALUES ('$user_id', '$operatorId', '$serviceId', '$serviceTime', '$formattedDate')";

            if ($conn->query($sqlInsert) === TRUE) {
                header("Location: index.php");
                exit();
            }
        } else {
            header("Location: Rezervace.php");
            exit();
            echo "Operátor s jménem $operatorName nebyl nalezen nebo služba s názvem $serviceName nebyla nalezena.";
        }
    }
    ?>
</body>

</html>