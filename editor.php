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
        <form method="post">
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

                echo '<div class="calendarEdt">';
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

            <div class="it1">
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

                    <div id="timeInfo"></div>

                    <script>
                        function updateTime() {
                            var xhttp = new XMLHttpRequest();
                            xhttp.onreadystatechange = function() {
                                if (this.readyState == 4 && this.status == 200) {
                                    document.getElementById("timeInfo").innerHTML = this.responseText;
                                }
                            };
                            xhttp.open("POST", "ajax_script.php", true);
                            xhttp.send();
                        }
                        updateTime();
                    </script>

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
                        while ($row = $result->fetch_assoc()) {
                            $openingTime = substr($row["cas_otvirani"], 0, 5);
                            $closingTime = substr($row["cas_zavirani"], 0, 5);
                            echo "<p class='dbinfo'> Aktuální nastavený čas v databázi: Otevírací čas -  $openingTime, Zavírací čas - $closingTime </p>";
                        }
                    } else {
                        echo "Nebyly nalezeny žádné záznamy.";
                    }
                    $conn->close();
                    ?>
                </div>
            </div>

            <div class="it1">
                <div class="serviceEdt">
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

                    $sql = "SELECT * FROM sluzba WHERE id = ?";
                    $stmt = $conn->prepare($sql);
                    $stmt->bind_param("i", $currentRow);
                    $stmt->execute();
                    $result = $stmt->get_result();

                    if ($result->num_rows > 0) {
                        $row = $result->fetch_assoc();
                        echo '<div id="serviceForm">';
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
                        echo '</div>';
                    } else {
                        echo "0 results";
                    }

                    $conn->close();
                    ?>

                    <script>
                        function prevService() {
                            var currentRow = parseInt(document.getElementById('currentRow').value);
                            currentRow = currentRow - 1 <= 0 ? <?php echo $totalRows; ?> : currentRow - 1;
                            loadService(currentRow);
                        }

                        function nextService() {
                            var currentRow = parseInt(document.getElementById('currentRow').value);
                            currentRow = currentRow + 1 > <?php echo $totalRows; ?> ? 1 : currentRow + 1;
                            loadService(currentRow);
                        }

                        function updateService() {
                            var updateID = document.getElementById('updateID').value;
                            var typSluzby = document.getElementById('typSluzby').value;
                            var casSluzby = document.getElementById('casSluzby').value;
                            var cena = document.getElementById('cena').value;

                            var xhr = new XMLHttpRequest();
                            xhr.open("POST", "update_service.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState == 4 && xhr.status == 200) {
                                    alert(xhr.responseText);
                                }
                            };
                            xhr.send("updateID=" + updateID + "&typSluzby=" + typSluzby + "&casSluzby=" + casSluzby + "&cena=" + cena);
                        }

                        function loadService(currentRow) {
                            var xhr = new XMLHttpRequest();
                            xhr.open("POST", "load_service.php", true);
                            xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
                            xhr.onreadystatechange = function() {
                                if (xhr.readyState == 4 && xhr.status == 200) {
                                    document.getElementById("serviceForm").innerHTML = xhr.responseText;
                                }
                            };
                            xhr.send("currentRow=" + currentRow);
                        }
                    </script>
                </div>
            </div>

        </div>
    </div>
</body>

</html>