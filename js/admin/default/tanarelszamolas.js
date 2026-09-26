$(document).ready(function () {

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');
            $('.js-refresh')
                .on('click', function () {

                    $.ajax({
                        url: '/admin/tanarelszamolas/refresh',
                        type: 'GET',
                        data: {
                            tol: $('input[name="tol"]').val(),
                            ig: $('input[name="ig"]').val(),
                        },
                        success: function (d) {
                            $('#eredmeny').html(d);
                        }
                    })
                })
                .button();
        },
    }));
});