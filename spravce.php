<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.php");
    exit();
}

if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();

    header("Location: index.php");
    exit();
}


require_once('scripts/dbconnect.php');

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_reservation'])) {
    $reservation_id = $_POST['reservation_id'];

    $delete_query = "DELETE FROM reservations WHERE reservation_id = $reservation_id";

    if ($conn->query($delete_query) === TRUE) {
        echo "<script> alert('Rezervace s ID $reservation_id byla úspěšně smazána.') </script>";
    } else {
        echo "Chyba při mazání rezervace: " . $conn->error;
    }
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['delete_reservation'])) {
    $reservation_id = $_POST['reservation_id'];
    echo "<script>
            var response = confirm('Opravdu chcete smazat rezervaci?');
            if (response == true) {
                window.location.href = 'spravce.php?delete_reservation=true&reservation_id=" . $reservation_id . "';
            }
          </script>";
}

if (isset($_GET['delete_reservation']) && $_GET['delete_reservation'] === 'true' && isset($_GET['reservation_id'])) {
    $delete_reservation_id = $_GET['reservation_id'];

    $delete_query = "DELETE FROM reservations WHERE reservation_id = $delete_reservation_id";

    if ($conn->query($delete_query) === TRUE) {
        echo "<script> alert('Rezervace s ID $delete_reservation_id byla úspěšně smazána.') </script>";
    } else {
        echo "Chyba při mazání rezervace: " . $conn->error;
    }

    header("Location: spravce.php");
    exit();
}

$query = "SELECT r.reservation_id, o.jmeno AS operator_jmeno, r.datum_sluzby, r.cas_sluzby, s.typ_sluzby, u.Jmeno, u.Prijmeni, u.Email
          FROM reservations r
          LEFT JOIN operator o ON r.operator_id = o.id
          LEFT JOIN sluzba s ON r.sluzba_id = s.id
          LEFT JOIN uzivatele u ON r.uzivatel_id = u.id";

$result = $conn->query($query);
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
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/spravce.css">
    <script src="scripts/javascript.js"></script>
    <script src="scripts/spravce.js"></script>
</head>

<body>

    <div class="pozice">
        <div class="topnav" id="myTopnav">
            <a href="index.php">Úvodní stránka</a>
            <?php
            if (!isset($_SESSION['logged_in'])) {
                echo '<a href="" onclick="Login()">Přihlášení</a>';
                echo '<a href="" onclick="Register()">Registrace</a>';
            }
            ?>
            <a href="rezervace.php">Rezervace</a>
            <a href="editor.php">Editor</a>
            <a href="spravce.php">Správce</a>

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
                echo "<form method='post'>";
                echo "<input type='hidden' name='reservation_id' value='" . $row["reservation_id"] . "'>";
                echo "<button type='button' class='delete_reservation' onclick='showConfirmation(" . $row["reservation_id"] . ")'>Smazat</button>";
                echo "</form>";
                echo "<button onclick=\"editReservation(" . $row['reservation_id'] . ")\">Upravit</button>";
                echo "</div>";
                echo "<p class='bold-text'>Reservation ID: " . $row["reservation_id"] . "</p>";
                echo "<p>Obsluha: " . $row["operator_jmeno"] . "</p>";
                echo "<p>Datum: " . $row["datum_sluzby"] . "</p>";
                echo "<p>Čas: " . $row["cas_sluzby"] . "</p>";
                echo "<p>Služba: " . $row["typ_sluzby"] . "</p>";
                echo "<br>";
                echo "<p style='font-weight: bold;'> Údaje o uživateli </p>";
                echo "<p>Jméno: " . $row["Jmeno"] . "</p>";
                echo "<p >Příjmení: " . $row["Prijmeni"] . "</p>";
                echo "<p>Email: " . $row["Email"] . "</p>";
                echo "</div>";
                $counter++;
            }
            echo "</div>";
        } else {
            echo "<p>No reservations found.</p>";
        }

        ?>

    </div>
</body>

</html>