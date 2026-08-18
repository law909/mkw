$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            $('.js-okbutton, .js-exportbutton').on('click', function (e) {
                let $ff;
                e.preventDefault();
                $ff = $('#folyoszamlaellenorzes');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

        }
    }));
});
