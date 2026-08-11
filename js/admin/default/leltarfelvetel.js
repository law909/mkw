/**
 * Leltár felvételi lista vonalkódos rögzítése.
 *
 * A vonalkód mező a bolti eladáséval azonos: 4 karaktertől név/cikkszám autocomplete, Enterre
 * vonalkódos keresés. A beolvasás a leltár termék+változat sorát keresi meg
 * és növeli eggyel a tény mennyiségét; az első beolvasásnál a sor létre is jön.
 */
var leltarfelvetel = (function ($) {

    const URL_FINDTERMEK = '/admin/leltarfelvetel/findtermek';
    const URL_KERESES = '/admin/leltarfelvetel/kereses';
    const URL_GETTERMEK = '/admin/leltarfelvetel/gettermek';
    const URL_ADDTETEL = '/admin/leltarfelvetel/addtetel';
    const URL_SETTETEL = '/admin/leltarfelvetel/settetel';
    const URL_DELTETEL = '/admin/leltarfelvetel/deltetel';

    // Jelzi, hogy az utolsó Enter az autocomplete listából való választás volt-e (ilyenkor
    // nem vonalkódozunk).
    var productSelected = false;

    function num(v) {
        if (v === null || v === undefined) {
            return 0;
        }
        v = ('' + v).replace(',', '.').replace(/[^0-9.\-]/g, '');
        const n = parseFloat(v);
        return isNaN(n) ? 0 : n;
    }

    function hiba($cont, uzenet) {
        $cont.find('.js-leltarkereshiba').text(uzenet || '');
    }

    // A szerver által visszaadott sor beillesztése: meglévőt cserélünk, újat a lista tetejére
    // teszünk, hogy a legutóbbi beolvasás mindig látszódjon.
    function sorBeilleszt($cont, res) {
        var $regi = $cont.find('.js-leltartetel[data-tetelid="' + res.tetelid + '"]');
        var $uj = $(res.html);
        if ($regi.length) {
            $regi.replaceWith($uj);
        } else {
            $cont.find('.js-leltartetelek').prepend($uj);
        }
        $uj.addClass('leltarfelvetel-frissitve');
        window.setTimeout(function () {
            $uj.removeClass('leltarfelvetel-frissitve');
        }, 700);
    }

    // A keresés eredményének kezelése: rögzített sor a táblázatba, változatválasztó a kereső alatti
    // dobozba, találat híján hibaüzenet.
    function handleResult($cont, res) {
        if (!res || !res.mode || res.mode === 'none') {
            hiba($cont, 'Nincs találat.');
            return;
        }
        if (res.mode === 'hiba') {
            hiba($cont, res.error || 'Hiba a rögzítés közben.');
            keresoUrit($cont);
            return;
        }
        hiba($cont, '');
        if (res.mode === 'valtozat') {
            $cont.find('.js-leltarvaltozatvalaszto').html(res.html);
            $cont.find('.js-leltarvaltozatvalasztoselect').focus();
            return;
        }
        // mode === 'tetel': a szerver már könyvelte a beolvasást
        sorBeilleszt($cont, res);
        keresoUrit($cont);
    }

    function keresoUrit($cont) {
        $cont.find('.js-leltarvaltozatvalaszto').empty();
        $cont.find('.js-leltarkereso').val('').focus();
    }

    // Enter a keresőben: vonalkódnak tekintjük, azzal keresünk változatot vagy terméket.
    function loadByVonalkod($cont, kod) {
        hiba($cont, '');
        $.ajax({
            url: URL_FINDTERMEK,
            data: {leltar: $cont.data('leltarid'), vonalkod: kod},
            dataType: 'json',
            success: function (res) {
                handleResult($cont, res);
            },
            error: function () {
                hiba($cont, 'Hiba a keresés közben.');
            }
        });
    }

    // Termék kiválasztása az autocomplete listából: ha van változata, változatválasztó jön,
    // egyébként egyből a könyvelés.
    function loadTermek($cont, termekid) {
        if (!termekid) {
            return;
        }
        $.ajax({
            url: URL_GETTERMEK,
            data: {leltar: $cont.data('leltarid'), termekid: termekid},
            dataType: 'json',
            success: function (res) {
                handleResult($cont, res);
            }
        });
    }

    function addValtozat($cont, $block) {
        var valtozatid = $block.find('.js-leltarvaltozatvalasztoselect').val();
        if (!valtozatid) {
            return;
        }
        $.ajax({
            url: URL_ADDTETEL,
            data: {
                leltar: $cont.data('leltarid'),
                termekid: $block.data('termekid'),
                valtozatid: valtozatid
            },
            dataType: 'json',
            success: function (res) {
                handleResult($cont, res);
            }
        });
    }

    function setTeny($cont, $row) {
        $.ajax({
            url: URL_SETTETEL,
            type: 'POST',
            data: {
                leltar: $cont.data('leltarid'),
                tetelid: $row.data('tetelid'),
                mennyiseg: num($row.find('.js-leltartenymennyiseg').val())
            },
            dataType: 'json',
            success: function (res) {
                if (res && res.ok) {
                    sorBeilleszt($cont, res);
                } else {
                    hiba($cont, (res && res.error) ? res.error : 'Hiba a mentés közben.');
                }
            },
            error: function () {
                hiba($cont, 'Hiba a mentés közben.');
            }
        });
    }

    function delTetel($cont, $row) {
        $.ajax({
            url: URL_DELTETEL,
            type: 'POST',
            data: {
                leltar: $cont.data('leltarid'),
                tetelid: $row.data('tetelid')
            },
            dataType: 'json',
            success: function (res) {
                if (res && res.ok) {
                    $row.remove();
                } else {
                    hiba($cont, (res && res.error) ? res.error : 'Hiba a törlés közben.');
                }
            },
            error: function () {
                hiba($cont, 'Hiba a törlés közben.');
            }
        });
    }

    function wire($cont) {
        // Kereső: 4 karaktertől név/cikkszám autocomplete (termékválasztás), Enterre vonalkódos keresés.
        $cont.find('.js-leltarkereso').autocomplete({
            minLength: 4,
            delay: 200,
            autoFocus: false,
            source: URL_KERESES,
            focus: function () {
                // Navigáláskor ne írja felül a beírt szöveget (maradjon a vonalkód/keresőkifejezés).
                return false;
            },
            select: function (event, ui) {
                // Termék kiválasztva a listából → változatválasztó vagy könyvelés.
                productSelected = true;
                $(this).val('');
                loadTermek($cont, ui.item.id);
                return false;
            }
        });

        $cont.on('keydown', '.js-leltarkereso', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                // Ha az Entert az autocomplete listás választás váltotta ki, azt a select már kezelte.
                if (productSelected) {
                    productSelected = false;
                    return;
                }
                var $inp = $(this);
                var kod = ($inp.val() || '').trim();
                $inp.autocomplete('close');
                $inp.val('');
                if (kod !== '') {
                    loadByVonalkod($cont, kod);
                }
            } else {
                // Új gépelés kezdődik: töröljük a listás választás jelzőt.
                productSelected = false;
            }
        });

        $cont.on('change', '.js-leltartenymennyiseg', function () {
            setTeny($cont, $(this).closest('.js-leltartetel'));
        });

        // Változatválasztás: a kiválasztott változat sorát könyveljük, a doboz ürül.
        $cont.on('change', '.js-leltarvaltozatvalasztoselect', function () {
            addValtozat($cont, $(this).closest('.js-leltarvaltozatsor'));
        });

        // Változatválasztó elvetése.
        $cont.on('click', '.js-leltarvaltozatmegse', function (e) {
            e.preventDefault();
            keresoUrit($cont);
        });

        $cont.on('click', '.js-leltarteteldel', function (e) {
            e.preventDefault();
            delTetel($cont, $(this).closest('.js-leltartetel'));
        });

        $cont.find('.js-leltarkereso').focus();
    }

    function init() {
        $('#mattkarb').mattkarb(new MattkarbConfig({
            beforeShow: function () {
                $('.js-leltarvissza').button();
                var $cont = $('.js-leltarfelvetel');
                if ($cont.length) {
                    wire($cont);
                }
            }
        }));
    }

    return {
        init: init
    };

})(jQuery);

$(document).ready(function () {
    leltarfelvetel.init();
});
