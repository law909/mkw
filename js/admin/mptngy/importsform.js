$(document).ready(function () {
    var dialogcenter = $('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        viewUrl: '/admin/getkarb',
        newWindowUrl: '/admin/viewkarb',
        saveUrl: '/admin/save',
        beforeShow: function () {

            $('.js-mptngybiraloimport').on('click', function (e) {
                e.preventDefault();
                var data = new FormData($('#mattkarb-form')[0]);
                $.ajax({
                    type: 'POST',
                    url: $(this).attr('href'),
                    processData: false,
                    contentType: false,
                    data: data
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