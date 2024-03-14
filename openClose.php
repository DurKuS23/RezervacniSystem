<?php
require_once('dbconnect.php');

$sql = "SELECT cas_otvirani, cas_zavirani FROM casrozpeti";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cas_otvirani = date("H:i", strtotime($row["cas_otvirani"]));
        $cas_zavirani = date("H:i", strtotime($row["cas_zavirani"]));

        echo '<div class="row-oph">
                <p class="bold"> Pondělí </p>
                <div>
                    <p>' . $cas_otvirani . ' - ' . $cas_zavirani . '</p>
                </div>
              </div>';
        echo '<div class="row-oph">
              <p class="bold"> Útery </p>
              <div>
              <p>' . $cas_otvirani . ' - ' . $cas_zavirani . '</p>
              </div>
            </div>';
        echo '<div class="row-oph">
            <p class="bold"> Středa </p>
            <div>
            <p>' . $cas_otvirani . ' - ' . $cas_zavirani . '</p>
            </div>
          </div>';
        echo '<div class="row-oph">
          <p class="bold"> Čtvrtek </p>
          <div>
          <p>' . $cas_otvirani . ' - ' . $cas_zavirani . '</p>
          </div>
        </div>';
        echo '<div class="row-oph">
        <p class="bold"> Pátek </p>
        <div>
        <p>' . $cas_otvirani . ' - ' . $cas_zavirani . '</p>
        </div>
      </div>';
        echo '<div class="row-oph">
        <p class="bold"> Sobota </p>
        <p> zavřeno </p>
    </div>';
        echo '<div class="row-oph">
        <p class="bold"> Neděle </p>
        <p> zavřeno </p>
    </div>';
    }
} else {
    echo "0 results";
}
