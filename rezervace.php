<!DOCTYPE html>
<html lang="en">
<?php
session_start();
if (isset($_SESSION['message'])) {
    echo "<script>alert('{$_SESSION['message']}');</script>";
    unset($_SESSION['message']);
}
?>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="styles/rezervace.css">
    <link rel="stylesheet" href="styles/navbar.css">
    <script src="scripst/rezervace.js"></script>
    <script src="scripts/javascript.js"></script>
    <link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script>
        $(document).ready(function() {
            $("#menu4").datepicker({
                minDate: new Date(),
                maxDate: new Date(new Date().getFullYear(), 11, 31),
                onSelect: function(dateText) {
                    var selectedDate = dateText;
                    $("#zvoleneDatum").text("Datum: " + dateText);
                    $.ajax({
                        url: 'scripts/zamestnanci.php',
                        method: 'POST',
                        data: {
                            datum: selectedDate
                        },
                        success: function(response) {
                            $("#operatorList").empty();
                            $("#operatorList").append("<li onclick=\"selectItemCService('Je mi to jedno')\"><a href=\"#\"> Je mi to jedno </a></li>");
                            response.forEach(function(operator) {
                                var operatorName = operator.jmeno;
                                $("#operatorList").append("<li onclick=\"selectItemCService('" + operatorName + "')\"><a href=\"#\">" + operatorName + "</a></li>");
                            });

                            toggleMenuCService();
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });

                    var xhr = new XMLHttpRequest();
                    xhr.open('POST', 'scripts/timegen.php?datum=' + selectedDate, true);

                    xhr.onreadystatechange = function() {
                        if (xhr.readyState === 4) {
                            if (xhr.status === 200) {
                                var times = JSON.parse(xhr.responseText);
                                var liList = '';
                                times.forEach(function(time) {
                                    liList += '<li class="bold" onclick="selectItemTime(\'' + time + '\')"><a href="#">' + time + '</a></li>';
                                });
                                document.getElementById("timeList").innerHTML = liList;
                            } else {
                                console.error(xhr.statusText);
                            }
                        }
                    };

                    xhr.onerror = function() {
                        console.error('Chyba při zpracování požadavku.');
                    };

                    xhr.send();

                    $("#menu4").hide();
                },
                beforeShowDay: function(date) {
                    var day = date.getDay();
                    return [(day !== 6 && day !== 0), ""];
                }
            });
        });


        function toggleCalendar() {
            $("#menu4").toggle();
        }
    </script>
</head>

<body>
    <div class="pozice">
        <div class="topnav" id="myTopnav">
            <a href="index.php">Úvodní stránka</a>
            <?php
            if (!isset($_SESSION['user_email'])) {
                echo '<a href="login.html">Přihlášení</a>';
                echo '<a href="register.html">Registrace</a>';
            } else {
                echo '<a href="scripts/logout.php">Odhlásit se</a>';
            }
            ?>
            <a href="rezervace.php">Rezervace</a>

            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i> </a>
        </div>
    </div>
    <div class="background">
        <div class="centerHeader">
            <h1>Online rezervace </h1>
        </div>

        <div class="bloky-all">
            <div class="bloky-1-2">
                <div class="blok">
                    <div class="menu4-btn" onclick="toggleCalendar()" name="" id="zvoleneDatum">Datum</div>
                    <div id="menu4" style="display: none;">
                    </div>
                </div>


                <br>
                <div class="blok">
                    <div class="menu2-btn" onclick="toggleMenuCService()" id="ZvolenaObsluha">Preferovaná obsluha</div>
                    <div id="menu2">
                        <ul id="operatorList">
                        </ul>
                    </div>
                </div>
            </div>

            <br>

            <div class="bloky-3-4">
                <div class="blok">
                    <div class="menu3-btn" onclick="toggleMenuService()" id="ZvolenaSluzba">Služba</div>
                    <div id="menu3">
                        <ul>
                            <?php
                            require_once('scripts/dbconnect.php');
                            $sql = "SELECT typ_sluzby, casSluzby, cena FROM sluzba";
                            $result = $conn->query($sql);

                            if ($result->num_rows > 0) {
                                while ($row = $result->fetch_assoc()) {
                                    $cas_sluzby = date('i', strtotime($row["casSluzby"])) . "min";
                                    $cas_sluzby = ltrim($cas_sluzby, '0');
                                    $cena = number_format($row["cena"], 0, '', '') . ' Kč';
                                    echo '<li onclick="selectItemService(\'' . $row["typ_sluzby"] . '\')">';
                                    echo '<a href="#">' . $row["typ_sluzby"] . ' - <span class="bold">' . $cas_sluzby . '</span></a>';
                                    echo '<br>';
                                    echo '<a href="#">cena: <span class="bold">' . $cena . '</span></a></li>';
                                }
                            } else {
                                echo "0 results";
                            }
                            ?>
                        </ul>
                    </div>
                </div>


                <br>
                <div class="blok">
                    <div class="menu-btn" onclick="toggleMenuTime()" id="ZvolenyCas">Vyberte čas</div>
                    <div id="menu">
                        <ul id="timeList">

                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>

    </div>

    <div class="background">
        <div class="confirm">
            <form action="scripts/rezervace_zapis.php" method="post">
                <input type="hidden" name="selectedTime" id="selectedTime" value="">
                <input type="hidden" name="selectedService" id="selectedService" value="">
                <input type="hidden" name="selectedCService" id="selectedCService" value="">
                <input type="hidden" name="selectedDate" id="selectedDate" value="">
                <input type="submit" value="POTVRDIT REZERVACI" class="buttonIn" onclick="submitForm()">
            </form>
        </div>
    </div>

    <div class="map">
        <iframe id="widthMap" src="https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d2605.732653578486!2d15.888706476887588!3d49.22459707486512!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x470d423e5579f153%3A0x882e29f2a26985b8!2zU3TFmWVkbsOtIHByxa9teXNsb3bDoSDFoWtvbGEgVMWZZWLDrcSN!5e0!3m2!1scs!2scz!4v1699739297062!5m2!1scs!2scz" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>
    <div class="background">
        <div class="central-gap">
            <div class="left-s">
                <div class="column-oph">
                    <p class="bold"> SPŠT </p>
                    <p> Manž. Curieových 734 </p>
                    <p> 674 01 Třebíč 1</p>
                    <p> Telefon : 645 213 564 </p>
                </div>

            </div>

            <div class="right-s">
                <?php include 'scripts/openClose.php'; ?>
            </div>
        </div>
    </div>

    <script src="scripts/rezervace.js"></script>

    <footer>
        <p> @Daniel Nováček 2023 <a href="spravcelogin.php">Správce </a></p>
    </footer>
</body>

</html>