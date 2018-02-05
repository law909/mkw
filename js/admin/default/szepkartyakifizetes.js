$(document).ready(function() {
    var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        beforeShow: function() {

            $('.js-kiegyenlit').on('click', function(e) {
                var bizid = $(this).data('egyedid');
                e.preventDefault();
                $.ajax({
                    url: '/admin/szepkartyakifizetes/kifizet',
                    type: 'POST',
                    data: {
                        id: bizid
                    },
                    success: function(data) {
                        $('table[data-egyedid="' + bizid + '"]').remove();
                    }
                });

            }).button();

        }
    });
});