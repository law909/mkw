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
            mkwcomp.termekfaFilter.init('#termekfa');
            $('.js-refresh')
                .on('click', function() {

                    var fak, fafilter;
                    fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                    if (fak.length > 0) {
                        fafilter = fak;
                    }

                    $.ajax({
                        url: '/admin/termekforgalmilista/refresh',
                        type: 'GET',
                        data: {
                            datumtipus: $('select[name="datumtipus"] option:selected').val(),
                            datumtol: $('input[name="tol"]').val(),
                            datumig: $('input[name="ig"]').val(),
                            raktarid: $('select[name="raktar"] option:selected').val(),
                            gyartoid: $('select[name="gyarto"] option:selected').val(),
                            partnerid: $('select[name="partner"] option:selected').val(),
                            keszletfilter: $('select[name="keszletfilter"] option:selected').val(),
                            forgalomfilter: $('select[name="forgalomfilter"] option:selected').val(),
                            ertektipus: $('select[name="ertektipus"] option:selected').val(),
                            arsav: $('select[name="arsav"] option:selected').val(),
                            nevfilter: $('input[name="nevfilter"]').val(),
                            fafilter: fafilter
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