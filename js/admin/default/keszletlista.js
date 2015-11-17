$(document).ready(function() {
    var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        beforeShow: function() {

            var $datumedit = $('#DatumEdit');
            if ($datumedit) {
                $datumedit.datepicker($.datepicker.regional['hu']);
                $datumedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                $datumedit.datepicker('setDate', $datumedit.attr('data-datum'));
            }

            $('.js-okbutton').on('click', function(e) {
                var $ff;
                e.preventDefault();
                $ff = $('#keszlet');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

            $('.js-exportbutton').on('click', function(e) {
                var $ff;
                e.preventDefault();
                $ff = $('#keszlet');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

        }
    });
});