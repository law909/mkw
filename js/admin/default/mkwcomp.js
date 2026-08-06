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
        .append($('<div>').html(item.value))
        .appendTo(ul);
}

function termekAutocompleteRenderer(ul, item) {
    var $item = $('<div>').html(item.label);
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
            var $tree = $(sel);
            $tree.jstree('uncheck_all');
            $.each(ids, function (i, id) {
                var $node = $('a', $tree).filter(function () {
                    return this.id && this.id.split('_')[1] === '' + id;
                });
                if ($node.length) {
                    $tree.jstree('check_node', $node);
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
                .on('change_state.jstree', function (e, data) {
                    $termekfa = $(this);
                    $('li', $termekfa).each(function (i) {
                        $this = $(this);
                        if ($this.hasClass('jstree-unchecked')) {
                            $('ins.jstree-checkbox', $this).removeClass('ui-icon ui-icon-circle-check ui-icon-check');
                        } else if ($this.hasClass('jstree-checked')) {
                            $('ins.jstree-checkbox', $this).removeClass('ui-icon ui-icon-circle-check ui-icon-check').addClass('ui-icon ui-icon-circle-check');
                        } else if ($this.hasClass('jstree-undetermined')) {
                            $('ins.jstree-checkbox', $this).removeClass('ui-icon ui-icon-circle-check ui-icon-check').addClass('ui-icon ui-icon-check');
                        }
                    });
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

    return {
        termekfaFilter: jstreeFilter('/admin/termekfa/jsonlist'),
        termekmenuFilter: jstreeFilter('/admin/termekmenu/jsonlist'),
        datumEdit: datumEdit(),
        bizonylattipusFilter: bizonylattipusFilter(),
        partnercimkeFilter: partnercimkeFilter()
    }

})(jQuery);