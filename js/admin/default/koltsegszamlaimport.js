$(document).ready(function () {

    // A datepicker a data-datum attribútumból veszi az értéket, ezért azt is állítjuk. A
    // datepicker-hívás csak inicializált mezőn működik, egyébként kivételt dob.
    var setDatum = function (sel, ertek) {
        var $mezo = $(sel).attr('data-datum', ertek).val(ertek);
        if ($mezo.data('datepicker')) {
            mkwcomp.datumEdit.clear($mezo);
        }
    };

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');
        }
    }));

    // Az importálás AJAX-ból megy, hogy a böngésző URL-je ne változzon meg (és az oldal
    // frissítése se indítsa újra a letöltést). A letöltés lassú – a NAV-tól számlánként
    // jönnek az adatok –, ezért addig letiltjuk a képernyőt, az előző futás eredményét pedig
    // eltakarítjuk.
    $('#koltsegszamlaimport').on('submit', function (e) {
        e.preventDefault();

        var form = $(this),
            eredmeny = $('#importeredmeny');

        eredmeny.empty();
        pleaseWait();

        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            dataType: 'json'
        })
            .done(function (data) {
                eredmeny.html(data.html);
                // a következő import felkínált időszaka
                setDatum('#TolEdit', data.toldatum);
                setDatum('#IgEdit', data.igdatum);
            })
            .fail(function () {
                eredmeny.html($('<div>')
                    .addClass('matt-messagecenter ui-widget ui-state-error')
                    .css({padding: '5px', margin: '5px 0'})
                    .text(form.attr('data-hibauzenet')));
            })
            .always(function () {
                $.unblockUI();
            });

        return false;
    });
});
