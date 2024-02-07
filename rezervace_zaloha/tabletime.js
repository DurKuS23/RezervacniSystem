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
