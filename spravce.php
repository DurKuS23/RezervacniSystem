<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.html");
    exit();
}

if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();

    header("Location: index.html");
    exit();
}

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rezervace";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$query = "SELECT r.reservation_id, o.jmeno AS operator_jmeno, r.datum_sluzby, r.cas_sluzby, s.typ_sluzby
          FROM reservations r
          LEFT JOIN operator o ON r.operator_id = o.id
          LEFT JOIN sluzba s ON r.sluzba_id = s.id";

$result = $conn->query($query);
?>

<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "rezervace";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

function click($btn)
{
    $reservationId = $btn + 1;
    $sql = "DELETE FROM reservations WHERE reservation_id = '$reservationId'";
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Administrace</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="spravce.css">
    <script src="javascript.js"></script>
    <script src="spravce.js"></script>
</head>

<body>
    <div class="pozice">
        <div class="topnav" id="myTopnav">
            <a href="index.html">Úvodní stránka</a>
            <a href="login.html">Přihlášení</a>
            <a href="register.html">Registrace</a>
            <a href="Rezervace.html">Rezervace</a>
            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i> </a>
        </div>
    </div>

    <div class="header">
        <form method="post">
            <button type="submit" name="logout">Odhlásit se</button>
        </form>
    </div>


    <div>
        <?php
        if ($result->num_rows > 0) {
            $counter = 0;
            echo "<div class='reservations'>";
            while ($row = $result->fetch_assoc()) {
                if ($counter % 3 == 0) {
                    echo "</div>";
                    echo "<div class='reservations'>";
                }
                echo "<div class='res-row'>";
                echo "<div class='cont-buttons'>";
                echo "<button class='delete-button' onclick='window.click($counter);'>Smazat</button>";
                echo "</div>";
                echo "<p class='bold-text'>Reservation ID: " . $row["reservation_id"] . "</p>";
                echo "<p>Obsluha: " . $row["operator_jmeno"] . "</p>";
                echo "<p>Datum: " . $row["datum_sluzby"] . "</p>";
                echo "<p>Čas: " . $row["cas_sluzby"] . "</p>";
                echo "<p>Služba: " . $row["typ_sluzby"] . "</p>";
                echo "</div>";
                $counter++;
            }
            echo "</div>";
        } else {
            echo "<p>No reservations found.</p>";
        }



        $result->free_result();
        $conn->close();
        ?>

    </div>
</body>

</html>