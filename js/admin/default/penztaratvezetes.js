$(document).ready(function () {

    // A pénztár valutaneme a bizonylatra kerül – csak megmutatjuk, szerkeszteni nem lehet.
    // A "honnan" pénztár a mérvadó.
    function syncValutanem() {
        var v = $('#HonnanPenztarEdit option:selected').data('valutanem');
        $('#ValutanemEdit').val(v ? v : '');
    }

    function uzenet(szoveg) {
        $('#dialogcenter').html(szoveg).dialog({
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

    // A pénztárbizonylat rögzítőjével azonos ellenőrzés (penztarbizonylat.js), csak itt
    // mindkét pénztárra le kell futnia.
    function checkPenztarDatum(kelt, penztar) {
        var retval = false;
        $.ajax({
            async: false,
            url: '/admin/penztarbizonylatfej/checkdatum',
            data: {
                datum: kelt,
                penztar: penztar
            },
            success: function (data) {
                var d = JSON.parse(data);
                if (d.response == 'ok') {
                    retval = true;
                }
            }
        });
        return retval;
    }

    // Mentés előtti ellenőrzés: a kötelező mezőkről a böngésző HTML5 ellenőrzése
    // gondoskodik, itt a csak összevetésből látszó hibák maradnak. Hamis visszatérésre a
    // jquery.mattkarb.js elhagyja a mentést, és a form a képernyőn marad.
    function ellenoriz() {
        var honnan = $('#HonnanPenztarEdit option:selected'),
            hova = $('#HovaPenztarEdit option:selected'),
            kelt = $('#KeltEdit').datepicker('getDate'),
            i;
        if (honnan.val() === hova.val()) {
            uzenet('A két pénztár nem lehet ugyanaz.');
            return false;
        }
        if (honnan.data('valutanem') != hova.data('valutanem')) {
            uzenet('A két pénztár valutaneme különbözik.');
            return false;
        }
        kelt = kelt.getFullYear() + '.' + (kelt.getMonth() + 1) + '.' + kelt.getDate();
        for (i = 0; i < 2; i++) {
            var p = i ? hova : honnan;
            if (!checkPenztarDatum(kelt, p.val())) {
                uzenet('Az időszakra a pénztár le van zárva: ' + p.text());
                return false;
            }
        }
        return true;
    }

    const penztaratvezetes = new MattkarbConfig({
        entityName: 'penztaratvezetes',
        beforeSerialize: function (form, opt) {
            return ellenoriz();
        },
        beforeShow: function () {
            mkwcomp.datumEdit.init('#KeltEdit');

            $('#AltalanosTab').on('change', '#HonnanPenztarEdit', function () {
                syncValutanem();
            });
            syncValutanem();
        },
    });

    if ($.fn.mattkarb) {
        $('#mattkarb').mattkarb(penztaratvezetes);
    }
});
