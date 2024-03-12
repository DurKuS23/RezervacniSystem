<?php
require_once('dbconnect.php');

$updateID = $_POST['updateID'];
$newTypSluzby = $_POST['typSluzby'];
$newCasSluzby = $_POST['casSluzby'];
$newCena = $_POST['cena'];

$sql_update = "UPDATE sluzba SET typ_sluzby=?, casSluzby=?, cena=? WHERE id=?";
$stmt = $conn->prepare($sql_update);
$stmt->bind_param("sssi", $newTypSluzby, $newCasSluzby, $newCena, $updateID);

if ($stmt->execute()) {
    echo "Záznam byl úspěšně aktualizován.";
} else {
    echo "Chyba při aktualizaci záznamu: " . $conn->error;
}

$conn->close();
?>
