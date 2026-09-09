$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            $('.js-okbutton').on('click', function (e) {
                e.preventDefault();
                const $ff = $('#jelenletiivgen');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

            // a letöltés nem új lapon indul, különben üres fül marad utána
            $('.js-exportbutton').on('click', function (e) {
                e.preventDefault();
                window.location = $(this).attr('href') + '?' + $('#jelenletiivgen').serialize();
            }).button();
        }
    }));
});
