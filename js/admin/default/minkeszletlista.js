$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            mkwcomp.datumEdit.init('#DatumEdit');
            mkwcomp.termekfaFilter.init('#termekfa');

            // a pipa állását a bejelentkezett dolgozóhoz mentjük (dolgozoparameterek),
            // ugyanazzal a kulccsal, amit a controller olvas vissza
            $('#KeszletSzamitEdit').on('change', function () {
                $.ajax({
                    url: '/admin/setlistparam',
                    type: 'POST',
                    global: false,
                    data: {
                        key: window.location.pathname,
                        par: 'minkeszletalattkeszletszamit',
                        value: $(this).prop('checked') ? 1 : 0
                    }
                });
            });

            // Mindhárom gomb ugyanazt az űrlapot küldi, csak más útvonalra: képernyős riport,
            // Excel export, és a bizonylatkészítéshez való szűkebb Excel.
            $('.js-okbutton, .js-exportbutton, .js-exportbizonylatbutton').on('click', function (e) {
                var $ff = $('#minkeszlet'),
                    fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                e.preventDefault();
                $('input[name="fafilter"]').val(fak.length > 0 ? fak : '');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

        }
    }));
});
