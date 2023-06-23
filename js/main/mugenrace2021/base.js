(() => {

    let isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;

    function resizeSpacer() {
        let navspacer = document.querySelector('.nav-spacer');
        if (navspacer) {
            if (isMobile) {
                let ref = document.querySelector('.termek-filter');
                if (ref) {
                    navspacer.style.height = (ref.offsetTop - ref.offsetHeight) + 'px';
                } else {
                    ref = document.querySelector('.header');
                    navspacer.style.height = ref.offsetHeight + 'px';
                }
            } else {
                let referenceHeight = document.querySelector('.header').offsetHeight;
                navspacer.style.height = referenceHeight + 'px';
            }
        }
    }

    document.addEventListener("alpine:init", () => {
        Alpine.store("header", {
            termekdb: 0
        });
    });

    document.addEventListener('DOMContentLoaded', (event) => {

        document.querySelector('.nav-menu').addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector('.menucontainer').classList.add('menucontainer-down');
            document.querySelectorAll('html, body').forEach(e => e.classList.add('dontscroll'));
        });

        document.querySelector('.menu-close').addEventListener('click', function (e) {
            e.preventDefault();
            document.querySelector('.menucontainer').classList.remove('menucontainer-down');
            document.querySelectorAll('html, body').forEach(e => e.classList.remove('dontscroll'));
        });

        document.querySelector('.nav-lang').addEventListener('change', function (e) {
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

        resizeSpacer();
    });

    let timeout = false;
    window.addEventListener('resize', (event) => {

        clearTimeout(timeout);
        timeout = setTimeout(resizeSpacer, 50);

    });
})();