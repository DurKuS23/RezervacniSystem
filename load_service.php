<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rezervace";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$currentRow = isset($_POST['currentRow']) ? intval($_POST['currentRow']) : 1;

$sql = "SELECT * FROM sluzba WHERE id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $currentRow);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
    echo '<input type="hidden" id="currentRow" value="' . $currentRow . '">';
    echo '<div class="serviceEdt">';
    echo '<h2> Úprava služeb </h2>';
    echo '<div class="lineblue"></div>';
    echo '<div>ID: ' . $currentRow . '</div>';

    echo '<div class="row-buttons">';
    echo '<button onclick="prevService()">Předchozí</button>';
    echo '<button onclick="nextService()">Další</button>';
    echo '</div>';

    echo '<div class="bolderService">';
    echo '<input type="hidden" id="updateID" value="' . $currentRow . '">';
    echo 'Typ služby: <input type="text" id="typSluzby" value="' . $row["typ_sluzby"] . '"><br>';
    echo 'Čas služby: <input type="text" id="casSluzby" value="' . $row["casSluzby"] . ' min"><br>';
    echo 'Cena: <input type="text" id="cena" value="' . $row["cena"] . 'Kč"><br>';
    echo '<button class="serviceBtn" onclick="updateService()">Upravit</button>';
    echo '</div>';

    echo '</div>';
} else {
    echo "0 results";
}

$conn->close();
?>
