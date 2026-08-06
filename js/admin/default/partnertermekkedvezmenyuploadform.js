$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {

            $('.js-del').on('click', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('href'),
                    processData: false,
                    contentType: false,
                    success: function () {
                        alert('Kész.');
                    }
                });
            }).button();

            $('.js-upload').on('click', function (e) {
                e.preventDefault();
                let data = new FormData($('#uploadform')[0]);
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('href'),
                    processData: false,
                    contentType: false,
                    data: data,
                    success: function () {
                        alert('Kész.');
                    }
                });
            }).button();

        },
    }));
});