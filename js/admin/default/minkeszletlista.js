$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            mkwcomp.datumEdit.init('#DatumEdit');

            // Mindhárom gomb ugyanazt az űrlapot küldi, csak más útvonalra: képernyős riport,
            // Excel export, és a bizonylatkészítéshez való szűkebb Excel.
            $('.js-okbutton, .js-exportbutton, .js-exportbizonylatbutton').on('click', function (e) {
                var $ff = $('#minkeszlet');
                e.preventDefault();
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

        }
    }));
});
