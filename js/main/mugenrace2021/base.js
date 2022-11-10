document.addEventListener("alpine:init", () => {
    Alpine.store("header", {
        termekdb: 0
    });
});

document.addEventListener('DOMContentLoaded', (event) => {

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
    document.querySelector('.nav-lang').addEventListener('change', function(e) {
        const formdata = new FormData();
        formdata.append('locale', e.target.value);
        fetch('/setlocale', {
            method: 'POST',
            body: formdata
        })
        .then((response) => {
            window.location.reload();
        });
    });

});
