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
    <h1> editor </h1>

    <form action="rezervace.php" method="post">
        <label for="cas">Čas:</label><br>
        <input type="text" id="cas" name="cas"><br>
        <label for="oblusha">Obluha:</label><br>
        <input type="text" id="oblusha" name="oblusha"><br>
        <label for="datum">Datum:</label><br>
        <input type="date" id="datum" name="datum"><br>
        <label for="sluzba">Služba:</label><br>
        <input type="text" id="sluzba" name="sluzba"><br>
        <input type="submit" value="Odeslat">
    </form>

    <form id="timeForm">
        <label for="openingTime">Otevírací čas:</label>
        <select id="openingTime">
            <!-- Generování možností pro výběr otevíracího času -->
            <?php
            // Počáteční čas (7:00)
            $start = strtotime('07:00');
            // Koncový čas (20:00)
            $end = strtotime('20:00');
            // Krok (15 minut)
            $step = 15 * 60;
            for ($time = $start; $time <= $end; $time += $step) {
                echo '<option value="' . date('H:i', $time) . '">' . date('H:i', $time) . '</option>';
            }
            ?>
        </select><br>
        <label for="closingTime">Zavírací čas:</label>
        <select id="closingTime">
            <?php
            $start = strtotime('07:00');
            $end = strtotime('20:00');
            for ($time = $start; $time <= $end; $time += $step) {
                echo '<option value="' . date('H:i', $time) . '">' . date('H:i', $time) . '</option>';
            }
            ?>
        </select><br>
        <button type="button" onclick="generateTimeArray()">Vygenerovat pole časů</button>
    </form>

    <script>
        function generateTimeArray() {
            var openingTime = document.getElementById("openingTime").value;
            var closingTime = document.getElementById("closingTime").value;
            var timeArray = [];

            var startTime = new Date("1970-01-01T" + openingTime + ":00");
            var endTime = new Date("1970-01-01T" + closingTime + ":00");

            var currentTime = new Date(startTime);
            while (currentTime <= endTime) {
                timeArray.push(currentTime.getHours() + ":" + ('0' + currentTime.getMinutes()).slice(-2));
                currentTime.setMinutes(currentTime.getMinutes() + 15);
            }

            console.log(timeArray);
        }
    </script>
</body>

</html>