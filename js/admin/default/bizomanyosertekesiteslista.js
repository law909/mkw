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

                    var partnercimkefilter = mkwcomp.partnercimkeFilter.getFilter('.js-cimkefilter');

                    $.ajax({
                        url: '/admin/bizomanyosertekesiteslista/refresh',
                        type: 'GET',
                        data: {
                            datumtipus: $('select[name="datumtipus"] option:selected').val(),
                            datumtol: $('input[name="tol"]').val(),
                            datumig: $('input[name="ig"]').val(),
                            partnerid: $('select[name="partner"] option:selected').val(),
                            ertektipus: $('select[name="ertektipus"] option:selected').val(),
                            arsav: $('select[name="arsav"] option:selected').val(),
                            partnercimkefilter: partnercimkefilter
                        },
                        success: function(d) {
                            $('#eredmeny').html(d);
                        }
                    })
                })
                .button();
            $('#cimkefiltercontainer').mattaccord({
                header: '',
                page: '.js-cimkefilterpage',
                closeUp: '.js-cimkefiltercloseupbutton'
            });
            $('.js-cimkefilter').on('click', function(e) {
                e.preventDefault();
                $(this).toggleClass('ui-state-hover');
            });
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