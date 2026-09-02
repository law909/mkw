/**
 * Tételek ellenőrzése: a beolvasott (vonalkód / cikkszám / keresés) termékeket a bizonylat
 * tételeihez párosítjuk, és a becsipogott mennyiséget az "Ellenőrzött" oszlopba számoljuk. Ami
 * nincs a bizonylaton, új sorként jelenik meg. Az eltérést a böngésző számolja, a szerver csak
 * a tételeket és a termékazonosítást adja (bizonylatellenorzesController).
 */
$(document).ready(function () {

    const URL_FINDTERMEK = '/admin/bizonylatellenorzes/findtermek';
    const URL_KERESES = '/admin/bizonylatellenorzes/kereses';
    const URL_GETTERMEK = '/admin/bizonylatellenorzes/gettermek';
    const TURES = 0.0001;
    const NINCSABIZONYLATON = 'nincs a bizonylaton';

    function num(v) {
        const n = parseFloat(('' + (v === undefined || v === null ? '' : v)).replace(',', '.'));
        return isNaN(n) ? 0 : n;
    }

    function fmt(n) {
        return (Math.round(n * 10000) / 10000).toString();
    }

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            const $cont = $('.js-ellenorzes'),
                $kereso = $cont.find('.js-ellkereso'),
                $hiba = $cont.find('.js-ellhiba');
            let productSelected = false;

            function tbody() {
                return $cont.find('.js-elltabla tbody');
            }

            function mennyiseg() {
                const m = num($cont.find('.js-ellmennyiseg').val());
                return m === 0 ? 1 : m;
            }

            // Egy sor eltérése és a lap összegzése; a sor színe: zöld = egyezik, piros = eltér.
            function recalc() {
                let osszElvart = 0, osszSzamolt = 0, elteroSorok = 0;
                $cont.find('.js-ellsor').each(function () {
                    const $sor = $(this),
                        elvart = num($sor.data('elvart')),
                        szamolt = num($sor.find('.js-ellszamolt').val()),
                        elteres = szamolt - elvart;
                    osszElvart += elvart;
                    osszSzamolt += szamolt;
                    $sor.find('.js-ellelteres').text(fmt(elteres));
                    $sor.removeClass('redtext');
                    if (Math.abs(elteres) >= TURES) {
                        $sor.addClass('redtext');
                        elteroSorok++;
                    }
                });
                $cont.find('.js-ellosszelvart').text(fmt(osszElvart));
                $cont.find('.js-ellosszszamolt').text(fmt(osszSzamolt));
                $cont.find('.js-ellosszelteres').text(fmt(osszSzamolt - osszElvart));
                const $ossz = $cont.find('.js-ellosszegzes').removeClass('redtext');
                if (elteroSorok === 0) {
                    $ossz.text('Minden tétel egyezik a bizonylattal.');
                } else {
                    $ossz.addClass('redtext').text(elteroSorok + ' tétel eltér a bizonylattól.');
                }
            }

            // A beolvasott termék sora: pontos (termék + változat) egyezés, annak híján a változat
            // nélküli termék-sor; ha egyik sincs, új sor "nincs a bizonylaton" jelöléssel.
            function keresSor(termekid, valtozatid) {
                let $talalat = $cont.find('.js-ellsor').filter(function () {
                    return String($(this).data('termekid')) === String(termekid)
                        && String($(this).data('valtozatid')) === String(valtozatid);
                });
                if (!$talalat.length && valtozatid) {
                    $talalat = $cont.find('.js-ellsor').filter(function () {
                        return String($(this).data('termekid')) === String(termekid) && !num($(this).data('valtozatid'));
                    });
                }
                return $talalat.first();
            }

            function ujSor(tetel) {
                const $sor = $('<tr class="js-ellsor" data-elvart="0"></tr>')
                    .attr('data-termekid', tetel.termekid)
                    .attr('data-valtozatid', tetel.valtozatid || 0);
                $sor.append($('<td class="datacell"></td>').text(tetel.cikkszam || ''));
                $sor.append($('<td class="datacell"></td>').text(tetel.nev || ''));
                $sor.append('<td class="datacell"></td>');
                $sor.append('<td class="datacell"></td>');
                $sor.append('<td class="datacell textalignright">0</td>');
                $sor.append('<td class="datacell textalignright"><input class="js-ellszamolt" type="number" step="any" value="0" size="6"></td>');
                $sor.append('<td class="datacell textalignright js-ellelteres"></td>');
                $sor.append($('<td class="datacell"></td>').text(NINCSABIZONYLATON));
                tbody().append($sor);
                return $sor;
            }

            function hozzaad(tetel) {
                let $sor = keresSor(tetel.termekid, tetel.valtozatid || 0);
                if (!$sor.length) {
                    $sor = ujSor(tetel);
                }
                const $inp = $sor.find('.js-ellszamolt');
                $inp.val(fmt(num($inp.val()) + mennyiseg()));
                $sor.addClass('ui-state-highlight');
                setTimeout(function () {
                    $sor.removeClass('ui-state-highlight');
                }, 800);
                recalc();
                $cont.find('.js-ellvaltozat').empty();
                $kereso.val('').trigger('focus');
            }

            function valtozatValaszto(res) {
                const $doboz = $cont.find('.js-ellvaltozat').empty();
                const $sel = $('<select class="js-ellvaltozatvalaszto"></select>')
                    .append($('<option value=""></option>').text('Válasszon változatot'));
                res.valtozatok.forEach(function (v) {
                    $sel.append($('<option></option>').val(v.id).text(v.nev + (v.vonalkod ? ' (' + v.vonalkod + ')' : '')));
                });
                $doboz.append($('<label></label>').text(res.nev + ' – változat: ')).append($sel)
                    .append(' ').append($('<a href="#" class="js-ellvaltozatmegse">Mégse</a>'));
                $sel.data('termek', res).trigger('focus');
            }

            function handleResult(res) {
                if (!res || !res.mode || res.mode === 'none') {
                    $hiba.text('Nincs találat.');
                    $kereso.trigger('focus');
                    return;
                }
                $hiba.text('');
                if (res.mode === 'valtozat') {
                    valtozatValaszto(res);
                    return;
                }
                hozzaad(res);
            }

            function loadByKod(kod) {
                $hiba.text('');
                $.ajax({
                    url: URL_FINDTERMEK,
                    data: {vonalkod: kod},
                    dataType: 'json',
                    success: handleResult,
                    error: function () {
                        $hiba.text('Hiba a keresés közben.');
                    }
                });
            }

            $kereso.autocomplete({
                minLength: 3,
                delay: 200,
                autoFocus: false,
                source: function (request, response) {
                    $.ajax({
                        url: URL_KERESES,
                        data: {term: request.term},
                        dataType: 'json',
                        success: response
                    });
                },
                focus: function () {
                    // Navigáláskor ne írja felül a beírt szöveget (maradjon a vonalkód).
                    return false;
                },
                select: function (event, ui) {
                    productSelected = true;
                    $(this).val('');
                    $.ajax({
                        url: URL_GETTERMEK,
                        data: {termekid: ui.item.id},
                        dataType: 'json',
                        success: handleResult
                    });
                    return false;
                }
            });

            $kereso.on('keydown', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                    if (productSelected) {
                        productSelected = false;
                        return;
                    }
                    const kod = ($kereso.val() || '').trim();
                    $kereso.autocomplete('close');
                    $kereso.val('');
                    if (kod !== '') {
                        loadByKod(kod);
                    }
                } else {
                    productSelected = false;
                }
            });

            // A lapon nincs mentés: az Enter soha ne küldje be a formot.
            $('#mattkarb-form').on('keydown', function (e) {
                if (e.which === 13) {
                    e.preventDefault();
                }
            });

            $cont.on('change', '.js-ellvaltozatvalaszto', function () {
                const $sel = $(this),
                    res = $sel.data('termek'),
                    valtozatid = $sel.val();
                if (!valtozatid) {
                    return;
                }
                const valtozat = res.valtozatok.filter(function (v) {
                    return String(v.id) === String(valtozatid);
                })[0];
                hozzaad({
                    termekid: res.termekid,
                    valtozatid: valtozatid,
                    cikkszam: '',
                    nev: res.nev + (valtozat ? ' (' + valtozat.nev + ')' : '')
                });
            });

            $cont.on('click', '.js-ellvaltozatmegse', function (e) {
                e.preventDefault();
                $cont.find('.js-ellvaltozat').empty();
                $kereso.trigger('focus');
            });

            $cont.on('change keyup', '.js-ellszamolt', recalc);

            $cont.find('.js-ellujra').button().on('click', function (e) {
                e.preventDefault();
                $cont.find('.js-ellsor').each(function () {
                    const $sor = $(this);
                    if (num($sor.data('elvart')) === 0 && $sor.find('td').last().text() === NINCSABIZONYLATON) {
                        $sor.remove();
                    } else {
                        $sor.find('.js-ellszamolt').val('0');
                    }
                });
                recalc();
                $kereso.val('').trigger('focus');
            });

            recalc();
            $kereso.trigger('focus');
        }
    }));
});
