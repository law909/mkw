// Lampion katalógus: mobil menü és a terméklap képváltója. Nincs kosár, nincs több.

document.addEventListener('DOMContentLoaded', () => {
    const menugomb = document.querySelector('.js-menugomb');
    const menu = document.getElementById('fomenu');
    if (menugomb && menu) {
        menugomb.addEventListener('click', () => {
            const nyitva = menu.classList.toggle('nyitva');
            menugomb.setAttribute('aria-expanded', nyitva ? 'true' : 'false');
        });
    }

    const fokep = document.querySelector('.js-fokep');
    const valtok = document.querySelectorAll('.js-kepvalto');
    if (fokep && valtok.length) {
        valtok[0].setAttribute('aria-current', 'true');
        valtok.forEach((gomb) => {
            gomb.addEventListener('click', () => {
                fokep.src = gomb.dataset.kep;
                valtok.forEach((g) => g.removeAttribute('aria-current'));
                gomb.setAttribute('aria-current', 'true');
            });
        });
    }
});
