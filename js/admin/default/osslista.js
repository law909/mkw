$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            $('.js-okbutton, .js-exportbutton').on('click', function (e) {
                e.preventDefault();
                $('#osslista').attr('action', $(this).attr('href')).submit();
            }).button();
        }
    }));
});
