<?php
require_once('dbconnect.php');

$datum = $_POST['datum']??"";
$formattedDatum = date('Y-m-d', strtotime($datum));
$sql = "SELECT jmeno FROM operator WHERE id IN (
    SELECT operator_id_1 FROM pracovnidata WHERE datum = '$formattedDatum'
    UNION
    SELECT operator_id_2 FROM pracovnidata WHERE datum = '$formattedDatum'
)";
$result = $conn->query($sql);

$operators = array();
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $operators[] = $row;
    }
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($operators);
