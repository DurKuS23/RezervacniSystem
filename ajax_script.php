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

