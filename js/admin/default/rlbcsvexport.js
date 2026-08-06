$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            $('.js-exportbutton').on('click', function (e) {
                var $ff;
                e.preventDefault();
                $ff = $('#rlbcsvexport');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

        }
    }));
});