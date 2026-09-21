$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            mkwcomp.datumEdit.init('#DatumEdit');
            mkwcomp.termekfaFilter.init('#termekfa');

            const setFaFilter = () => {
                const fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                $('input[name="fafilter"]').val(fak.length > 0 ? fak : '');
            };

            // A riport és az Excel ugyanazt az űrlapot küldi, csak más útvonalra, új ablakba.
            $('.js-okbutton, .js-exportbutton').on('click', function (e) {
                e.preventDefault();
                setFaFilter();
                const $ff = $('#gyartoirendeles');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

            // A szállítói megrendelés viszont ír, ezért POST-tal megy, és csak utána nyitjuk meg
            // az elkészült bizonylatot.
            $('.js-bizonylatbutton').on('click', function (e) {
                e.preventDefault();
                if (!$('#GyartoEdit').val()) {
                    alert('A szállítói megrendeléshez gyártót kell választani.');
                    return;
                }
                setFaFilter();
                const $gomb = $(this);
                $gomb.button('disable');
                $.ajax({
                    type: 'POST',
                    url: $gomb.attr('href'),
                    dataType: 'json',
                    data: $('#gyartoirendeles').serialize(),
                    success: (d) => {
                        if (!d || !d.ok) {
                            alert((d && d.error) ? d.error : 'A szállítói megrendelés nem készült el.');
                            return;
                        }
                        window.open('/admin/szallmegrfej/viewkarb?id=' + encodeURIComponent(d.bizonylatszam) + '&oper=edit', '_blank');
                    },
                    error: () => {
                        alert('A szállítói megrendelés nem készült el.');
                    },
                    complete: () => {
                        $gomb.button('enable');
                    }
                });
            }).button();

        }
    }));
});
