<!DOCTYPE html>
<html>
<?php
session_start();

if (!isset($_SESSION["logged_in"]) || $_SESSION["logged_in"] !== true) {
    header("Location: index.html");
    exit();
}

if (isset($_POST["logout"])) {
    session_unset();
    session_destroy();

    header("Location: index.php");
    exit();
}
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Administrace</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="editor.css">
    <link rel="stylesheet" href="spravce.css">
    <script src="javascript.js"></script>
    <script src="spravce.js"></script>
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
            <a href="Rezervace.php">Rezervace</a>
            <a href="Editor.php">Editor</a>
            <a href="Spravce.php">Správce</a>

            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i> </a>
        </div>
    </div>

    <div class="header">
        <form method="post" class="bblack">
            <button type="submit" name="logout">Odhlásit se</button>
        </form>
    </div>

    <div class="cont-1">
        <div class="headerH1">
            <h1> editor </h1>
        </div>
    </div>

    <div class="groups">
        <div class="group1">
            <div class="dateEdt">
                <?php
                $startDate = strtotime('today');
                $daysToShow = 30;

                echo '<div class="serviceEdt">';
                echo '<h2>Kalendář</h2>';
                echo '<div class="lineblue"></div>';
                echo '</div>';

                echo '<div id="calendar">';

                echo '<form id="dateForm">';
                echo '<table class="calendar" border="1">';
                echo '<tr><th>Po</th><th>Út</th><th>St</th><th>Čt</th><th>Pá</th></tr>';

                for ($i = 0; $i < $daysToShow; $i++) {
                    $currentDate = strtotime("+$i day", $startDate);
                    $dayOfWeek = date('N', $currentDate);
                    if ($dayOfWeek < 6) {
                        if ($dayOfWeek == 1) {
                            echo '<tr>';
                        }

                        echo '<td>';
                        echo '<button class="buttonCalendar" type="button" onclick="saveDate(\'' . date('j.n.', $currentDate) . '\')">';
                        echo date('j.n.', $currentDate);
                        echo '</button>';
                        echo '</td>';

                        if ($dayOfWeek == 5) {
                            echo '</tr>';
                        }
                    }
                }
                echo '</table>';
                echo '</form>';

                echo '</div>';

                echo '<div id="selectedDateInfo" class="dbinfo"></div>';

                ?>
            </div>
        </div>

        <div class="group2">
            <div class="timeEdt">
                <h2> Nastavení otevíracího času </h2>
                <div class="lineblue"></div>
                <form id="timeForm" method="post" action="">
                    <label for="openingTime">Otevírací čas:</label>
                    <select id="openingTime" name="openingTime">
                        <?php
                        $start = strtotime('07:00');
                        $end = strtotime('20:00');
                        $step = 15 * 60;
                        for ($time = $start; $time <= $end; $time += $step) {
                            echo '<option value="' . date('H:i', $time) . '">' . date('H:i', $time) . '</option>';
                        }
                        ?>
                    </select><br>
                    <label for="closingTime">Zavírací čas:</label>
                    <select id="closingTime" name="closingTime">
                        <?php
                        $start = strtotime('07:00');
                        $end = strtotime('20:00');
                        for ($time = $start; $time <= $end; $time += $step) {
                            echo '<option value="' . date('H:i', $time) . '">' . date('H:i', $time) . '</option>';
                        }
                        ?>
                    </select><br>
                    <button type="submit" class="timeBtn" name="saveChanges">Uložit změny</button>
                </form>

                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "rezervace";
                $conn = new mysqli($servername, $username, $password, $dbname);
                if ($conn->connect_error) {
                    die("Chyba připojení k databázi: " . $conn->connect_error);
                }
                $sql = "SELECT cas_otvirani, cas_zavirani FROM casrozpeti WHERE id=1";
                $result = $conn->query($sql);
                if ($result->num_rows > 0) {
                    echo '<p class="dbinfo">';
                    while ($row = $result->fetch_assoc()) {
                        $openingTime = substr($row["cas_otvirani"], 0, 5);
                        $closingTime = substr($row["cas_zavirani"], 0, 5);
                        echo "Aktuální nastavený čas v databázi: Otevírací čas - " . $openingTime . ", Zavírací čas - " . $closingTime;
                    }
                    echo '</p>';
                } else {
                    echo "Nebyly nalezeny žádné záznamy.";
                }

                $conn->close();
                ?>
                <?php
                if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["saveChanges"])) {
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "rezervace";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Chyba připojení k databázi: " . $conn->connect_error);
                    }

                    $openingTime = $_POST["openingTime"];
                    $closingTime = $_POST["closingTime"];

                    if ($openingTime < $closingTime) {

                        $sql = "UPDATE casrozpeti SET cas_otvirani='$openingTime', cas_zavirani='$closingTime' WHERE id=1";

                        if ($conn->query($sql) === TRUE) {

                            echo "<script>alert('První a poslední čas byly úspěšně aktualizovány v tabulce.');</script>";
                        } else {
                            echo "<script>alert('Chyba při aktualizaci dat: " . $conn->error . "');</script>";
                        }
                    } else {
                        echo "<script>alert(' Čas nebyl aktualizován, nemůžeš mít otevírací čas menší než zavírací.');</script>";
                    }
                    $conn->close();
                }
                ?>
            </div>

            
            <div>
                <?php
                $servername = "localhost";
                $username = "root";
                $password = "";
                $dbname = "rezervace";

                $conn = new mysqli($servername, $username, $password, $dbname);

                if ($conn->connect_error) {
                    die("Connection failed: " . $conn->connect_error);
                }

                function clamp($value, $min, $max)
                {
                    return max($min, min($max, $value));
                }

                $currentRow = isset($_POST['currentRow']) ? intval($_POST['currentRow']) : 1;

                $sql_count = "SELECT COUNT(*) AS total_rows FROM sluzba";
                $result_count = $conn->query($sql_count);
                $totalRows = $result_count->fetch_assoc()['total_rows'];

                $currentRow = clamp($currentRow, 1, $totalRows);

                $sql = "SELECT * FROM sluzba WHERE id = $currentRow";
                $result = $conn->query($sql);

                if ($result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo '<form action="" method="post">';
                    echo '<input type="hidden" name="currentRow" value="' . $currentRow . '">';
                    echo '<div class="serviceEdt">';
                    echo '<h2> Úprava služeb </h2>';
                    echo '<div class="lineblue"></div>';
                    echo '<div>ID: ' . $currentRow . '</div>';

                    echo '<div class="row-buttons">';
                    echo '<form action="" method="post">';
                    echo '<input type="hidden" name="currentRow" value="' . (($currentRow - 1) <= 0 ? $totalRows : ($currentRow - 1)) . '">';
                    echo '<button type="submit">Předchozí</button>';
                    echo '</form>';

                    echo '<form action="" method="post">';
                    echo '<input type="hidden" name="currentRow" value="' . (($currentRow + 1) > $totalRows ? 1 : ($currentRow + 1)) . '">';
                    echo '<button type="submit">Další</button>';
                    echo '</form>';
                    echo '</div>';

                    echo '<div>';
                    echo 'Typ služby: <input type="text" name="typSluzby" value="' . $row["typ_sluzby"] . '"><br>';
                    echo 'Čas služby: <input type="text" name="casSluzby" value="' . $row["casSluzby"] . ' min"><br>';
                    echo 'Cena: <input type="text" name="cena" value="' . $row["cena"] . 'Kč"><br>';
                    echo '<button type="submit" name="update">Upravit</button>';
                    echo '</div>';
                    echo '</form>';

                    echo '</div>';
                } else {
                    echo "0 results";
                }

                if (isset($_POST['update'])) {
                    $newTypSluzby = $_POST['typSluzby'];
                    $newCasSluzby = $_POST['casSluzby'];
                    $newCena = $_POST['cena'];

                    $sql_update = "UPDATE sluzba SET typ_sluzby='$newTypSluzby', casSluzby='$newCasSluzby', cena='$newCena' WHERE id=$currentRow";

                    if ($conn->query($sql_update) === TRUE) {
                        echo "Záznam byl úspěšně aktualizován.";
                    } else {
                        echo "Chyba při aktualizaci záznamu: " . $conn->error;
                    }
                }

                $conn->close();
                ?>
            </div>
        </div>
    </div>
</body>

</html>