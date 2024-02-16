function loadReservations() {
    var xhr = new XMLHttpRequest();
    var url = "spravce.php";
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

/*
function deleteReservation(button) {
    var reservationId = button.getAttribute('data-id');
    if (confirm("Opravdu chcete smazat tuto rezervaci?")) {
        var xhr = new XMLHttpRequest();
        xhr.open("POST", "spravce.php", true);
        xhr.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
        xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
                alert(xhr.responseText);
                location.reload();
            }
        };
        xhr.send("reservation_id=" + reservationId);
    }
}
*/

function showConfirmation() {
    var response = confirm("Opravdu chcete smazat rezervaci ?");
    if (response == true) {
        window.location.href = "spravce.php?response=ano";
    } else {
        window.location.href = "spravce.php?response=ne";
    }
}