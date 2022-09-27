window.addEventListener('DOMContentLoaded', (event) => {

    let isMobile = window.matchMedia("only screen and (max-width: 760px)").matches;
    if (isMobile) {
        document.getElementById('termekfa-kep').remove();

        document.querySelector('.filter-opener').addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (!document.querySelector('.termek-filter').classList.contains('termek-filter-down')) {
                document.querySelector('.termek-filter').classList.add('termek-filter-down');
                document.querySelectorAll('html, body').forEach(e => e.classList.add('dontscroll'));
            } else {
                document.querySelector('.termek-filter').classList.remove('termek-filter-down');
                document.querySelectorAll('html, body').forEach(e => e.classList.remove('dontscroll'));
            }
        });

        document.querySelector('.filter-closer').addEventListener('click', function (e) {
            e.preventDefault();
            e.stopPropagation();
            if (document.querySelector('.termek-filter').classList.contains('termek-filter-down')) {
                document.querySelector('.termek-filter').classList.remove('termek-filter-down');
                document.querySelectorAll('html, body').forEach(e => e.classList.remove('dontscroll'));
            }
        });
    }
});