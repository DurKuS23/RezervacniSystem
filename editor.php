<!DOCTYPE html>
<html>
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
?>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Administrace</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <link rel="stylesheet" href="styles/editor.css">
    <link rel="stylesheet" href="styles/spravce.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
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

    <div class="cont-1">
        <div class="headerH1">
            <h1> editor </h1>
            <div class="lineblue"></div>
        </div>
    </div>

    <div class="groups">
        <div class="group1">
            <div class="it1">
                <div class="dateEdt">
                    <?php

                    require_once('scripts/dbconnect.php');

                    $sql = "SELECT datum FROM pracovnidata";
                    $result = $conn->query($sql);

                    $existingDates = array();
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            $existingDates[] = date('d.m.', strtotime($row["datum"]));
                        }
                    }

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

                    $firstWorkday = strtotime('next Monday', $startDate);

                    for ($i = 0; $i < $daysToShow; $i++) {
                        $currentDate = strtotime("+$i day", $firstWorkday);
                        $formattedDate = date('d.m.', $currentDate);
                        $dayOfWeek = date('N', $currentDate);

                        if ($dayOfWeek < 6) {
                            if ($dayOfWeek == 1) {
                                echo '<tr>';
                            }

                            echo '<td>';
                            $buttonClass = in_array($formattedDate, $existingDates) ? 'buttonCalendar existingDate' : 'buttonCalendar';
                            echo '<button class="' . $buttonClass . '" type="button" onclick="saveDate(\'' . $formattedDate . '\')">';
                            echo $formattedDate;
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

            <div class="it1">
                <div class="CServiceEdt">
                    <h2> Výběr zaměstnanců </h2>
                    <div class="lineblue"></div>
                    <div id="result"></div>
                    <div id="selectedDateOperator" class="dbinfo"> Vyberte datum </div>
                    <?php
                    require_once('scripts/dbconnect.php');

                    $sql = "SELECT id, jmeno FROM operator WHERE id <> 1";
                    $result = $conn->query($sql);

                    echo  '<div class="gap">';
                    if ($result->num_rows > 0) {
                        echo "<select name='operator1' id='operator1'>";
                        while ($row = $result->fetch_assoc()) {
                            echo "<option value='" . $row["id"] . "'>" . $row["jmeno"] . "</option>";
                        }
                        echo "</select>";
                    } else {
                        echo "Žádní operátoři nebyli nalezeni.";
                    }
                    echo "<select name='operator2' id='operator2' style='display:none;'></select>";
                    echo '</div>';
                    ?>

                    <button class="serviceBtn" type="submit" id="submitBtn">Potvrdit</button>
                    <div class="dbinfo" id="selectedOperators"></div>
                </div>

                <script>
                    $(document).ready(function() {
                        $("#submitBtn").click(function() {
                            var selectedDate = $("#selectedDateInfo").text().trim().replace("Vybrané datum: ", "");
                            var operator1 = $("#operator1").val();
                            var operator2 = $("#operator2").val();

                            $.ajax({
                                type: "POST",
                                url: "scripts/CService.php",
                                data: {
                                    selectedDate: selectedDate,
                                    operator1: operator1,
                                    operator2: operator2,
                                    submitBtn: true
                                },
                                success: function(response) {
                                    if (response.trim() === "success") {
                                        alert("Pro datum " + selectedDate + " byli zvoleni operátoři: " + operator1 + " a " + operator2);
                                        location.reload();
                                    }
                                }
                            });
                        });
                    });

                    document.getElementById("operator1").addEventListener("change", function() {
                        var operator1Value = this.value;
                        var operator2Select = document.getElementById("operator2");

                        var xhr = new XMLHttpRequest();
                        xhr.onreadystatechange = function() {
                            if (xhr.readyState == 4 && xhr.status == 200) {
                                operator2Select.innerHTML = xhr.responseText;
                                operator2Select.style.display = "block";
                            }
                        };
                        xhr.open("GET", "scripts/CService.php?selectedOperator=" + operator1Value, true)
                        xhr.send();
                    });

                    document.getElementById("submitBtn").addEventListener("click", function(event) {
                        var selectedDateInfo = document.getElementById("selectedDateInfo").textContent;
                        var selectedDate = selectedDateInfo.replace("Vybrané datum: ", "");

                        if (selectedDate === "") {
                            alert("Prosím vyberte datum.");
                            event.preventDefault();
                            return;
                        }

                        var operator1 = document.getElementById("operator1").value;
                        var operator2 = document.getElementById("operator2").value;

                        if (operator1 === "" || operator2 === "") {
                            alert("Prosím vyberte oba zaměstnance.");
                            event.preventDefault();
                            return;
                        }

                        var data = {
                            selectedDate: selectedDate,
                            operator1: operator1,
                            operator2: operator2
                        };
                        fetch('scripts/CService.php', {
                                method: 'POST',
                                headers: {
                                    'Content-Type': 'application/json',
                                },
                                body: JSON.stringify(data),
                            })
                            .then(response => {
                                if (!response.ok) {
                                    throw new Error('Nepodařilo se odeslat data.');
                                }
                                return response.text();
                            })
                            .then(data => {})
                            .catch(error => {
                                console.error('Chyba:', error);
                            });

                        event.preventDefault();
                    });
                </script>




            </div>
        </div>

        <div class="group2">

            <div class="it1">
                <div class="timeEdt">
                    <h2> Nastavení otevíracího času </h2>
                    <div class="lineblue"></div>
                    <form id="timeForm" method="post" action="">
                        <div class="row-form">
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
                            </select>
                        </div>
                        <br>
                        <div class="row-form">
                            <label for="closingTime">Zavírací čas:</label>
                            <select id="closingTime" name="closingTime">
                                <?php
                                $start = strtotime('07:00');
                                $end = strtotime('20:00');
                                for ($time = $start; $time <= $end; $time += $step) {
                                    echo '<option value="' . date('H:i', $time) . '">' . date('H:i', $time) . '</option>';
                                }
                                ?>
                            </select>
                        </div>
                        <br>
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
                            xhttp.open("POST", "scripts/ajax_script.php", true);
                            xhttp.send();
                        }
                        updateTime();
                    </script>

                    <?php
                    require_once('scripts/dbconnect.php');
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
                    ?>
                </div>
            </div>

            <div class="it1">
                <div class="serviceEdt">
                    <?php
                    require_once("scripts/dbconnect.php");

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
                            xhr.open("POST", "scripts/update_service.php", true);
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
                            xhr.open("POST", "scripts/load_service.php", true);
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