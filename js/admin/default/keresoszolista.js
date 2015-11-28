$(document).ready(function() {
    var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        beforeShow: function() {

            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            $('.js-okbutton').on('click', function(e) {
                var $ff;
                e.preventDefault();
                $ff = $('#keresoszo');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();
        }
    });
});