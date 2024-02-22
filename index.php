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
            <a href="#AboutUsContent"> O nás </a>
            <a href="#priceMenu"> Ceník </a>
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
    <div class="cont-1">
        <div class="headerH1">
            <h1 class="txt"> Barber Shop</h1>
        </div>
        <div class="headerH2">
            <h3> Tradiční holičství </h3>
        </div>
    </div>
    <div class="logo">
        <img src="image/logo.png" alt="logo">
    </div>

    <div class="line"></div>


    <div class="cont-2">
        <div class="headerH2">
            <h2 id="AboutUsContent"> O nás </h2>
            <div class="lineblue"></div>
            <div class="about-us">
                <p> Barber Shop je pánské holičství. Kromě moderních střihů, nabízíme také úpravu vousů, mytí a barvení vlasů či pleťové masky
                    pro muže. Nechejte se hýčkat, ale taktéž si odpočiňte třeba u sklenky vašeho oblíbeného drinku. Nacházíme se v Třebíči.
                    Pokud jste u nás nebyli, tak neváhejte a přijďte se k nám podívat, rádi Vás uvidíme a poskytneme naše služby. Pokud jste již u nás byli,
                    tak se těšíme na Vaší další návštěvu.</p>
            </div>
        </div>
    </div>
    <div class="slideshow-container">
        <div class="slides">
            <img src="image/indexback.png" alt="Image 1">
            <img src="image/backgroundImage3.jpg" alt="Image 2">
        </div>

        <a class="prev" onclick="plusSlides(-1)">&#10094;</a>
        <a class="next" onclick="plusSlides(1)">&#10095;</a>
    </div>

    <div class="line2"></div>

    <div class="cont-3">
        <div class="headerH2">
            <h2 id="priceMenu"> Ceník </h2>
            <div class="lineblue"></div>
        </div>
        <div class="groups">
            <div class="group1">
                <h3> Střih </h3>
                <div class="it">
                    <h4> Klasický střih _______ 430kč</h4>
                    <p> Barber střih,dvojité mytí úprava obočí,úprava kontur,styling.(káva,panák a chlazený nápoj zdarma) </p>
                </div>
                <div class="it">
                    <h4> Klasický střih s masáží _______ 500kč</h4>
                    <p> Barber střih,dvojité mytí úprava obočí,úprava kontur,masáž hlavy přírodními oleji styling.(káva,panák a chlazený nápoj zdarma) </p>
                </div>
            </div>
            <div class="line3"></div>
            <div class="group2">
                <h3> Střih s holením </h3>
                <div class="it">
                    <h4> Klasický střih a masáž hlavy, holení _______ 1000kč</h4>
                    <p> Barber střih,dvojité mytí úprava obočí,úprava kontur,masáž hlavy přírodními oleji styling.
                        Procedura zahrnuje napářku horkým ručníkem, speciální olej, holení ošetření po oholení.(Káva,panák a chlazený nápoj zdarma) </p>
                </div>
                <div class="it">
                    <h4> Klasický střih a holení _______ 900kč</h4>
                    <p> Barber střih, dvojité mytí úprava obočí,úprava kontur, styling.
                        Procedura zahrnuje napářku horkým ručníkem, speciální olej, holenÍ, ošetření po oholení.(Káva,panák a chlazený nápoj zdarma) </p>
                </div>
            </div>
            <div class="line3"></div>
            <div class="group3">
                <h3> Holení a úprava vousů </h3>
                <div class="it">
                    <h4> Holení ,,Hot Towel'' _______ 500kč</h4>
                    <p> Procedura zahrnuje napářku horkým ručníkem, speciální olej, holení, ošetření po oholení. (Káva,panák a chlazený nápoj zdarma) </p>
                </div>
                <div class="it">
                    <h4> Úprava vousů a kníru _______ 250kč</h4>
                </div>
            </div>
        </div>
    </div>

    <footer>
        <p> @Daniel Nováček 2023 <a href="spravcelogin.php">Správce </a></p>
    </footer>
    <script src="script.js"></script>
</body>

</html>