function show(id) {
    const mediaQuery = window.matchMedia("(max-width: 700px)");
    var menu = document.getElementById(id);
    menu.style.right = '0';
    if (mediaQuery.matches){
        menu.style.width = '100%';
    }
}

function hide(id) {
    const mediaQuery = window.matchMedia("(max-width: 1000px)");
    var menu = document.getElementById(id);
    menu.style.right = '-300px';
    menu.style.width = '300px';
}