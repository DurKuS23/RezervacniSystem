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
    <script src="calendar.js"></script>
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
        <div class="dateEdt">
            <h2> Výběr data </h2>
            <div class="lineblue"></div>
            <div class="blok">
                <div class="menu4-btn" onclick="toggleCalendar()" id="zvoleneDatum"> Datum </div>
                <div id="menu4" style="display: none;"></div>
            </div>
        </div>

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
                <button type="submit" class="timeBtn">Uložit změny</button>
            </form>
        </div>

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
        if ($_SERVER["REQUEST_METHOD"] == "POST") {
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


        <script>
            function toggleCalendar() {
                var calendar = document.getElementById("menu4");
                if (calendar.style.display === "none") {
                    calendar.style.display = "block";
                } else {
                    calendar.style.display = "none";
                }
            }
            document.addEventListener('DOMContentLoaded', function() {
                const calendar = document.getElementById('menu4');
                const daysOfWeek = ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'];

                const currentDate = new Date();

                const headerRow = document.createElement('div');
                headerRow.classList.add('calendar-row');
                daysOfWeek.forEach(day => {
                    const dayHeader = document.createElement('div');
                    dayHeader.classList.add('day', 'header');
                    dayHeader.textContent = day;
                    headerRow.appendChild(dayHeader);
                });
                calendar.appendChild(headerRow);

                const firstDayOfMonth = new Date(currentDate.getFullYear(), currentDate.getMonth(), 1);
                const daysInMonth = new Date(currentDate.getFullYear(), currentDate.getMonth() + 1, 0).getDate();

                for (let i = 1; i <= daysInMonth; i++) {
                    const currentDay = new Date(currentDate.getFullYear(), currentDate.getMonth(), i);
                    const dayElement = document.createElement('div');
                    dayElement.classList.add('day');
                    dayElement.textContent = i;

                    if (currentDay.getDay() === 0 || currentDay.getDay() === 6) {
                        dayElement.classList.add('sunday');
                    } else {
                        dayElement.classList.add('day');
                    }

                    if (currentDay.toDateString() === currentDate.toDateString()) {
                        dayElement.classList.add('today');
                    }

                    dayElement.addEventListener('click', function() {
                        const selectedDate = new Date(currentDate.getFullYear(), currentDate.getMonth(), i);
                        alert('Selected date: ' + selectedDate.toDateString());
                    });

                    calendar.appendChild(dayElement);
                }
            });
        </script>
</body>

</html>