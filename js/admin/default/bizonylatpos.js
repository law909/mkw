/**
 * A bizonylat karb vonalkódos (POS) tételfelvitele.
 *
 * A fej a klasszikus rögzítőé marad, csak a tételblokk cserélődik: vonalkód/keresés,
 * változatválasztó, egysoros tételek. A kiadott sorok eleve a klasszikus mezőneveket
 * viselik (tetelid[], tetel*_<uid>), így a mentés a gyorsrögzítő ágán megy át.
 *
 * A főoldali bolti eladással (boltieladas.js) szemben itt a partner, a raktár és a
 * valutanem a bizonylat fejéből jön, tehát az árazás és a készletjelzés fejfüggő.
 */
var bizonylatpos = (function ($) {

    const URL_FINDTERMEK = '/admin/bizonylatpos/findtermek';
    const URL_KERESES = '/admin/bizonylatpos/kereses';
    const URL_GETTERMEK = '/admin/bizonylatpos/gettermek';
    const URL_GETTETEL = '/admin/bizonylatpos/gettetel';

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

    // A fej aktuális állapota – minden keresés ezzel az árazási kontextussal megy.
    function fejAdatok(biztipus) {
        return {
            type: biztipus,
            partner: $('input.js-partnerid, select.js-partnerid').val() || '',
            valutanem: $('#ValutanemEdit').val() || '',
            raktar: $('#RaktarEdit').val() || ''
        };
    }

    function recalcRow($row) {
        const menny = num($row.find('.js-posmennyiseg').val());
        const brutto = round2(num($row.find('.js-posbruttoegysar').val()));
        $row.find('.js-posbrutto').text(fmt(brutto * menny));
    }

    // ÁFA-kulcs alapján a nettó/bruttó/kedvezmény összehangolása az épp módosított mező szerint.
    // A mentés a (rejtett) nettó egységárból dolgozik, ezért azt mindig szinkronban tartjuk.
    function syncPrices($row, source) {
        var afakulcs = num($row.data('afakulcs'));
        var enetto = num($row.data('enetto'));
        var netto = round2(num($row.find('.js-posnettoegysar').val()));
        var brutto = round2(num($row.find('.js-posbruttoegysar').val()));
        var kedv = round2(num($row.find('.js-poskedvezmeny').val()));

        if (source === 'kedvezmeny') {
            netto = round2(enetto * (100 - kedv) / 100);
            brutto = round2(netto * (100 + afakulcs) / 100);
            $row.find('.js-posnettoegysar').val(fmt(netto));
            $row.find('.js-posbruttoegysar').val(fmt(brutto));
        } else if (source === 'brutto') {
            netto = round2(brutto / (100 + afakulcs) * 100);
            $row.find('.js-posnettoegysar').val(fmt(netto));
            if (enetto > 0) {
                $row.find('.js-poskedvezmeny').val(fmt((1 - netto / enetto) * 100));
            }
        }
        recalcRow($row);
    }

    // A keresés eredménye: kész tételsor a táblázatba, változatválasztó a kereső alatti dobozba,
    // találat híján hibaüzenet.
    function handleResult($cont, res, onChange) {
        var $hiba = $cont.find('.js-poskereshiba');
        if (!res || !res.mode || res.mode === 'none') {
            $hiba.text('Nincs találat.');
            return;
        }
        $hiba.text('');
        if (res.mode === 'valtozat') {
            $cont.find('.js-posvaltozatvalaszto').html(res.html);
            $cont.find('.js-posvaltozatvalaszto select').focus();
            return;
        }
        addTetelRow($cont, res.html, onChange);
    }

    function addTetelRow($cont, html, onChange) {
        var $row = $(html);
        $cont.find('.js-postetelek').append($row);
        recalcRow($row);
        $cont.find('.js-posvaltozatvalaszto').empty();
        $cont.find('.js-poskereso').val('').focus();
        onChange();
    }

    function loadByVonalkod($cont, kod, biztipus, onChange) {
        $cont.find('.js-poskereshiba').text('');
        $.ajax({
            url: URL_FINDTERMEK,
            data: $.extend(fejAdatok(biztipus), {vonalkod: kod}),
            dataType: 'json',
            success: function (res) {
                handleResult($cont, res, onChange);
            },
            error: function () {
                $cont.find('.js-poskereshiba').text('Hiba a keresés közben.');
            }
        });
    }

    function loadTermek($cont, termekid, biztipus, onChange) {
        if (!termekid) {
            return;
        }
        $.ajax({
            url: URL_GETTERMEK,
            data: $.extend(fejAdatok(biztipus), {termekid: termekid}),
            dataType: 'json',
            success: function (res) {
                handleResult($cont, res, onChange);
            }
        });
    }

    function loadValtozatTetel($cont, $block, biztipus, onChange) {
        var valtozatid = $block.find('.js-posvaltozatvalaszto').val();
        if (!valtozatid) {
            return;
        }
        $.ajax({
            url: URL_GETTETEL,
            data: $.extend(fejAdatok(biztipus), {
                termekid: $block.data('termekid'),
                valtozatid: valtozatid
            }),
            dataType: 'json',
            success: function (res) {
                if (res && res.ok && res.html) {
                    addTetelRow($cont, res.html, onChange);
                }
            }
        });
    }

    /**
     * @param {string} biztipus a bizonylattípus azonosítója (a sablonváltozókhoz kell)
     * @param {function} onChange a fej összesítőjének újraszámolója (bizonylathelper calcOsszesen)
     */
    function init(biztipus, onChange) {
        var $cont = $('.js-bizonylatpos');
        if (!$cont.length) {
            return;
        }
        onChange = onChange || function () {
        };

        $cont.find('.js-poskereso').autocomplete({
            minLength: 4,
            delay: 200,
            autoFocus: false,
            source: function (request, response) {
                $.ajax({
                    url: URL_KERESES,
                    data: $.extend(fejAdatok(biztipus), {term: request.term}),
                    dataType: 'json',
                    success: response
                });
            },
            focus: function () {
                // Navigáláskor ne írja felül a beírt szöveget (maradjon a vonalkód/keresőkifejezés).
                return false;
            },
            select: function (event, ui) {
                productSelected = true;
                $(this).val('');
                loadTermek($cont, ui.item.id, biztipus, onChange);
                return false;
            }
        });

        $cont.on('keydown', '.js-poskereso', function (e) {
            if (e.which === 13) {
                // Enter a keresőben soha ne küldje be a bizonylatot, csak tételt vegyen fel.
                e.preventDefault();
                if (productSelected) {
                    productSelected = false;
                    return;
                }
                var $inp = $(this);
                var kod = ($inp.val() || '').trim();
                $inp.autocomplete('close');
                $inp.val('');
                if (kod !== '') {
                    loadByVonalkod($cont, kod, biztipus, onChange);
                }
            } else {
                productSelected = false;
            }
        });

        // Gépelés közben csak a sor frissül; az összesítő a mező elhagyásakor.
        $cont.on('keyup', '.js-posmennyiseg', function () {
            recalcRow($(this).closest('.js-postetel'));
        });
        $cont.on('change', '.js-posmennyiseg', function () {
            recalcRow($(this).closest('.js-postetel'));
            onChange();
        });
        $cont.on('change', '.js-poskedvezmeny', function () {
            syncPrices($(this).closest('.js-postetel'), 'kedvezmeny');
            onChange();
        });
        $cont.on('change', '.js-posbruttoegysar', function () {
            syncPrices($(this).closest('.js-postetel'), 'brutto');
            onChange();
        });

        $cont.on('change', '.js-posvaltozatvalaszto', function () {
            loadValtozatTetel($cont, $(this).closest('.js-posvaltozatsor'), biztipus, onChange);
        });

        $cont.on('click', '.js-posvaltozatmegse', function (e) {
            e.preventDefault();
            $cont.find('.js-posvaltozatvalaszto').empty();
            $cont.find('.js-poskereso').val('').focus();
        });

        $cont.on('click', '.js-postetheldel', function (e) {
            e.preventDefault();
            $(this).closest('.js-postetel').remove();
            onChange();
        });

        $cont.find('.js-poskereso').focus();
    }

    /**
     * Van-e legalább egy felvett tétel – a mentés előtti ellenőrzéshez.
     */
    function vanTetel() {
        return $('.js-bizonylatpos .js-postetel').length > 0;
    }

    /**
     * A POS sorok hozzájárulása a fej összesítőjéhez (nettó/bruttó, mennyiséggel szorozva).
     */
    function osszegek() {
        var netto = 0, brutto = 0;
        $('.js-bizonylatpos .js-postetel').each(function () {
            var $r = $(this),
                menny = num($r.find('.js-posmennyiseg').val());
            netto = netto + num($r.find('.js-posnettoegysar').val()) * menny;
            brutto = brutto + num($r.find('.js-posbruttoegysar').val()) * menny;
        });
        return {netto: netto, brutto: brutto};
    }

    return {
        init: init,
        vanTetel: vanTetel,
        osszegek: osszegek
    };

})(jQuery);
