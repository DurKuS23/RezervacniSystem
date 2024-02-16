    <?php
    $servername = "localhost";
    $username = "root";
    $password = '';
    $dbname = "rezervace";

    $conn = new mysqli($servername, $username, '', $dbname);
    if ($conn->connect_error) {
        die("Chyba připojení k databázi: " . $conn->connect_error);
    } else {
    }

    $Email = $_POST['email'];
    $Password = $_POST['heslo'];

    $Email = mysqli_real_escape_string($conn, $Email);

    $sql = "SELECT Email, Heslo FROM uzivatele WHERE Email = '$Email'";
    $result = $conn->query($sql);

    $hashHesla = hash("sha256", $Password);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        if ($hashHesla == $row['Heslo']) {
            echo "Přihlášení úspěšné!";
        } else {
            echo "Chybné heslo.";
        }
    } else {
        echo "Uživatel s tímto e-mailem neexistuje.";
    }

    $conn->close();
    ?>