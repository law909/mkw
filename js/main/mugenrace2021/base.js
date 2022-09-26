{
    document.querySelector('.nav-menu').addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector('.menucontainer').classList.add('menucontainer-down');
        document.querySelectorAll('html, body').forEach(e => e.classList.add('dontscroll'));
    });
    document.querySelector('.menu-close').addEventListener('click', function(e) {
        e.preventDefault();
        document.querySelector('.menucontainer').classList.remove('menucontainer-down');
        document.querySelectorAll('html, body').forEach(e => e.classList.remove('dontscroll'));
    });

}
