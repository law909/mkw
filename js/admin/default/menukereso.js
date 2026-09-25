/**
 * Ctrl+K (Macen Cmd+K) menükereső a modern témához. A menüpontokat az oldalsáv kirajzolt
 * menüjéből olvassa, a legutóbb megnyitott képernyőket a böngésző localStorage-e tárolja.
 */
const menukereso = (function () {
    const LEGUTOBBI_KULCS = 'mkw.menukereso.legutobbi';
    const LEGUTOBBI_DB = 6;
    const TALALAT_DB = 12;

    let $reteg = null;
    let $input = null;
    let $lista = null;
    let pontok = [];
    let talalatok = [];
    let aktiv = 0;

    const normalize = (s) => String(s).normalize('NFD').replace(/[̀-ͯ]/g, '').toLowerCase().trim();

    function olvasLegutobbi() {
        try {
            return JSON.parse(window.localStorage.getItem(LEGUTOBBI_KULCS)) || [];
        } catch (e) {
            return [];
        }
    }

    function mentLegutobbi(href) {
        try {
            const lista = [href, ...olvasLegutobbi().filter((h) => h !== href)].slice(0, LEGUTOBBI_DB);
            window.localStorage.setItem(LEGUTOBBI_KULCS, JSON.stringify(lista));
        } catch (e) {
            // privát ablakban a localStorage tiltott lehet; a kereső enélkül is működik
        }
    }

    function gyujtPontok() {
        const csoportnevek = {};
        $('.js-menucsoporttoggle').each(function () {
            csoportnevek[$(this).data('mcsid')] = $(this).find('.ui-jqgrid-title').text().trim();
        });
        // a „Gyakran használt" szakasz másolatai nélkül, különben minden találat kétszer jönne
        return $('.menupont').not('.js-gyakranhasznalt .menupont').toArray().map((a) => {
            const nev = $(a).text().trim();
            const csoport = csoportnevek[$(a).closest('.js-menucsoport').data('mcsid')] || '';
            return {elem: a, href: a.getAttribute('href') || '', nev, csoport, kereso: normalize(nev + ' ' + csoport), nevnorm: normalize(nev)};
        }).filter((p) => p.nev);
    }

    function keres(szoveg) {
        const q = normalize(szoveg);
        if (!q) {
            const legutobbi = olvasLegutobbi();
            return legutobbi.map((href) => pontok.find((p) => p.href === href)).filter(Boolean);
        }
        const szavak = q.split(/\s+/);
        return pontok
            .filter((p) => szavak.every((sz) => p.kereso.includes(sz)))
            .map((p) => {
                let pont = 3;
                if (p.nevnorm.startsWith(q)) {
                    pont = 0;
                } else if (p.nevnorm.split(/\s+/).some((w) => w.startsWith(szavak[0]))) {
                    pont = 1;
                } else if (p.nevnorm.includes(szavak[0])) {
                    pont = 2;
                }
                return {p, pont};
            })
            .sort((a, b) => a.pont - b.pont || a.p.nev.length - b.p.nev.length || a.p.nev.localeCompare(b.p.nev, 'hu'))
            .slice(0, TALALAT_DB)
            .map((x) => x.p);
    }

    function rajzol() {
        talalatok = keres($input.val());
        aktiv = Math.min(aktiv, Math.max(talalatok.length - 1, 0));
        $lista.empty();
        if (!talalatok.length) {
            $lista.append($('<li class="menukereso-ures">').text($input.val() ? 'Nincs ilyen menüpont.' : 'Kezdj el gépelni a kereséshez.'));
            return;
        }
        if (!$input.val()) {
            $lista.append($('<li class="menukereso-cim">').text('Legutóbbi képernyők'));
        }
        talalatok.forEach((p, i) => {
            $('<li class="menukereso-talalat">')
                .toggleClass('menukereso-aktiv', i === aktiv)
                .attr('data-index', i)
                .append($('<span class="menukereso-nev">').text(p.nev))
                .append($('<span class="menukereso-csoport">').text(p.csoport))
                .appendTo($lista);
        });
    }

    function valaszt(i) {
        const p = talalatok[i];
        if (!p) {
            return;
        }
        bezar();
        // a js- kezelős menüpontok (pl. médiatár) nem navigálnak, azokat a saját kattintásuk intézi
        if (!p.href || p.href === '#') {
            $(p.elem).trigger('click');
            return;
        }
        mentLegutobbi(p.href);
        window.location.href = p.href;
    }

    function nyit() {
        pontok = gyujtPontok();
        aktiv = 0;
        $reteg.prop('hidden', false);
        $input.val('').trigger('focus');
        rajzol();
    }

    function bezar() {
        $reteg.prop('hidden', true);
    }

    function init() {
        if (!$('body').hasClass('modernui') || !$('.menupont').length) {
            return;
        }
        $reteg = $('<div class="menukereso" hidden>' +
            '<div class="menukereso-panel" role="dialog" aria-label="Keresés a menüben">' +
            '<input class="menukereso-input" type="search" placeholder="Keresés a menüben…" autocomplete="off">' +
            '<ul class="menukereso-lista"></ul>' +
            '<div class="menukereso-sugo"><kbd>↑</kbd><kbd>↓</kbd> választás <kbd>Enter</kbd> megnyitás <kbd>Esc</kbd> bezárás</div>' +
            '</div></div>').appendTo('body');
        $input = $reteg.find('.menukereso-input');
        $lista = $reteg.find('.menukereso-lista');

        const aktualis = $('.menupont-aktiv').attr('href');
        if (aktualis && aktualis !== '#') {
            mentLegutobbi(aktualis);
        }
        if (/Mac|iPhone|iPad/.test(navigator.platform)) {
            $('.js-menukereso-billentyu').text('⌘ K');
        }

        $('.js-menukereso-nyit').on('click', nyit);
        $(document).on('keydown', (e) => {
            if ((e.ctrlKey || e.metaKey) && !e.altKey && e.key.toLowerCase() === 'k') {
                e.preventDefault();
                $reteg.prop('hidden') ? nyit() : bezar();
            }
        });
        $reteg.on('mousedown', (e) => {
            if (e.target === $reteg[0]) {
                bezar();
            }
        });
        $input.on('input', () => {
            aktiv = 0;
            rajzol();
        });
        $input.on('keydown', (e) => {
            if (e.key === 'ArrowDown' || e.key === 'ArrowUp') {
                e.preventDefault();
                const n = talalatok.length;
                aktiv = n ? (aktiv + (e.key === 'ArrowDown' ? 1 : -1) + n) % n : 0;
                rajzol();
            } else if (e.key === 'Enter') {
                e.preventDefault();
                valaszt(aktiv);
            } else if (e.key === 'Escape') {
                bezar();
            }
        });
        $lista.on('mousemove', '.menukereso-talalat', function () {
            const i = Number($(this).attr('data-index'));
            if (i !== aktiv) {
                aktiv = i;
                $lista.children('.menukereso-talalat').each(function () {
                    $(this).toggleClass('menukereso-aktiv', Number($(this).attr('data-index')) === aktiv);
                });
            }
        });
        $lista.on('click', '.menukereso-talalat', function () {
            valaszt(Number($(this).attr('data-index')));
        });
    }

    return {init};
})();

$(document).ready(menukereso.init);
