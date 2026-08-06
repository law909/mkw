$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            $('.js-fifoalapadat,.js-keszletertek').on('click', function (e) {
                e.preventDefault();
                $ff = $('#fifoexport');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

            $('.js-fifocalc').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    data: {
                        storno: $('input[name="storno"]').prop('checked')
                    },
                    url: $(this).attr('href'),
                    success: function () {
                        alert('Kész.');
                    }
                });
            }).button();

        },
    }));
});