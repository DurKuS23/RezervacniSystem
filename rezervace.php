<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="rezervace.js"></script>
</head>

<body>
    <?php
    $servername = "localhost";
    $username = "root";
    $password = '';
    $dbname = "rezervace";

    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    } else {
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

            $sqlInsert = "INSERT INTO reservations (operator_id, sluzba_id, cas_sluzby, datum_sluzby) 
                      VALUES ('$operatorId', '$serviceId', '$serviceTime', '$formattedDate')";

            if ($conn->query($sqlInsert) === TRUE) {
                header("Location: index.html");
                exit();
            }
        } else {
            header("Location: Rezervace.html");
            exit();
            echo "Operátor s jménem $operatorName nebyl nalezen nebo služba s názvem $serviceName nebyla nalezena.";
        }
    }
    ?>
</body>

</html>