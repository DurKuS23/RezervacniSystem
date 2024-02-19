function toggleMenuTime() {
    var menu = document.getElementById('menu');
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

function selectItemTime(item) {
    var menu = document.getElementById('menu');
    menu.style.display = 'none';
    var zvolenyCasElement = document.getElementById('ZvolenyCas');
    zvolenyCasElement.textContent = "Zvolený čas: " + item;
}

function toggleMenuCService() {
    var menu = document.getElementById('menu2');
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

function selectItemCService(item) {
    var menu = document.getElementById('menu2');
    menu.style.display = 'none';
    var zvolenyCService = document.getElementById('ZvolenaObsluha');
    zvolenyCService.textContent = "Zvolená obsluha: " + item;
}

function toggleMenuService() {
    var menu = document.getElementById('menu3');
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

function selectItemService(item) {
    var menu = document.getElementById('menu3');
    menu.style.display = 'none';
    var zvolenyItemService = document.getElementById('ZvolenaSluzba');
    zvolenyItemService.textContent = "Zvolená služba: " + item;
}

function toggleDate() {
    var menu = document.getElementById('menu4');
    menu.style.display = (menu.style.display === 'block') ? 'none' : 'block';
}

function selectDateService(item) {
    var menu = document.getElementById('menu4');
    menu.style.display = 'none';
    var zvolenyItemService = document.getElementById('reservationDate');
    zvolenyItemService.textContent = "Zvolené datum: " + item;
}-

function submitForm() {

    var selectedTimeElement = document.getElementById("ZvolenyCas");
    var selectedCServiceElement = document.getElementById("ZvolenaObsluha");
    var selectedServiceElement = document.getElementById("ZvolenaSluzba");
    var selectedDateElement = document.getElementById("zvoleneDatum");

    var selectedTime = selectedTimeElement.textContent.replace("Zvolený čas:", "").trim();
    var selectedService = selectedServiceElement.textContent.replace("Zvolená služba:", "").trim();
    var selectedCService = selectedCServiceElement.textContent.replace("Zvolená obsluha:", "").trim();
    var selectedDate = selectedDateElement.textContent.replace("Datum:", "").trim();

    if (isLoggedIn) {
        if (selectedTime !== "Vyberte čas" && selectedService !== "Služba" && selectedCService !== "Preferovaná obsluha" && selectedDate !== "Datum") {
            document.getElementById("selectedTime").value = selectedTime;
            document.getElementById("selectedCService").value = selectedCService;
            document.getElementById("selectedService").value = selectedService;
            document.getElementById("selectedDate").value = selectedDate;
            alert("Vaše rezervace byla úspěšná !");
        } else {
            alert("Prosím vyplňte všechna pole formuláře.");
        }
    } else {
        alert("Pro provedení rezervace se prosím přihlaste.");
    }
}


