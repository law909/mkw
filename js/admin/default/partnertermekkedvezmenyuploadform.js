$(document).ready(function () {
    var dialogcenter = $('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        viewUrl: '/admin/getkarb',
        newWindowUrl: '/admin/viewkarb',
        saveUrl: '/admin/save',
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
        onSubmit: function () {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click', messagecenterclick)
                .slideToggle('slow');
        }
    });
});