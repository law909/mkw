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

    document.getElementById('filter-cleaner-button').addEventListener('click', function (e) {
        e.preventDefault();
        document.querySelectorAll('input[type="checkbox"]').forEach((check) => {
            check.checked = false;
        });
    });

    document.querySelectorAll('.filter-apply-button').forEach((applybutton) => {
        applybutton.addEventListener('click', function (e) {
            e.preventDefault();
            let url,
                filterstr = '';
            url = document.location.origin + document.location.pathname;
            checks = document.querySelectorAll('input[type="checkbox"]');
            checks.forEach((check) => {
                if (check.checked) {
                    filterstr = filterstr + check.name + ',';
                }
            });
            if (filterstr !== '') {
                url = url + '?filter=' + filterstr;
            }
            document.location = url;
        })

    })
});