/**
 * Csomagolási lista: a darabokhoz írt dobozszámokból épül a dobozok táblája, a nettó súly a tételek
 * súlyából számolódik, amíg a felhasználó felül nem írja. A mentés a teljes állapotot küldi.
 */
$(document).ready(function () {
    const $form = $('.js-csomagolas');
    if (!$form.length) {
        return;
    }
    const $dobozok = $form.find('.js-csomagdobozok');
    const MEZOK = ['bruttosuly', 'szelesseg', 'magassag', 'melyseg'];

    function num(v) {
        const n = parseFloat(('' + (v ?? '')).replace(',', '.'));
        return isNaN(n) ? 0 : n;
    }

    // dobozszám → nettó súly (kg), és a még dobozba nem tett darabok száma
    function osszesit() {
        const netto = {};
        let hianyzik = 0;
        $form.find('.js-csomagtetel').each(function () {
            const $sor = $(this),
                suly = num($sor.data('suly')),
                darabonkent = $sor.data('darabonkent') == 1,
                mennyiseg = num($sor.data('mennyiseg'));
            $sor.find('.js-csomagdarab').each(function () {
                const szam = parseInt(this.value, 10),
                    db = darabonkent ? 1 : mennyiseg;
                if (szam > 0) {
                    netto[szam] = (netto[szam] || 0) + suly * db;
                } else {
                    hianyzik += db;
                }
            });
        });
        return {netto: netto, hianyzik: hianyzik};
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
        $form.find('.js-csomaguzenet').text('');
        $.ajax({
            url: $form.attr('action'),
            type: 'POST',
            data: $form.serialize(),
            dataType: 'json',
            success: function () {
                $form.find('.js-csomaguzenet').text('Mentve.');
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

    $form.on('input change', '.js-csomagdarab', frissit);
    $form.on('change', '.js-csomagmind', function () {
        $(this).closest('.js-csomagtetel').find('.js-csomagdarab').val(this.value);
        frissit();
    });
    $form.on('input', '.js-csomagnetto', function () {
        $(this).attr('data-kezi', this.value === '' ? '0' : '1');
    });
    // Enter egy mezőben ne küldje el félkészen
    $form.on('keydown', 'input', function (e) {
        if (e.which === 13) {
            e.preventDefault();
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
