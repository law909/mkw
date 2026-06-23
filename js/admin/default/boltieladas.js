var boltieladas = (function ($) {

    const URL_GETKARB = '/admin/boltieladas/getkarb';
    const URL_FINDTERMEK = '/admin/boltieladas/findtermek';
    const URL_CALCOSSZESEN = '/admin/bizonylatfej/calcosszesen';
    const URL_SAVE = '/admin/boltieladas/save';

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

    function loadTetel($cont, kod) {
        var $hiba = $cont.find('.js-boltieladas-kereshiba');
        $hiba.text('');
        $.ajax({
            url: URL_FINDTERMEK,
            data: {vonalkod: kod},
            dataType: 'html',
            success: function (html) {
                // A szerver a kész tételsor HTML-jét adja vissza; üres válasz = nincs találat.
                html = $.trim(html || '');
                if (html === '') {
                    $hiba.text('Nincs találat a vonalkódra: ' + kod);
                    return;
                }
                var $row = $(html);
                $cont.find('.js-boltieladas-tetelek').append($row);
                recalcRow($row);
                recalcTotals($cont);
            },
            error: function () {
                $hiba.text('Hiba a keresés közben.');
            }
        });
    }

    function save($cont) {
        var $uzenet = $cont.find('.js-boltieladas-uzenet');
        $uzenet.removeClass('boltieladas-hiba').text('');

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

    function wire($cont) {
        // Vonalkód: Enterre betölti a tételt, majd ürül és visszakapja a fókuszt.
        $cont.on('keydown', '.js-boltieladas-vonalkod', function (e) {
            if (e.which === 13) {
                e.preventDefault();
                var $inp = $(this);
                var kod = $.trim($inp.val());
                if (kod !== '') {
                    loadTetel($cont, kod);
                }
                $inp.val('');
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

        $cont.on('click', '.js-be-del', function (e) {
            e.preventDefault();
            $(this).closest('.js-boltieladas-tetel').remove();
            recalcTotals($cont);
        });

        $cont.on('click', '.js-boltieladas-rogzit', function (e) {
            e.preventDefault();
            save($cont);
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
