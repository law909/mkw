/**
 * Közös autocomplete renderer-ek.
 *
 * A jQuery UI 1.12-ben a menüelem markupja `<li><a>`-ról
 * `<li><div class="ui-menu-item-wrapper">`-re változott: a wrapper osztályt a widget
 * teszi rá a gyerek elemre, nekünk csak `<a>` helyett `<div>`-et kell adnunk. `<a>`-val
 * a legördülő stílus nélkül és kattinthatatlanul jelenne meg.
 *
 * Korábban ez a két függvény 22 példányban élt 20 fájlban – itt egy helyen van. A
 * hívóhelyek változatlanul a nevükre hivatkoznak: a mkwcomp.js minden admin oldalon
 * (base.tpl) a képernyő-JS-ek előtt töltődik be, így a globális definíció mindenhol áll.
 */
function partnerAutocompleteRenderer(ul, item) {
    return $('<li>')
        .append($('<div>').text(item.value))
        .appendTo(ul);
}

function termekAutocompleteRenderer(ul, item) {
    var $item = $('<div>').text(item.label);
    if (item.nemlathato) {
        $item.addClass('nemelerhetovaltozat');
    }
    return $('<li>')
        .append($item)
        .appendTo(ul);
}

(function ($) {
    /**
     * A legördülő sorainak saját rendererét állítja be:
     *   $('.js-termekselect').autocomplete(cfg).autocompleteRenderer(termekAutocompleteRenderer);
     *
     * Miért kell: a jQuery UI 1.12 óta a `.autocomplete('instance')` ÜRES halmazon
     * undefined-ot ad vissza (1.11-ben magát a jQuery objektumot adta), így a korábbi
     * `…autocomplete('instance')._renderItem = fn` alak TypeError-t dobott ott, ahol a
     * szelektor semmire sem illeszkedik – például tétel nélküli, most nyitott bizonylaton.
     * A kivétel a hívó függvény hátralévő részét is megölte (gombok, eseménykezelők).
     *
     * Ráadásul a régi alak több találat esetén is csak az ELSŐ elemre állította be a
     * renderert; ez itt már mindegyikre ráteszi.
     */
    $.fn.autocompleteRenderer = function (renderer) {
        return this.each(function () {
            var instance = $(this).autocomplete('instance');
            if (instance) {
                instance._renderItem = renderer;
            }
        });
    };

    /**
     * Egy `.button()`-ná alakított elem feliratának cseréje:
     *   $('.js-termekfabutton').buttonLabel('KABÁT');
     *
     * Miért kell: a jQuery UI 1.11 a gomb szövegét egy `<span class="ui-button-text">`-be
     * csomagolta, ezért a régi kód `$('span', gomb).text(…)`-tel írta felül a feliratot.
     * Az 1.12 óta a widget NEM hoz létre ilyen spant, így az a szelektor semmire sem
     * illeszkedik, és a felirat némán változatlan marad – a termékkarbon például a
     * kategóriaválasztó „válasszon"-on ragadt, pedig a `data-value` már a kiválasztott
     * elemé volt.
     *
     * Ha van szöveg-span gyerek (régi markup), azt írja, különben magát az elemet.
     * Az ikon-spanokat (`ui-icon`) nem tekinti feliratnak.
     */
    $.fn.buttonLabel = function (text) {
        return this.each(function () {
            var $el = $(this),
                $label = $el.children('span').not('.ui-icon');
            if ($label.length) {
                $label.text(text);
            } else {
                $el.text(text);
            }
        });
    };

    /**
     * Az autocomplete keresései kikerülnek a globális "Kérem várjon..." zárolásból.
     *
     * Miért kell: az appinit.js minden ajax kérésre $.blockUI-t hív, a blockUI pedig
     * bindEvents:true-val elnyeli a keydown/keypress eseményeket a zároló réteg alatt.
     * Emiatt a minLength (általában 4) elérése után a további leütések elvesztek: a
     * karakter meg sem jelent a mezőben, és nem is került bele a keresésbe.
     *
     * A jelzőt a source körül állítjuk, nem az URL-ből ismerjük fel: a $.ajax hívás a
     * source-on belül szinkron módon történik, így a prefilter biztosan a jelző alatt fut.
     * Így a string source és a saját $.ajax-os source függvények is le vannak fedve.
     */
    var autocompleteDepth = 0;

    $.ajaxPrefilter(function (options) {
        if (autocompleteDepth > 0) {
            options.global = false;
        }
    });

    var originalInitSource = $.ui.autocomplete.prototype._initSource;
    $.ui.autocomplete.prototype._initSource = function () {
        originalInitSource.call(this);
        var innerSource = this.source;
        this.source = function (request, response) {
            autocompleteDepth++;
            try {
                return innerSource.call(this, request, response);
            } finally {
                autocompleteDepth--;
            }
        };
    };
})(jQuery);

var mkwcomp = (function ($) {

    // jstree alapú fa szűrő (termékfa, termékmenü) – a listaurl adja a fa tartalmát
    function jstreeFilter(listaurl) {

        // szelektoronkénti állapot: a fa aszinkron tölt, ezért az URL-ből érkező kijelölést
        // addig függőben tartjuk, amíg a fa be nem töltött
        var state = {};

        function getState(sel) {
            if (!state[sel]) {
                state[sel] = {pending: []};
            }
            return state[sel];
        }

        function applyChecks(sel, ids) {
            const $tree = $(sel);
            $tree.jstree('uncheck_all');
            if (!ids || !ids.length) {
                return;
            }
            // egyetlen bejárás az egész listára, nem azonosítónként egy
            const keresett = {};
            $.each(ids, function (i, id) {
                keresett['' + id] = true;
            });
            $('a[id]', $tree).each(function () {
                if (keresett[this.id.split('_')[1]]) {
                    $tree.jstree('check_node', $(this));
                }
            });
        }

        function clearChecks(sel) {
            getState(sel).pending = [];
            $(sel).jstree('uncheck_all');
        }

        // kijelölés visszaállítása id-lista alapján (pl. az URL-ből);
        // ha a fa még tölt, a betöltés végén állítjuk be
        function setChecks(sel, ids) {
            getState(sel).pending = ids || [];
            if ($('li', $(sel)).length) {
                applyPending(sel);
            }
        }

        function applyPending(sel) {
            var st = getState(sel);
            applyChecks(sel, st.pending);
            st.pending = [];
        }

        function getFilter(sel) {
            var fak = [];
            $(sel).jstree('get_checked').each(function () {
                var x = $('a', this).attr('id');
                if (x) {
                    fak.push(x.split('_')[1]);
                }
            });
            // a fa még nem töltött be: az URL-ből kapott, még be nem állított kijelölés az érvényes
            if (!fak.length && getState(sel).pending.length) {
                return getState(sel).pending.slice();
            }
            return fak;
        }

        function init(sel) {
            $(sel).jstree({
                core: {animation: 100},
                plugins: ['themeroller', 'json_data', 'contextmenu', 'ui', 'checkbox'],
                themeroller: {item: ''},
                json_data: {
                    ajax: {url: listaurl}
                },
                ui: {select_limit: 1},
                contextmenu: {
                    select_node: true,
                    items: {
                        create: false, rename: false, remove: false, ccp: false
                    }
                }
            })
                .on('loaded.jstree', function () {
                    applyPending(sel);
                })
                // három szelektor az egész fára, nem csomópontonkénti bejárás: a nagy
                // termékfákon minden pipálás a teljes fát végigjárta
                .on('change_state.jstree', function () {
                    const $tree = $(this);
                    $('ins.jstree-checkbox', $tree).removeClass('ui-icon ui-icon-circle-check ui-icon-check');
                    $('li.jstree-checked > a > ins.jstree-checkbox', $tree).addClass('ui-icon ui-icon-circle-check');
                    $('li.jstree-undetermined > a > ins.jstree-checkbox', $tree).addClass('ui-icon ui-icon-check');
                });
        }

        return {
            init: init,
            clearChecks: clearChecks,
            setChecks: setChecks,
            getFilter: getFilter
        }
    }

    function datumEdit() {

        function init(sel) {
            var $datumedit;
            if (typeof sel === 'string') {
                $datumedit = $(sel);
            } else {
                $datumedit = sel;
            }
            if ($datumedit) {
                $datumedit.datepicker($.datepicker.regional['hu']);
                $datumedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                $datumedit.datepicker('setDate', $datumedit.attr('data-datum'));
            }
        }

        function clear(sel) {
            var $datumedit;
            if (typeof sel === 'string') {
                $datumedit = $(sel);
            } else {
                $datumedit = sel;
            }
            if ($datumedit) {
                $datumedit.datepicker('setDate', $datumedit.attr('data-datum'));
            }
        }

        function getDate(sel) {
            var d = $(sel).datepicker('getDate');
            if (d) {
                return d.getFullYear() + '.' + (d.getMonth() + 1) + '.' + d.getDate();
            }
            return '';
        }

        return {
            init: init,
            clear: clear,
            getDate: getDate
        }
    }

    function bizonylattipusFilter() {

        function getFilter(sel) {
            var btk = [];
            $(sel + ':checked').each(function () {
                btk.push($(this).val());
            });
            return btk;
        }

        return {
            getFilter: getFilter
        }
    }

    function partnercimkeFilter() {

        function getFilter(sel) {
            var cimkek = [];
            $(sel).filter('.ui-state-hover').each(function () {
                cimkek.push($(this).attr('data-id'));
            });
            return cimkek;
        }

        // a kijelölt címkék visszaállítása id-lista alapján (pl. az URL-ből)
        function setFilter(sel, ids) {
            var $cimkek = $(sel);
            $cimkek.removeClass('ui-state-hover');
            $.each(ids || [], function (i, id) {
                if (/^\d+$/.test(id)) {
                    $cimkek.filter('[data-id="' + id + '"]').addClass('ui-state-hover');
                }
            });
        }

        return {
            getFilter: getFilter,
            setFilter: setFilter
        }
    }

    /**
     * A készletsorok Foglalt/Érkezik linkje: modalban a foglaló, illetve érkeztető bizonylatok.
     * A link data-termekid, data-valtozatid, data-raktarid és data-tipus (foglal|erkezik)
     * attribútumot hord; a bizonylatszám a szűrt listanézetre visz.
     */
    function keszletBizonylatok() {

        function bind($root) {
            $root.on('click', '.js-keszletbizonylatok', function (e) {
                e.preventDefault();
                const $link = $(this);
                $.ajax({
                    url: '/admin/termek/keszletbizonylatok',
                    type: 'GET',
                    data: {
                        termekid: $link.data('termekid'),
                        valtozatid: $link.data('valtozatid'),
                        raktarid: $link.data('raktarid'),
                        tipus: $link.data('tipus')
                    },
                    success: function (data) {
                        const d = JSON.parse(data);
                        $('#dialogcenter').html(d.html).dialog({
                            modal: true,
                            title: d.title,
                            width: 'auto',
                            buttons: {
                                'OK': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                });
            });
        }

        return {
            bind: bind
        }
    }

    /**
     * A kérdőív szerkesztője az időpont és az időpont téma karbantartó Kérdőív fülén
     * (idopontkerdoivszerkeszto.tpl). A kérdések blokkjait a rejtett kerdoiv mező JSON-jából
     * építi, és minden módosításkor vissza is írja – a szerver a JSON-t kapja.
     * Szerkezet: {cim, leiras, kerdesek: [{szoveg, tipus: egy|tobb|szoveg, kotelezo, valaszok: []}]}
     * A forrás legördülő értéke "idopont-<id>" vagy "tema-<id>", innen tudja, honnan kérje a JSON-t.
     */
    function kerdoivSzerkeszto() {

        function init($tab) {
        const dialogcenter = $('#dialogcenter'),
            $json = $tab.find('.js-kerdoivjson'),
            $lista = $tab.find('.js-kerdoivkerdesek'),
            tipusok = [
                {ertek: 'egy', nev: 'Egy válasz jelölhető'},
                {ertek: 'tobb', nev: 'Több válasz is jelölhető'},
                {ertek: 'szoveg', nev: 'Szöveges válasz'}
            ];

        function kerdesBlokk(k) {
            const $blokk = $('<fieldset class="mattkarb-doboz js-kerdoivkerdes" style="display:block">'),
                $legend = $('<legend>').append($('<span class="js-ksorszam">')),
                $tipus = $('<select class="js-ktipus">'),
                $valaszok = $('<textarea class="js-kvalaszok" rows="6" cols="60">').val((k.valaszok || []).join('\n'));
            tipusok.forEach(function (t) {
                $tipus.append($('<option>').val(t.ertek).text(t.nev));
            });
            $tipus.val(k.tipus || 'egy');
            $legend
                .append(' ')
                .append($('<a href="#" class="js-kfel" title="Feljebb"><span class="ui-icon ui-icon-arrowthick-1-n"></span></a>'))
                .append($('<a href="#" class="js-kle" title="Lejjebb"><span class="ui-icon ui-icon-arrowthick-1-s"></span></a>'))
                .append($('<a href="#" class="js-ktorol" title="Kérdés törlése"><span class="ui-icon ui-icon-circle-minus"></span></a>'));
            const $table = $('<table>').append(
                $('<tr>').append($('<td>').text('Kérdés:'))
                    .append($('<td>').append($('<input type="text" size="83" maxlength="255" class="js-kszoveg">').val(k.szoveg || ''))),
                $('<tr>').append($('<td>').text('Válasz módja:'))
                    .append($('<td>').append($tipus).append(' ')
                        .append($('<label>').append($('<input type="checkbox" class="js-kkotelezo">').prop('checked', !!k.kotelezo)).append(' Kötelező'))),
                $('<tr class="js-kvalaszoksor">').append($('<td>').text('Lehetséges válaszok:'))
                    .append($('<td>').append($valaszok).append($('<div class="mattkarb-hint">').text('Soronként egy válasz.')))
            );
            $blokk.append($legend).append($table);
            $blokk.find('.js-kfel, .js-kle, .js-ktorol').button();
            return $blokk;
        }

        function frissitBlokk($blokk, sorszam) {
            $blokk.find('.js-ksorszam').text(sorszam + '. kérdés');
            $blokk.find('.js-kvalaszoksor').toggle($blokk.find('.js-ktipus').val() !== 'szoveg');
        }

        function frissitMind() {
            $lista.children('.js-kerdoivkerdes').each(function (i) {
                frissitBlokk($(this), i + 1);
            });
        }

        function osszegyujt() {
            const adat = {
                cim: $tab.find('.js-kerdoivcim').val(),
                leiras: $tab.find('.js-kerdoivleiras').val(),
                kerdesek: []
            };
            $lista.children('.js-kerdoivkerdes').each(function () {
                const $b = $(this),
                    tipus = $b.find('.js-ktipus').val();
                adat.kerdesek.push({
                    szoveg: $b.find('.js-kszoveg').val(),
                    tipus: tipus,
                    kotelezo: $b.find('.js-kkotelezo').is(':checked'),
                    valaszok: tipus === 'szoveg' ? [] : $b.find('.js-kvalaszok').val().split('\n').map(function (s) {
                        return s.trim();
                    }).filter(function (s) {
                        return s !== '';
                    })
                });
            });
            $json.val(JSON.stringify(adat));
        }

        function betolt(adat) {
            $tab.find('.js-kerdoivcim').val(adat.cim || '');
            $tab.find('.js-kerdoivleiras').val(adat.leiras || '');
            $lista.empty();
            (adat.kerdesek || []).forEach(function (k) {
                $lista.append(kerdesBlokk(k));
            });
            frissitMind();
            osszegyujt();
        }

        let kezdo = {};
        try {
            kezdo = JSON.parse($json.val() || '{}');
        } catch (e) {
            kezdo = {};
        }
        betolt(kezdo);

        $tab
            .on('input change', 'input, textarea, select', function () {
                if ($(this).is('.js-ktipus')) {
                    frissitBlokk($(this).closest('.js-kerdoivkerdes'), 0);
                    frissitMind();
                }
                if (!$(this).is('.js-kerdoivforras')) {
                    osszegyujt();
                }
            })
            .on('click', '.js-kerdoivujkerdes', function (e) {
                e.preventDefault();
                const $blokk = kerdesBlokk({tipus: 'egy', kotelezo: false, valaszok: []});
                $lista.append($blokk);
                frissitMind();
                osszegyujt();
                $blokk.find('.js-kszoveg').trigger('focus');
            })
            .on('click', '.js-kfel, .js-kle', function (e) {
                e.preventDefault();
                const $blokk = $(this).closest('.js-kerdoivkerdes');
                if ($(this).is('.js-kfel')) {
                    $blokk.prev('.js-kerdoivkerdes').before($blokk);
                } else {
                    $blokk.next('.js-kerdoivkerdes').after($blokk);
                }
                frissitMind();
                osszegyujt();
            })
            .on('click', '.js-ktorol', function (e) {
                e.preventDefault();
                const $blokk = $(this).closest('.js-kerdoivkerdes');
                dialogcenter.html('Törli ezt a kérdést?').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'Igen': function () {
                            $blokk.remove();
                            frissitMind();
                            osszegyujt();
                            $(this).dialog('close');
                        },
                        'Nem': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            })
            .on('change', '.js-kerdoivforras', function () {
                const $sel = $(this),
                    forras = ($sel.val() || '').split('-');
                if (forras.length !== 2) {
                    return;
                }
                $.ajax({
                    url: '/admin/' + (forras[0] === 'tema' ? 'idoponttema' : 'idopont') + '/getkerdoiv',
                    type: 'GET',
                    data: {id: forras[1]},
                    success: function (data) {
                        betolt(JSON.parse(data));
                        $sel.val('');
                    }
                });
            });
        $tab.find('.js-kerdoivujkerdes').button();

        return {
            betolt: betolt,
            vanKerdes: function () {
                return $lista.children('.js-kerdoivkerdes').length > 0;
            }
        };
        }

        return {
            init: init
        }
    }

    return {
        termekfaFilter: jstreeFilter('/admin/termekfa/jsonlist'),
        termekmenuFilter: jstreeFilter('/admin/termekmenu/jsonlist'),
        datumEdit: datumEdit(),
        bizonylattipusFilter: bizonylattipusFilter(),
        partnercimkeFilter: partnercimkeFilter(),
        keszletBizonylatok: keszletBizonylatok(),
        kerdoivSzerkeszto: kerdoivSzerkeszto()
    }

})(jQuery);