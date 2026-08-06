$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            $('.js-okbutton').on('click', function (e) {
                let $ff;
                e.preventDefault();
                $ff = $('#munkaido');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

        }
    }));
});