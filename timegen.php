<?php

session_start(); 
$servename = "localhost";
$username = "root";
$password = "";
$dbname = "rezervace";

$conn = new mysqli($servename, $username, $password, $dbname);


$selectedDate = $_GET['datum'] ?? "";
$formattedDatum = date('Y-m-d', strtotime($selectedDate));

$sqlSelect = "SELECT datum_sluzby, cas_sluzby, casSluzby FROM reservations WHERE datum_sluzby = '$formattedDatum'";
$result = $conn->query($sqlSelect);

$sql = "SELECT cas_otvirani, cas_zavirani FROM casrozpeti WHERE id = 1";
$resultTime = $conn->query($sql);

if ($resultTime->num_rows > 0) {
    $row = $resultTime->fetch_assoc();
    $openingTime = $row["cas_otvirani"];
    $closingTime = $row["cas_zavirani"];
} 

$ArrayWithSTime = array(); /* Pole s časy rezervací pro dané datum */
$ArrayWithLTime = array(); /* Pole s časy jak dlouho dané rezervace budou trvat */
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cas_sluzby = $row["cas_sluzby"];
        $timeModified = date('H:i', strtotime($cas_sluzby));

        $casSluzby = $row["casSluzby"];
        $datum = $row["datum_sluzby"];


        $ArrayWithSTime[] = $timeModified;
        $ArrayWithLTime[] = $casSluzby;
    }
} 

$ArrayWithTimeFiltered = array();
$interval = new DateInterval('PT15M');
$currentDateTime = new DateTime($openingTime);
$closingDateTime = new DateTime($closingTime);


while ($currentDateTime <= $closingDateTime) {
    $timeToAdd = $currentDateTime->format('H:i');

    $isTimeAvailable = true;
    foreach ($ArrayWithSTime as $index => $startTime) {
        $startTimeStamp = strtotime($startTime);
        $endTimeStamp = $startTimeStamp + strtotime($ArrayWithLTime[$index]) - strtotime('TODAY');

        if ($currentDateTime >= new DateTime($startTime) && $currentDateTime < new DateTime(date('H:i', $endTimeStamp))) {
            $isTimeAvailable = false;
            break;
        }
    }

    if ($isTimeAvailable) {
        $ArrayWithTimeFiltered[] = $timeToAdd;
    }
    $currentDateTime->add($interval);
}

header('Content-Type: application/json');
echo json_encode($ArrayWithTimeFiltered);


?>