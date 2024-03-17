function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}

function generateTimeArray() {
    var openingTime = document.getElementById("openingTime").value;
    var closingTime = document.getElementById("closingTime").value;

    var startTime = new Date("1970-01-01T" + openingTime + ":00");
    var endTime = new Date("1970-01-01T" + closingTime + ":00");

    if (startTime < endTime) {
        var timeArray = [];
        var currentTime = new Date(startTime);
        var step = 15 * 60;
        var timeListHTML = '';

        while (currentTime <= endTime) {
            var timeString = currentTime.getHours() + ":" + ('0' + currentTime.getMinutes()).slice(-2);
            timeListHTML += '<li onclick="selectItemTime(\'' + timeString + '\')"><a href="#">' + timeString + '</a></li>';
            currentTime.setMinutes(currentTime.getMinutes() + 15);
        }

        document.getElementById('timeList').innerHTML = timeListHTML;
    } else {
        alert("Otevírací čas musí být před zavíracím !");
    }
}

function saveDate(selectedDate) {
    var xhttp = new XMLHttpRequest();
    xhttp.onreadystatechange = function () {
        console.log("readyState: " + this.readyState + ", status: " + this.status);
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("selectedDateInfo").innerHTML = this.responseText;
        }
    };
    xhttp.open("POST", "scripts/ajax_script.php", true);
    xhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp.send("selectedDate=" + selectedDate);

    var xhttp2 = new XMLHttpRequest();
    xhttp2.onreadystatechange = function () {
        if (this.readyState == 4 && this.status == 200) {
            document.getElementById("selectedDateOperator").innerHTML = this.responseText;
        }
    };
    xhttp2.open("POST", "scripts/CService.php", true);
    xhttp2.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
    xhttp2.send("selectedDate=" + selectedDate);
}



function sendFormData() {
    var openingTime = document.getElementById("openingTime").value;
    var closingTime = document.getElementById("closingTime").value;

    var xhr = new XMLHttpRequest();
    xhr.open("POST", "scripts/ajax_script.php", true);
    xhr.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

    xhr.onload = function () {
        if (xhr.status >= 200 && xhr.status < 300) {
            document.getElementById("timeInfo").innerHTML = xhr.responseText;
        } else {
            alert("Chyba při aktualizaci dat: " + xhr.statusText);
        }
    };

    xhr.onerror = function () {
        alert("Chyba připojení k serveru");
    };

    xhr.send("openingTime=" + encodeURIComponent(openingTime) + "&closingTime=" + encodeURIComponent(closingTime));
}

document.addEventListener("DOMContentLoaded", function () {
    document.getElementById("timeForm").addEventListener("submit", function (event) {
        event.preventDefault();
        sendFormData();
    });
});



