$(document).ready(function () {
    const dialogcenter = $('#dialogcenter');

    /**
     * A kérdőív szerkesztője az időpont karbantartó Kérdőív fülén. A kérdések blokkjait a rejtett
     * kerdoiv mező JSON-jából építi, és minden módosításkor vissza is írja – a szerver a JSON-t kapja.
     * Szerkezet: {cim, leiras, kerdesek: [{szoveg, tipus: egy|tobb|szoveg, kotelezo, valaszok: []}]}
     */
    function kerdoivSzerkeszto($tab) {
        const $json = $('#KerdoivEdit'),
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
                    id = $sel.val();
                if (!id) {
                    return;
                }
                $.ajax({
                    url: '/admin/idopont/getkerdoiv',
                    type: 'GET',
                    data: {id: id},
                    success: function (data) {
                        betolt(JSON.parse(data));
                        $sel.val('');
                    }
                });
            });
        $tab.find('.js-kerdoivujkerdes').button();
    }

    const mattkarbconfig = new MattkarbConfig({
        entityName: 'idopont',
        beforeShow: function () {
            // a vég a kezdetet követi, amíg a felhasználó nem nyúl hozzá
            $('#KezdetEdit').on('change', function () {
                const $veg = $('#VegEdit');
                if (!$veg.val()) {
                    $veg.val($(this).val());
                }
            });

            // az egyszeri és az ismétlődő megadás kizárja egymást. A vég nem kötelező: az átvett
            // rendezvényeknek csak kezdő időpontjuk volt
            function toggleIsmetlodo() {
                const ismetlodo = $('#IsmetlodoCheck').is(':checked');
                $('.js-egyszeriblokk').toggle(!ismetlodo);
                $('.js-ismetlodoblokk').toggle(ismetlodo);
                $('#KezdetEdit').prop('required', !ismetlodo);
                $('#NapEdit, #KezdetidoEdit').prop('required', ismetlodo);
            }

            $('#IsmetlodoCheck').on('change', toggleIsmetlodo);
            toggleIsmetlodo();

            mkwcomp.datumEdit.init('#EarlybirdvegeEdit');
            new ClipboardJS('.js-uidcopy');
            kerdoivSzerkeszto($('#KerdoivTab'));
        }
    });

    if ($.fn.mattable) {
        const datumtolfilter = $('#datumtolfilter'),
            datumigfilter = $('#datumigfilter');

        datumtolfilter.datepicker($.datepicker.regional['hu']);
        datumtolfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
        datumigfilter.datepicker($.datepicker.regional['hu']);
        datumigfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');

        $('#mattable-select').mattable({
            filter: {
                fields: [
                    '#tipusfilter',
                    '#nevfilter',
                    '#datumtolfilter',
                    '#datumigfilter',
                    '#dolgozofilter',
                    '#idoponttemafilter',
                    '#jogahelyszinfilter',
                    '#idopontallapotfilter',
                    '#inaktivfilter',
                    '#ismetlodofilter'
                ]
            },
            tablebody: {
                url: '/admin/idopont/getlistbody',
                onStyle: function () {
                    new ClipboardJS('.js-uidcopy');
                    $('.js-emailkezdes').button();
                }
            },
            karb: mattkarbconfig
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });

        $('#mattable-body')
            .on('click', '.js-flagcheckbox', function (e) {
                e.preventDefault();
                const $this = $(this);
                $.ajax({
                    url: '/admin/idopont/setflag',
                    type: 'POST',
                    data: {
                        id: $this.attr('data-id'),
                        flag: $this.attr('data-flag'),
                        kibe: !$this.is('.ui-state-hover')
                    },
                    success: function () {
                        $this.toggleClass('ui-state-hover');
                    }
                });
            })
            .on('click', '.js-emailkezdes', function (e) {
                e.preventDefault();
                const $gomb = $(this);
                $.ajax({
                    url: '/admin/idopont/email/kezdes',
                    type: 'POST',
                    data: {id: $gomb.data('egyedid')},
                    success: function (data) {
                        const d = JSON.parse(data);
                        dialogcenter.html(d.msg).dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                });
            });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(mattkarbconfig);
        }
    }
});
