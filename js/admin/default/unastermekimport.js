$(document).ready(function () {

    var form = $('#unastermekimport'),
        haladas = $('#unashaladas'),
        haladasSzoveg = $('#unashaladasszoveg'),
        haladasCsik = $('#unashaladascsik'),
        eredmeny = $('#unaseredmeny');

    $('#mattkarb').mattkarb(new MattkarbConfig({}));

    var hibaDoboz = function (uzenet) {
        return $('<div>')
            .addClass('matt-messagecenter ui-widget ui-state-error')
            .css({padding: '5px', margin: '5px 0'})
            .text(uzenet);
    };

    var haladasFrissit = function (kesz, osszes) {
        var szazalek = osszes > 0 ? Math.round(kesz * 100 / osszes) : 0;
        haladasSzoveg.text(kesz + ' / ' + osszes + ' (' + szazalek + '%)');
        haladasCsik.css('width', szazalek + '%');
    };

    // a szolgáltatás inkrementális menetben úgyis kikapcsolja – itt csak látszódjon is
    var inkrementalis = $('#InkrementalisEdit'),
        unasidkihagy = $('#UnasidkihagyEdit'),
        unasidkihagyElozo = unasidkihagy.prop('checked');

    inkrementalis.on('change', function () {
        if (this.checked) {
            unasidkihagyElozo = unasidkihagy.prop('checked');
            unasidkihagy.prop({checked: false, disabled: true});
        } else {
            unasidkihagy.prop({checked: unasidkihagyElozo, disabled: false});
        }
    }).trigger('change');

    $('#unasteszt').on('click', function () {
        var valasz = $('#unastesztvalasz').text('...');
        $.ajax({url: '/admin/unastermekimport/teszt', type: 'POST', dataType: 'json'})
            .done(function (data) {
                if (!data.ok) {
                    valasz.html($('<span>').addClass('ui-state-error-text').text(data.hiba));
                    return;
                }
                var szoveg = 'ShopId: ' + data.shopid + ', csomag: ' + data.subscription
                    + ', jogosultságok: ' + (data.permissions || []).join(', ');
                if (data.hianyzo && data.hianyzo.length) {
                    szoveg += ' — HIÁNYZIK: ' + data.hianyzo.join(', ');
                    valasz.html($('<span>').addClass('ui-state-error-text').text(szoveg));
                } else {
                    valasz.text(szoveg);
                }
            })
            .fail(function () {
                valasz.html($('<span>').addClass('ui-state-error-text').text('A teszt nem futott le.'));
            });
    });

    $('#unasriporttorles').on('click', function () {
        var gomb = $(this),
            valasz = $('#unasriporttorlesvalasz'),
            szaraz = $('#SzarazfutasEdit').is(':checked');
        if (!szaraz && !window.confirm(gomb.attr('data-kerdes'))) {
            return;
        }
        gomb.prop('disabled', true);
        $.ajax({
            url: '/admin/unastermekimport/riporttorles',
            type: 'POST',
            data: {szarazfutas: szaraz ? 1 : 0},
            dataType: 'json'
        })
            .done(function (data) {
                if (data.ok) {
                    if (data.szarazfutas) {
                        valasz.text('Száraz futás: ' + data.db + ' fájl törlődne.');
                    } else {
                        eredmeny.empty();
                        valasz.text(data.db + ' fájl törölve.');
                    }
                } else {
                    valasz.html($('<span>').addClass('ui-state-error-text').text(data.hiba));
                }
            })
            .fail(function () {
                valasz.html($('<span>').addClass('ui-state-error-text').text('A törlés nem futott le.'));
            })
            .always(function () {
                gomb.prop('disabled', false);
            });
    });

    $('#unasstop').on('click', function () {
        $.ajax({url: '/admin/unastermekimport/stop', type: 'POST', dataType: 'json'})
            .always(function () {
                window.location.reload();
            });
    });

    var riportBetolt = function (fajl) {
        $.ajax({
            url: form.attr('data-riporturl'),
            type: 'POST',
            data: {fajl: fajl},
            dataType: 'json'
        })
            .done(function (data) {
                if (data.ok) {
                    eredmeny.html(data.html);
                } else {
                    eredmeny.html(hibaDoboz(data.hiba));
                }
            })
            .fail(function () {
                eredmeny.html(hibaDoboz(form.attr('data-hibauzenet')));
            })
            .always(function () {
                form.find('input[type=submit]').prop('disabled', false);
            });
    };

    // önmagát hívja, amíg a fájl végére nem érünk
    var kotegFuttat = function (fajl, tol, osszes) {
        $.ajax({
            url: form.attr('data-kotegurl'),
            type: 'POST',
            data: {fajl: fajl, tol: tol},
            dataType: 'json'
        })
            .done(function (data) {
                if (!data.ok) {
                    eredmeny.html(hibaDoboz(data.hiba));
                    form.find('input[type=submit]').prop('disabled', false);
                    return;
                }
                haladasFrissit(data.kovetkezo, osszes);
                if (data.kesz) {
                    // sorablakos futásnál a mezők a következő szakaszra ugranak
                    if (data.kovetkezo_sortol) {
                        $('#SortolEdit').val(data.kovetkezo_sortol);
                        $('#SorigEdit').val(data.kovetkezo_sorig);
                        // a folytatás ugyanazt a fájlt dolgozza fel, újraletöltés nem való hozzá
                        $('#UjraletoltesEdit').prop('checked', false);
                    } else if (typeof data.kovetkezo_sortol !== 'undefined') {
                        $('#SortolEdit').val(0);
                        $('#SorigEdit').val(0);
                    }
                    riportBetolt(fajl);
                    return;
                }
                kotegFuttat(fajl, data.kovetkezo, osszes);
            })
            .fail(function () {
                eredmeny.html(hibaDoboz(form.attr('data-hibauzenet')));
                form.find('input[type=submit]').prop('disabled', false);
            });
    };

    form.on('submit', function (e) {
        e.preventDefault();

        eredmeny.empty();
        haladas.show();
        haladasFrissit(0, 0);
        haladasSzoveg.text('A termékadatbázis lekérése...');
        form.find('input[type=submit]').prop('disabled', true);

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json'
        })
            .done(function (data) {
                if (!data.ok) {
                    eredmeny.html(hibaDoboz(data.hiba));
                    haladas.hide();
                    form.find('input[type=submit]').prop('disabled', false);
                    return;
                }
                eredmeny.append($('<div>').css('margin', '5px 0').text(
                    (data.ujrahasznalt ? 'Korábbi fájl (' + data.letoltve + '): ' : 'Letöltve: ')
                    + data.fajl + ' (' + data.sorok + ' sor, feldolgozás: '
                    + (data.kezdosor + 1) + '-' + data.osszes + ', ' + data.meret + ' bájt), '
                    + 'képforrás: ' + data.kepforras + ', '
                    + 'megtalált oszlopok: ' + (data.oszlopok || []).join(', ')
                    + (data.hianyzo && data.hianyzo.length ? ' | hiányzó: ' + data.hianyzo.join(', ') : '')
                ));
                if (data.kezdosor >= data.osszes) {
                    haladas.hide();
                    form.find('input[type=submit]').prop('disabled', false);
                    return;
                }
                haladasFrissit(data.kezdosor, data.osszes);
                kotegFuttat(data.fajl, data.kezdosor, data.osszes);
            })
            .fail(function () {
                eredmeny.html(hibaDoboz(form.attr('data-hibauzenet')));
                haladas.hide();
                form.find('input[type=submit]').prop('disabled', false);
            });

        return false;
    });
});
