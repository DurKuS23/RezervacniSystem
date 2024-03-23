<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.php");
    exit();
}
require_once('scripts/dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $reservation_id = $_POST['reservation_id'];
    $datum_sluzby = $_POST['datum_sluzby'];
    $cas_sluzby = $_POST['cas_sluzby'];
    $operator_id = $_POST['operator_id'];
    $sluzba_id = $_POST['sluzba_id'];

    $query_update = "UPDATE reservations SET datum_sluzby = ?, cas_sluzby = ?, operator_id = ?, sluzba_id = ? WHERE reservation_id = ?";
    $stmt_update = $conn->prepare($query_update);
    $stmt_update->bind_param("ssiii", $datum_sluzby, $cas_sluzby, $operator_id, $sluzba_id, $reservation_id);

    if ($stmt_update->execute()) {
        header("Location: spravce.php");
        exit();
    } else {
        echo "Chyba při aktualizaci dat: " . $conn->error;
    }
    $stmt_update->close();
}

$reservation_id = $_GET['id'];
$query_select = "SELECT r.reservation_id, r.datum_sluzby, r.cas_sluzby, r.operator_id, r.sluzba_id, o.jmeno AS operator_jmeno, s.typ_sluzby
                FROM reservations r
                LEFT JOIN operator o ON r.operator_id = o.id
                LEFT JOIN sluzba s ON r.sluzba_id = s.id
                WHERE r.reservation_id = ?";
$stmt_select = $conn->prepare($query_select);
$stmt_select->bind_param("i", $reservation_id);
$stmt_select->execute();
$result_select = $stmt_select->get_result();

if ($result_select->num_rows > 0) {
    $row = $result_select->fetch_assoc();
} else {
    echo "Nebyly nalezeny žádné informace pro dané ID rezervace.";
}

$stmt_select->close();

$query_operators = "SELECT id, jmeno FROM operator";
$result_operators = $conn->query($query_operators);
$query_services = "SELECT id, typ_sluzby FROM sluzba";
$result_services = $conn->query($query_services);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/editor_rezervace.css">
    <title>Editace rezervace</title>
</head>

<body>
    <h1>Editace rezervace</h1>
    <div class="center-form">
        <form method="post" action="<?php echo $_SERVER["PHP_SELF"]; ?>">
            <input type="hidden" name="reservation_id" value="<?php echo $row['reservation_id']; ?>">
            <label>Datum:</label>
            <input type="date" name="datum_sluzby" value="<?php echo $row['datum_sluzby']; ?>"><br><br>
            <label>Čas:</label>
            <input type="time" name="cas_sluzby" value="<?php echo $row['cas_sluzby']; ?>"><br><br>

            <label for="operator">Operátor:</label>
            <select name="operator_id" id="operator">
                <?php
                while ($operator = $result_operators->fetch_assoc()) {
                    $selected = ($row['operator_id'] == $operator['id']) ? 'selected' : '';
                    echo "<option value='" . $operator['id'] . "' $selected>" . $operator['jmeno'] . "</option>";
                }
                ?>
            </select><br><br>

            <label for="sluzba">Služba:</label>
            <select name="sluzba_id" id="sluzba">
                <?php
                while ($service = $result_services->fetch_assoc()) {
                    $selected = ($row['sluzba_id'] == $service['id']) ? 'selected' : '';
                    echo "<option value='" . $service['id'] . "' $selected>" . $service['typ_sluzby'] . "</option>";
                }
                ?>
            </select><br><br>

            <input type="submit" value="Uložit změny">
            <button onclick="window.location.href = 'spravce.php'">Vrátit se zpět </button>
        </form>
    </div>
    <br>



</body>

</html>