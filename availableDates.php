<?php 

require_once('dbconnect.php');

$sql = "SELECT DISTINCT datum FROM pracovnidata";
$result = $conn->query($sql);

$availableDates = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $availableDates[] = $row['datum'];
    }
}

$conn->close();

echo json_encode($availableDates);
?>
