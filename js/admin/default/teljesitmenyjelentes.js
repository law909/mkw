$(document).ready(function() {
    var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        beforeShow: function() {

            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            $('.js-refresh')
                .on('click', function() {

                    $.ajax({
                        url: '/admin/teljesitmenyjelentes/refresh',
                        type: 'GET',
                        data: {
                            tol: mkwcomp.datumEdit.getDate('#TolEdit'),
                            ig: mkwcomp.datumEdit.getDate('#IgEdit')
                        },
                        success: function(d) {
                            $('#eredmeny').html(d);
                        }
                    })
                })
                .button();

        }
    });
});