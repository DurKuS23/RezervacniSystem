<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title></title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="navbar.css">
    <link rel="stylesheet" href="index.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="indexscript.js"></script>
    <script src="javascript.js"></script>
</head>

<body>
    <div class="pozice">
        <div class="topnav" id="myTopnav">
            <a href="index.php">Úvodní stránka</a>
            <?php
            session_start();
            if (!isset($_SESSION['user_email'])) {
                echo '<a href="" onclick="Login()">Přihlášení</a>';
                echo '<a href="" onclick="Register()">Registrace</a>';
            }
            ?>
            <a href="rezervace.php">Rezervace</a>

            <a href="javascript:void(0);" class="icon" onclick="myFunction()">
                <i class="fa fa-bars"></i> </a>
        </div>
    </div>
    <?php

    if (isset($_SESSION['user_email'])) {
        $user_email = $_SESSION['user_email'];
        echo '<div class="header">';
        echo '<form method="post">';
        echo '<button type="submit" name="logout">Odhlásit se</button>';
        echo '</form>';
        echo '</div>';
    } else {
    }

    if (isset($_POST["logout"])) {
        session_unset();
        session_destroy();

        header("Location: index.php");
        exit();
    }
    ?>
    <div class="wavy-line">
        <div class="cont-1">
            <div class="headerH1">
                <h1 class="txt"> Barber Shop</h1>
            </div>

            <div class="headerH3">
                <h3> Tradiční holičství </h3>
            </div>
        </div>

    </div>

    <div class="cont-2">
        <div class="headerH3">
            <h1> zkouska </h1>
        </div>
    </div>
    <footer>
        <p> @Daniel Nováček 2023 <a href="spravcelogin.php">Správce </a></p>
    </footer>
</body>

</html>