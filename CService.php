<?php

require_once('dbconnect.php');

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['selectedOperator'])) {
    $selectedOperator = $_GET['selectedOperator'];

    $sql = "SELECT id, jmeno FROM operator WHERE id <> 1 AND id <> $selectedOperator";
    $result = $conn->query($sql);

    $options = "";
    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $options .= "<option value='" . $row["id"] . "'>" . $row["jmeno"] . "</option>";
        }
    } else {
        $options = "<option value=''>Žádní operátoři nebyli nalezeni.</option>";
    }

    echo $options;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $selectedDate = $_POST['selectedDate'];
    $dateParts = explode('.', $selectedDate);
    $day = $dateParts[0];
    $month = $dateParts[1];

    $selectedDateFormatted = "2024-$month-$day";

    echo "<p class='dbinfo'>Vyber zaměstnance pro toto datum: $selectedDateFormatted</p>";
    if (isset($_POST['submitBtn'])) {

        $servername = "localhost";
        $username = "root";
        $password = "";
        $dbname = "rezervace";

        $conn = new mysqli($servername, $username, $password, $dbname);

        if ($conn->connect_error) {
            die("Připojení k databázi selhalo: " . $conn->connect_error);
        }

        $sql_count = "SELECT COUNT(*) AS count FROM pracovnidata WHERE datum = '$selectedDateFormatted'";
        $result_count = $conn->query($sql_count);
        $row_count = $result_count->fetch_assoc();
        $count = $row_count['count'];

        if ($count > 0) {
            if (isset($_POST['operator1']) && isset($_POST['operator2'])) {
                $operator1_id = $_POST['operator1'];
                $operator2_id = $_POST['operator2'];

                $sql_update = "UPDATE pracovnidata SET operator_id_1 = '$operator1_id', operator_id_2 = '$operator2_id' WHERE datum = '$selectedDateFormatted'";
                if ($conn->query($sql_update) === TRUE) {
                    $response = array("status" => "success", "message" => "Data byla úspěšně aktualizována.");
                    echo json_encode($response);
                } else {
                    echo "Chyba při aktualizaci dat: " . $conn->error;
                }
            }
        } else {
            if (isset($_POST['operator1']) && isset($_POST['operator2'])) {
                $operator1_id = $_POST['operator1'];
                $operator2_id = $_POST['operator2'];

                $sql_insert = "INSERT INTO pracovnidata (datum, operator_id_1, operator_id_2) VALUES ('$selectedDateFormatted', '$operator1_id', '$operator2_id')";
                if ($conn->query($sql_insert) === TRUE) {
                    $response = array("status" => "success", "message" => "Data byla úspěšně uložena.");
                    echo json_encode($response);
                } else {
                    echo "Chyba při zápisu dat do databáze: " . $conn->error;
                }
            } else {
                echo "<p class='dbinfo'>Chyba: Operátoři nebyli správně vybráni.</p>";
            }
        }
        $conn->close();
    }
} else {
    echo "<p class='dbinfo'>Chyba: Neplatný požadavek</p>";
}
?>


<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['submitBtn'])) {
        $selectedDate = $_POST['selectedDate'];
        $operator1 = $_POST['operator1'];
        $operator2 = $_POST['operator2'];

        echo "Data byla úspěšně přijata: SelectedDate=$selectedDate, Operator1=$operator1, Operator2=$operator2";
    }
} else {
    echo "Chyba: Neplatný typ požadavku.";
}
?>