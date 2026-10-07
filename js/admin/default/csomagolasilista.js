/**
 * Csomagolási lista: tételenként doboz × mennyiség párok, ezekből épül a dobozok táblája; a nettó súly a tételek
 * súlyából számolódik, amíg a felhasználó felül nem írja. A mentés a teljes állapotot küldi.
 */
$(document).ready(function () {
    const $form = $('.js-csomagolas');
    if (!$form.length) {
        return;
    }
    const $dobozok = $form.find('.js-csomagdobozok');
    const MEZOK = ['bruttosuly', 'szelesseg', 'magassag', 'melyseg'];
    let pairIndex = $form.find('.js-csomagpar').length;

    function num(v) {
        const n = parseFloat(('' + (v ?? '')).replace(',', '.'));
        return isNaN(n) ? 0 : n;
    }

    function round(n) {
        return Math.round(n * 10000) / 10000;
    }

    // a tétel párjai: doboz (0 = nincs megadva) és mennyiség
    function getPairs($sor) {
        return $sor.find('.js-csomagpar').map(function () {
            return {
                doboz: parseInt($(this).find('.js-csomagpardoboz').val(), 10) || 0,
                db: num($(this).find('.js-csomagpardb').val())
            };
        }).get();
    }

    function button(osztaly, ikon, title) {
        return $(`<button type="button" class="${osztaly} csomagolas-gomb ui-button ui-widget ui-state-default ui-corner-all"></button>`)
            .attr('title', title)
            .append($('<span class="ui-button-text"></span>').append($(`<span class="ui-icon ${ikon}"></span>`)));
    }

    // $utan nélkül a sor végére kerül
    function addPair($sor, db, $utan) {
        const id = $sor.data('id'),
            i = pairIndex++;
        const $par = $('<span class="js-csomagpar csomagolas-par"></span>').append(
            $('<input class="js-csomagpardoboz csomagolas-szam" type="number" min="1" step="1">')
                .attr({name: `tetel[${id}][${i}][doboz]`, title: 'Doboz'}),
            ' × ',
            $('<input class="js-csomagpardb csomagolas-szam" type="number" min="0" step="any">')
                .attr({name: `tetel[${id}][${i}][db]`, title: 'Mennyiség'}).val(db > 0 ? round(db) : ''),
            ' ',
            button('js-csomagpardel', 'ui-icon-circle-minus', 'Töröl'),
            ' ',
            button('js-csomagparadd', 'ui-icon-circle-plus', 'Újabb doboz ez alá')
        );
        if ($utan) {
            $par.insertAfter($utan);
        } else {
            $sor.find('.csomagolas-parok').append($par);
        }
        return $par;
    }

    // az utolsó pár helyére üres kerül a tétel teljes mennyiségével, hogy a sor ne maradjon beviteli mező nélkül
    function removePair($par) {
        const $sor = $par.closest('.js-csomagtetel');
        $par.remove();
        if (!$sor.find('.js-csomagpar').length) {
            addPair($sor, num($sor.data('mennyiseg')));
        }
    }

    // dobozszám → nettó súly (kg); közben a tételsorok maradékát is kiírja
    // a tétel párokba még be nem írt mennyisége
    function getKiosztatlan($sor) {
        return round(getPairs($sor).reduce((ossz, par) => ossz - par.db, num($sor.data('mennyiseg'))));
    }

    function osszesit() {
        const netto = {};
        let hianyzik = 0;
        $form.find('.js-csomagtetel').each(function () {
            const $sor = $(this),
                suly = num($sor.data('suly'));
            let maradek = num($sor.data('mennyiseg'));
            getPairs($sor).forEach(function (par) {
                if (par.doboz > 0 && par.db > 0) {
                    netto[par.doboz] = (netto[par.doboz] || 0) + suly * par.db;
                    maradek -= par.db;
                }
            });
            maradek = round(maradek);
            $sor.find('.js-csomagmaradek').text(maradek).toggleClass('redtext', maradek < 0);
            hianyzik += Math.max(maradek, 0);
            const nincsMit = getKiosztatlan($sor) <= 0;
            $sor.find('.js-csomagparadd').prop('disabled', nincsMit).toggleClass('ui-state-disabled', nincsMit);
        });
        return {netto: netto, hianyzik: round(hianyzik)};
    }

    function ujDobozSor(szam) {
        const $sor = $('<tr class="js-csomagdoboz"></tr>').attr('data-szam', szam);
        $sor.append($('<td></td>').text(szam));
        $sor.append($('<td></td>').append(
            $('<input class="js-csomagnetto" type="number" step="any" min="0">').attr('name', `dobozadat[${szam}][nettosuly]`)
        ));
        MEZOK.forEach(function (mezo) {
            $sor.append($('<td></td>').append(
                $('<input type="number" step="any" min="0">').attr('name', `dobozadat[${szam}][${mezo}]`)
            ));
        });
        $sor.append($('<td></td>').append(
            button('js-csomagdobozdel', 'ui-icon-circle-minus', 'Töröl')
        ));
        return $sor;
    }

    function frissit() {
        const ossz = osszesit();
        const szamok = Object.keys(ossz.netto).map(Number).sort((a, b) => a - b);
        $dobozok.find('.js-csomagdoboz').each(function () {
            if (szamok.indexOf(Number($(this).data('szam'))) === -1) {
                $(this).remove();
            }
        });
        let $elozo = null;
        szamok.forEach(function (szam) {
            let $sor = $dobozok.find(`.js-csomagdoboz[data-szam="${szam}"]`);
            if (!$sor.length) {
                $sor = ujDobozSor(szam);
                if ($elozo) {
                    $sor.insertAfter($elozo);
                } else {
                    $dobozok.prepend($sor);
                }
            }
            const $netto = $sor.find('.js-csomagnetto');
            if ($netto.attr('data-kezi') !== '1') {
                $netto.val(ossz.netto[szam] ? Math.round(ossz.netto[szam] * 1000) / 1000 : '');
            }
            $elozo = $sor;
        });
        $form.find('.js-csomaghianyzik').text(ossz.hianyzik > 0 ? `Még ${ossz.hianyzik} darab nincs dobozba téve.` : '');
    }

    function ment(siker, hiba) {
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function () {
                mkwUzenet('A mentés sikerült.');
                if (siker) {
                    siker();
                }
            },
            // the reason is shown by the global ajaxError (appinit.js)
            error: function () {
                if (hiba) {
                    hiba();
                }
            }
        });
    }

    $form.on('input change', '.js-csomagpardoboz, .js-csomagpardb', frissit);
    // az új pár a még ki nem osztott mennyiséget kapja
    $form.on('click', '.js-csomagparadd', function (e) {
        e.preventDefault();
        const $sor = $(this).closest('.js-csomagtetel');
        const kiosztatlan = getKiosztatlan($sor);
        if (kiosztatlan <= 0) {
            return;
        }
        addPair($sor, kiosztatlan, $(this).closest('.js-csomagpar')).find('.js-csomagpardoboz').trigger('focus');
        frissit();
    });
    $form.on('click', '.js-csomagpardel', function (e) {
        e.preventDefault();
        removePair($(this).closest('.js-csomagpar'));
        frissit();
    });
    // a doboz sora frissit()-ben tűnik el, amikor már egy pár sem hivatkozik rá
    $form.on('click', '.js-csomagdobozdel', function (e) {
        e.preventDefault();
        const szam = Number($(this).closest('.js-csomagdoboz').data('szam'));
        $form.find('.js-csomagpar').filter(function () {
            return parseInt($(this).find('.js-csomagpardoboz').val(), 10) === szam;
        }).each(function () {
            removePair($(this));
        });
        frissit();
    });
    $form.on('input', '.js-csomagnetto', function () {
        $(this).attr('data-kezi', this.value === '' ? '0' : '1');
    });
    // Enter ne küldje el félkészen; a tételek párjaiban a következő tétel dobozszámára ugrik
    $form.on('keydown', 'input', function (e) {
        if (e.which !== 13) {
            return;
        }
        e.preventDefault();
        const $sor = $(this).closest('.js-csomagtetel');
        if ($sor.length) {
            $sor.nextAll('.js-csomagtetel').first().find('.js-csomagpardoboz').first().trigger('focus').trigger('select');
        }
    });
    $form.on('submit', function (e) {
        e.preventDefault();
        ment();
    });
    $form.find('.js-csomagnyomtat').on('click', function (e) {
        e.preventDefault();
        // a mentés aszinkron: az ablakot most kell megnyitni, különben a böngésző letiltja
        const ablak = window.open('', '_blank');
        ment(function () {
            ablak.location = $form.data('printurl');
        }, function () {
            ablak.close();
        });
    });

    frissit();
});
