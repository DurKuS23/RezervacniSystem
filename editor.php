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
        <div class="timeEdt">
            <h2> Nastavení otevíracího času </h2>
            <div class="lineblue"></div>
            <form id="timeForm">
                <label for="openingTime">Otevírací čas:</label>
                <select id="openingTime">
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
                <select id="closingTime">
                    <?php
                    $start = strtotime('07:00');
                    $end = strtotime('20:00');
                    for ($time = $start; $time <= $end; $time += $step) {
                        echo '<option value="' . date('H:i', $time) . '">' . date('H:i', $time) . '</option>';
                    }
                    ?>
                </select><br>
                <button type="button" class="timeBtn" onclick="generateTimeArray()">Vygenerovat pole časů</button>
            </form>
        </div>

        <div class="obsluhaEdt">
            <h2> Vyberte obsluhu pro den </h2>
            <div class="lineblue"></div>
            <div class="obsluha">
                <form id="staffForm" method="post">
                    <?php
                    $servername = "localhost";
                    $username = "root";
                    $password = "";
                    $dbname = "rezervace";

                    $conn = new mysqli($servername, $username, $password, $dbname);

                    if ($conn->connect_error) {
                        die("Chyba připojení k databázi: " . $conn->connect_error);
                    }

                    $sql = "SELECT * FROM operator WHERE id != (SELECT MIN(id) FROM operator)";
                    $result = $conn->query($sql);

                    if ($result->num_rows > 0) {
                        echo '<label for="selectStaff1">Vyberte prvního zaměstnance:</label>';
                        echo '<select id="selectStaff1" name="staff1" onchange="updateSecondStaff(this.value)">';
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row["id"] . '">' . $row["jmeno"] . '</option>';
                        }
                        echo '</select><br>';

                        $result->data_seek(0);
                        echo '<label for="selectStaff2">Vyberte druhého zaměstnance:</label>';
                        echo '<select id="selectStaff2" name="staff2" onchange="updateFirstStaff(this.value)">';
                        while ($row = $result->fetch_assoc()) {
                            echo '<option value="' . $row["id"] . '">' . $row["jmeno"] . '</option>';
                        }
                        echo '</select><br>';

                        echo '<input type="submit" name="submit" class="obsluhabtn" value="Potvrdit výběr">';
                    } else {
                        echo "Žádná data k zobrazení";
                    }

                    $conn->close();

                    if (isset($_POST['submit'])) {
                        $selectedStaff1 = $_POST['staff1'];
                        $selectedStaff2 = $_POST['staff2'];

                        if ($selectedStaff1 == $selectedStaff2) {
                            echo "Vyberte prosím dva různé zaměstnance.";
                        } else {
                            $conn = new mysqli($servername, $username, $password, $dbname);
                            if ($conn->connect_error) {
                                die("Chyba připojení k databázi: " . $conn->connect_error);
                            }

                            $sql = "SELECT jmeno FROM operator WHERE id IN ($selectedStaff1, $selectedStaff2)";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                $row = $result->fetch_assoc();
                                echo "<p id='operator1'>První vybraný zaměstnanec: " . $row["jmeno"] . "</p>";
                                $row = $result->fetch_assoc();
                                echo "<p id='operator2'>Druhý vybraný zaměstnanec: " . $row["jmeno"] . "</p>";
                            } else {
                                echo "Žádná data k zobrazení";
                            }
                        }
                    }
                    ?>
                    <label for="newStaff" class="newStaffT">Přidat nového zaměstnance:</label>
                    <input type="text" id="newStaff" name="newStaff"><br>
                    <button type="button" class="NewStaffM" onclick="addStaff()">Přidat zaměstnance</button>
                </form>
            </div>
            <p id="operator1"></p>
            <p id="operator2"></p>
        </div>

        <script>
            function generateTimeArray() {
                var openingTime = document.getElementById("openingTime").value;
                var closingTime = document.getElementById("closingTime").value;

                var startTime = new Date("1970-01-01T" + openingTime + ":00");
                var endTime = new Date("1970-01-01T" + closingTime + ":00");

                if (startTime < endTime) {
                    var timeArray = [];
                    var currentTime = new Date(startTime);
                    var step = 15 * 60;

                    while (currentTime <= endTime) {
                        timeArray.push(currentTime.getHours() + ":" + ('0' + currentTime.getMinutes()).slice(-2));
                        currentTime.setMinutes(currentTime.getMinutes() + 15);
                    }

                    console.log(timeArray);
                } else {
                    alert("Otevírací čas musí být před zavíracím !");
                }
            }

            function addStaff() {
                var newStaff = document.getElementById("newStaff").value;
                var selectStaff1 = document.getElementById("selectStaff1");
                var selectStaff2 = document.getElementById("selectStaff2");

                var option1 = document.createElement("option");
                option1.text = newStaff;
                option1.value = newStaff;

                var option2 = document.createElement("option");
                option2.text = newStaff;
                option2.value = newStaff;

                selectStaff1.add(option1);
                selectStaff2.add(option2);
            }

            function updateSecondStaff(value) {
                var selectStaff2 = document.getElementById("selectStaff2");
                var option = selectStaff2.querySelector('option[value="' + value + '"]');
                if (option) {
                    selectStaff2.removeChild(option);
                } else {
                    var selectStaff1 = document.getElementById("selectStaff1");
                    var option1 = selectStaff1.querySelector('option[value="' + value + '"]');
                    if (option1) {
                        var newOption = document.createElement("option");
                        newOption.text = option1.text;
                        newOption.value = option1.value;
                        selectStaff2.add(newOption);
                    }
                }
            }

            function updateFirstStaff(value) {
                var selectStaff1 = document.getElementById("selectStaff1");
                var option = selectStaff1.querySelector('option[value="' + value + '"]');
                if (option) {
                    selectStaff1.removeChild(option);
                } else {
                    var selectStaff2 = document.getElementById("selectStaff2");
                    var option2 = selectStaff2.querySelector('option[value="' + value + '"]');
                    if (option2) {
                        var newOption = document.createElement("option");
                        newOption.text = option2.text;
                        newOption.value = option2.value;
                        selectStaff1.add(newOption);
                    }
                }
            }
        </script>
</body>

</html>