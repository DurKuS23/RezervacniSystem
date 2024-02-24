function myFunction() {
    var x = document.getElementById("myTopnav");
    if (x.className === "topnav") {
        x.className += " responsive";
    } else {
        x.className = "topnav";
    }
}

function Register() {
    var width = 540;
    var height = 450;
    var left = (window.innerWidth - width) / 2;
    var top = (window.innerHeight - height) / 2;
    var options = 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top;

    var newWindow = window.open('register.html', 'centeredWindow', options);


    var closeButton = document.getElementById('end');

    closeButton.addEventListener('click', function () {
        newWindow.close();
    });
    newWindow.document.body.appendChild(closeButton);
}

function Login() {
    var width = 540;
    var height = 450;
    var left = (window.innerWidth - width) / 2;
    var top = (window.innerHeight - height) / 2;
    var options = 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top;

    var newWindow = window.open('login.html', 'centeredWindow', options);
}

function closeWindow() {
    window.opener.closeWindow();
    window.close();
}


function spravce() {
    var width = 540;
    var height = 450;
    var left = (window.innerWidth - width) / 2;
    var top = (window.innerHeight - height) / 2;
    var options = 'width=' + width + ',height=' + height + ',left=' + left + ',top=' + top;

    var newWindow = window.open('spravcelogin.php', 'centeredWindow', options);
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