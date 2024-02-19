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

