
function showConfirmation(reservationId) {
    var response = confirm("Opravdu chcete smazat rezervaci?");
    if (response == true) {
        window.location.href = "spravce.php?delete_reservation=true&reservation_id=" + reservationId;
    }
}

function editReservation(reservationId) {
    console.log("Editace rezervace pro ID: " + reservationId);
    window.location.href = "editace_rezervace.php?id=" + reservationId;
}

function loadReservations() {
    var xhr = new XMLHttpRequest();
    var url = "scripts/spravce.php";
    xhr.open("POST", url, true);
    xhr.onreadystatechange = function () {
        if (xhr.readyState == 4 && xhr.status == 200) {
            var reservations = JSON.parse(xhr.responseText);
            for (var i = 1; i <= reservations.length; i++) {
                var resRowId = "res-row-" + i;
                var resRow = document.getElementById(resRowId);

                resRow.innerHTML = "<p>Reservation ID: " + reservations[i].reservation_id + "</p>" +
                    "<p>Operator Name: " + reservations[i].operator_name + "</p>" +
                    "<p>Date: " + reservations[i].datum_sluzby + "</p>" +
                    "<p>Time: " + reservations[i].cas_sluzby + "</p>" +
                    "<p>Service Name: " + reservations[i].service_name + "</p>";
            }
        }
    };
    xhr.send();
}
window.onload = loadReservations;
