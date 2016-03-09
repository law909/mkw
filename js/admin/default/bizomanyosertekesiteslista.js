$(document).ready(function() {
    var dialogcenter=$('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent:true,
        viewUrl:'/admin/getkarb',
        newWindowUrl:'/admin/viewkarb',
        saveUrl:'/admin/save',
        beforeShow:function() {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');
            $('.js-refresh')
                .on('click', function() {
                    $.ajax({
                        url: '/admin/bizomanyosertekesiteslista/refresh',
                        type: 'GET',
                        data: {
                            datumtipus: $('select[name="datumtipus"] option:selected').val(),
                            datumtol: $('input[name="tol"]').val(),
                            datumig: $('input[name="ig"]').val(),
                            partnerid: $('select[name="partner"] option:selected').val(),
                            ertektipus: $('select[name="ertektipus"] option:selected').val(),
                            arsav: $('select[name="arsav"] option:selected').val()
                        },
                        success: function(d) {
                            $('#eredmeny').html(d);
                        }
                    })
                })
                .button();
        },
        onSubmit:function() {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click',messagecenterclick)
                .slideToggle('slow');
        }
    });
});