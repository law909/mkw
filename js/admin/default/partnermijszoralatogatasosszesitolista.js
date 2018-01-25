$(document).ready(function() {
    var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent:true,
        viewUrl:'/admin/getkarb',
        newWindowUrl:'/admin/viewkarb',
        saveUrl:'/admin/save',
        beforeShow:function() {
            $('.js-refresh')
                .on('click', function() {

                    $.ajax({
                        url: '/admin/partnermijszoralatogatasosszesitolista/refresh',
                        type: 'GET',
                        data: {
                            ev: $('input[name="ev"]').val()
                        },
                        success: function(d) {
                            $('#eredmeny').html(d);
                        }
                    })
                })
                .button();
            $('.js-exportbutton').on('click', function(e) {
                e.preventDefault();

                $ff = $('#partnermijszoralatogatasosszesito');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();
        }
    });
});