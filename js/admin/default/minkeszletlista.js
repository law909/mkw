$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            mkwcomp.datumEdit.init('#DatumEdit');
            mkwcomp.termekfaFilter.init('#termekfa');

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
