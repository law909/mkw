$(document).ready(function() {
    var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        beforeShow: function() {

            $('.js-exportbutton').on('click', function(e) {
                var $ff;
                e.preventDefault();
                $ff = $('#rlbcsvexport');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

        }
    });
});