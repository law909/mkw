var boltieladas = (function ($) {

    const URL_GETKARB = '/admin/boltieladas/getkarb';
    const URL_FINDTERMEK = '/admin/boltieladas/findtermek';
    const URL_KERESES = '/admin/boltieladas/kereses';
    const URL_GETTERMEK = '/admin/boltieladas/gettermek';
    const URL_GETTETEL = '/admin/boltieladas/gettetel';
    const URL_CALCOSSZESEN = '/admin/bizonylatfej/calcosszesen';
    const URL_SAVE = '/admin/boltieladas/save';
    const URL_SZAMLAELOKESZIT = '/admin/boltieladas/szamlaelokeszit';
    const URL_SZAMLAKARB = '/admin/szamlafej/viewkarb?boltiszamla=1';

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

    function round2(n) {
        return Math.round((n + Number.EPSILON) * 100) / 100;
    }

    function fmt(n) {
        return round2(n).toFixed(2);
    }

    function recalcRow($row) {
        const menny = num($row.find('.js-be-mennyiseg').val());
        const netto = round2(num($row.find('.js-be-nettoegysar').val()));
        const brutto = round2(num($row.find('.js-be-bruttoegysar').val()));
        $row.find('.js-be-netto').text(fmt(netto * menny));
        $row.find('.js-be-brutto').text(fmt(brutto * menny));
    }

    function recalcTotals($cont) {
        let data = {
            fizmod: $cont.find('.js-boltieladas-fizmod').val(),
            netto: [],
            brutto: []
        };
        $cont.find('.js-boltieladas-tetel').each(function () {
            data.netto.push(num($(this).find('.js-be-netto').text()));
            data.brutto.push(num($(this).find('.js-be-brutto').text()));
        });
        $.ajax({
            url: URL_CALCOSSZESEN,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (!res) {
                    return;
                }
                $cont.find('.js-boltieladas-nettosum').text(res.nettosum);
                $cont.find('.js-boltieladas-bruttosum').text(res.bruttosum);
            }
        });
    }

    // ÁFA-kulcs alapján a nettó/bruttó/kedvezmény összehangolása az épp módosított mező szerint.
    function syncPrices($row, source) {
        var afakulcs = num($row.data('afakulcs'));
        var enetto = num($row.data('enetto'));
        var netto = round2(num($row.find('.js-be-nettoegysar').val()));
        var brutto = round2(num($row.find('.js-be-bruttoegysar').val()));
        var kedv = round2(num($row.find('.js-be-kedvezmeny').val()));

        if (source === 'kedvezmeny') {
            netto = round2(enetto * (100 - kedv) / 100);
            brutto = round2(netto * (100 + afakulcs) / 100);
            $row.find('.js-be-nettoegysar').val(fmt(netto));
            $row.find('.js-be-bruttoegysar').val(fmt(brutto));
        } else if (source === 'netto') {
            brutto = round2(netto * (100 + afakulcs) / 100);
            $row.find('.js-be-bruttoegysar').val(fmt(brutto));
            if (enetto > 0) {
                $row.find('.js-be-kedvezmeny').val(fmt((1 - netto / enetto) * 100));
            }
        } else if (source === 'brutto') {
            netto = round2(brutto / (100 + afakulcs) * 100);
            $row.find('.js-be-nettoegysar').val(fmt(netto));
            if (enetto > 0) {
                $row.find('.js-be-kedvezmeny').val(fmt((1 - netto / enetto) * 100));
            }
        }
        recalcRow($row);
    }

    // A keresés eredményének kezelése: kész tételsor a táblázatba, változatválasztó a táblázat
    // alatti dobozba, semmi találat esetén hibaüzenet.
    function handleResult($cont, res) {
        var $hiba = $cont.find('.js-boltieladas-kereshiba');
        if (!res || !res.mode || res.mode === 'none') {
            $hiba.text('Nincs találat.');
            return;
        }
        $hiba.text('');
        if (res.mode === 'valtozat') {
            // Változatválasztó a tétel táblázat alatt; a tételt a változat kiválasztása után adjuk hozzá.
            $cont.find('.js-boltieladas-valtozatvalaszto').html(res.html);
            $cont.find('.js-be-valtozatvalaszto').focus();
            return;
        }
        // mode === 'tetel'
        addTetelRow($cont, res.html);
    }

    // Kész tételsor hozzáadása a táblázathoz, a változatválasztó doboz ürítése, fókusz vissza a keresőre.
    function addTetelRow($cont, html) {
        var $row = $(html);
        $cont.find('.js-boltieladas-tetelek').append($row);
        recalcRow($row);
        recalcTotals($cont);
        $cont.find('.js-boltieladas-valtozatvalaszto').empty();
        $cont.find('.js-boltieladas-vonalkod').val('').focus();
    }

    // Enter a keresőben: vonalkódnak tekintjük, azzal keresünk változatot vagy terméket.
    function loadByVonalkod($cont, kod) {
        $cont.find('.js-boltieladas-kereshiba').text('');
        $.ajax({
            url: URL_FINDTERMEK,
            data: {vonalkod: kod},
            dataType: 'json',
            success: function (res) {
                handleResult($cont, res);
            },
            error: function () {
                $cont.find('.js-boltieladas-kereshiba').text('Hiba a keresés közben.');
            }
        });
    }

    // Termék kiválasztása az autocomplete listából: ha van változata, változatválasztó jön,
    // egyébként egyből a tételsor.
    function loadTermek($cont, termekid) {
        if (!termekid) {
            return;
        }
        $.ajax({
            url: URL_GETTERMEK,
            data: {termekid: termekid},
            dataType: 'json',
            success: function (res) {
                handleResult($cont, res);
            }
        });
    }

    // A változat kiválasztása után betölti a tételsort; a változatválasztó doboz ürül (eltűnik).
    function loadValtozatTetel($cont, $block) {
        var valtozatid = $block.find('.js-be-valtozatvalaszto').val();
        if (!valtozatid) {
            return;
        }
        $.ajax({
            url: URL_GETTETEL,
            data: {
                termekid: $block.data('termekid'),
                valtozatid: valtozatid
            },
            dataType: 'json',
            success: function (res) {
                if (res && res.ok && res.html) {
                    addTetelRow($cont, res.html);
                }
            }
        });
    }

    // A kosár sorainak összegyűjtése párhuzamos tömbökbe (a mentés és a számla is ezt küldi).
    function collectData($cont) {
        var data = {
            fizmod: $cont.find('.js-boltieladas-fizmod').val(),
            termekid: [],
            valtozatid: [],
            mennyiseg: [],
            kedvezmeny: [],
            enettoegysar: [],
            nettoegysar: [],
            bruttoegysar: [],
            afaid: []
        };
        $cont.find('.js-boltieladas-tetel').each(function () {
            var $r = $(this);
            data.termekid.push($r.find('.js-be-termekid').val());
            data.valtozatid.push($r.find('.js-be-valtozatid').val());
            data.afaid.push($r.find('.js-be-afaid').val());
            data.mennyiseg.push(num($r.find('.js-be-mennyiseg').val()));
            data.kedvezmeny.push(num($r.find('.js-be-kedvezmeny').val()));
            data.enettoegysar.push(num($r.data('enetto')));
            data.nettoegysar.push(num($r.find('.js-be-nettoegysar').val()));
            data.bruttoegysar.push(num($r.find('.js-be-bruttoegysar').val()));
        });
        return data;
    }

    function save($cont) {
        var $uzenet = $cont.find('.js-boltieladas-uzenet');
        $uzenet.removeClass('boltieladas-hiba').text('');

        var data = collectData($cont);

        if (!data.termekid.length) {
            $uzenet.addClass('boltieladas-hiba').text('Nincs tétel a bizonylaton!');
            return;
        }

        $.ajax({
            url: URL_SAVE,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res && res.ok) {
                    $cont.find('.js-boltieladas-tetelek').empty();
                    recalcTotals($cont);
                    $uzenet.removeClass('boltieladas-hiba').text('Rögzítve: ' + res.id);
                    $cont.find('.js-boltieladas-vonalkod').val('').focus();
                } else {
                    $uzenet.addClass('boltieladas-hiba').text((res && res.error) ? res.error : 'Hiba a rögzítés közben.');
                }
            },
            error: function () {
                $uzenet.addClass('boltieladas-hiba').text('Hiba a rögzítés közben.');
            }
        });
    }

    // "Számla" gomb: a kosarat mentés nélkül átadjuk a session-nek, és új ablakban megnyitjuk
    // a számlaszerkesztőt a tételekkel. A bolti eladás bizonylat NEM jön létre.
    function szamla($cont) {
        var $uzenet = $cont.find('.js-boltieladas-uzenet');
        $uzenet.removeClass('boltieladas-hiba').text('');

        var data = collectData($cont);
        if (!data.termekid.length) {
            $uzenet.addClass('boltieladas-hiba').text('Nincs tétel a bizonylaton!');
            return;
        }

        // Az ablakot még a kattintás eseményében megnyitjuk (popup-blokkoló elkerülése),
        // majd az AJAX válasz után irányítjuk a számlaszerkesztőre.
        var w = window.open('', '_blank');
        $.ajax({
            url: URL_SZAMLAELOKESZIT,
            type: 'POST',
            data: data,
            dataType: 'json',
            success: function (res) {
                if (res && res.ok) {
                    if (w) {
                        w.location = URL_SZAMLAKARB;
                    } else {
                        window.location = URL_SZAMLAKARB;
                    }
                    // A számla megnyílt: a POS kosarát ürítjük.
                    $cont.find('.js-boltieladas-tetelek').empty();
                    recalcTotals($cont);
                    $cont.find('.js-boltieladas-vonalkod').val('').focus();
                    $uzenet.removeClass('boltieladas-hiba').text('Számla megnyitva.');
                } else {
                    if (w) {
                        w.close();
                    }
                    $uzenet.addClass('boltieladas-hiba').text((res && res.error) ? res.error : 'Hiba a számla előkészítésekor.');
                }
            },
            error: function () {
                if (w) {
                    w.close();
                }
                $uzenet.addClass('boltieladas-hiba').text('Hiba a számla előkészítésekor.');
            }
        });
    }

    function wire($cont) {
        // Kereső: 4 karaktertől név/cikkszám autocomplete (termékválasztás), Enterre vonalkódos keresés.
        $cont.find('.js-boltieladas-vonalkod').autocomplete({
            minLength: 4,
            delay: 200,
            autoFocus: false,
            source: URL_KERESES,
            focus: function () {
                // Navigáláskor ne írja felül a beírt szöveget (maradjon a vonalkód/keresőkifejezés).
                return false;
            },
            select: function (event, ui) {
                // Termék kiválasztva a listából → változatválasztó vagy tételsor.
                productSelected = true;
                $(this).val('');
                loadTermek($cont, ui.item.id);
                return false;
            }
        });

        $cont.on('keydown', '.js-boltieladas-vonalkod', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                // Ha az Entert az autocomplete listás választás váltotta ki, azt a select már kezelte.
                if (productSelected) {
                    productSelected = false;
                    return;
                }
                var $inp = $(this);
                var kod = $.trim($inp.val());
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

        // Gépelés közben azonnal frissítjük a sort; az összesítő (szerverhívás) csak a mező
        // elhagyásakor (change) fut, hogy ne küldjünk kérést minden leütésre.
        $cont.on('keyup', '.js-be-mennyiseg', function () {
            recalcRow($(this).closest('.js-boltieladas-tetel'));
        });
        $cont.on('change', '.js-be-mennyiseg', function () {
            recalcRow($(this).closest('.js-boltieladas-tetel'));
            recalcTotals($cont);
        });
        $cont.on('change', '.js-be-kedvezmeny', function () {
            syncPrices($(this).closest('.js-boltieladas-tetel'), 'kedvezmeny');
            recalcTotals($cont);
        });
        $cont.on('change', '.js-be-nettoegysar', function () {
            syncPrices($(this).closest('.js-boltieladas-tetel'), 'netto');
            recalcTotals($cont);
        });
        $cont.on('change', '.js-be-bruttoegysar', function () {
            syncPrices($(this).closest('.js-boltieladas-tetel'), 'brutto');
            recalcTotals($cont);
        });

        // Fizetési mód váltásakor újraszámoljuk az összesítőket (a készpénzes minimum
        // címlet-kerekítés miatt).
        $cont.on('change', '.js-boltieladas-fizmod', function () {
            recalcTotals($cont);
        });

        // Változatválasztás: a kiválasztott változat tételsora bekerül a táblázatba, a doboz ürül.
        $cont.on('change', '.js-be-valtozatvalaszto', function () {
            loadValtozatTetel($cont, $(this).closest('.js-boltieladas-valtozatsor'));
        });

        // Változatválasztó elvetése.
        $cont.on('click', '.js-be-valtozatmegse', function (e) {
            e.preventDefault();
            $cont.find('.js-boltieladas-valtozatvalaszto').empty();
            $cont.find('.js-boltieladas-vonalkod').val('').focus();
        });

        $cont.on('click', '.js-be-del', function (e) {
            e.preventDefault();
            $(this).closest('.js-boltieladas-tetel').remove();
            recalcTotals($cont);
        });

        $cont.on('click', '.js-boltieladas-rogzit', function (e) {
            e.preventDefault();
            save($cont);
        });

        $cont.on('click', '.js-boltieladas-szamla', function (e) {
            e.preventDefault();
            szamla($cont);
        });

        //$cont.find('.js-boltieladas-rogzit').button();
        $cont.find('.js-boltieladas-vonalkod').focus();
    }

    function init(containerSelector) {
        var $container = $(containerSelector);
        if (!$container.length) {
            return;
        }
        $.ajax({
            url: URL_GETKARB,
            success: function (html) {
                $container.html(html);
                wire($container);
            }
        });
    }

    return {
        init: init
    };

})(jQuery);
