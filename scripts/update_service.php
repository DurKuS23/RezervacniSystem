<?php
require_once('dbconnect.php');

$updateID = $_POST['updateID'];
$newTypSluzby = $_POST['typSluzby'];
$casSluzby = $_POST['casSluzby'];
$casSluzby = str_replace([' ', 'min'], '', $casSluzby);
$newCena = $_POST['cena'];

echo $_POST['casSluzby'];

$sql_update = "UPDATE sluzba SET typ_sluzby=?, casSluzby=?, cena=? WHERE id=?";
$stmt = $conn->prepare($sql_update);
$stmt->bind_param("sssi", $newTypSluzby, $casSluzby, $newCena, $updateID);

if ($stmt->execute()) {
    echo "Záznam byl úspěšně aktualizován.";
} else {
    echo "Chyba při aktualizaci záznamu: " . $conn->error;
}
