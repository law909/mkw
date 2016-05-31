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
                            tol: $('input[name="tol"]').val(),
                            ig: $('input[name="ig"]').val(),
                            raktar: $('select[name="raktar"] option:selected').val(),
                            gyarto: $('select[name="gyarto"] option:selected').val(),
                            partner: $('select[name="partner"] option:selected').val(),
                            keszletfilter: $('select[name="keszletfilter"] option:selected').val(),
                            forgalomfilter: $('select[name="forgalomfilter"] option:selected').val(),
                            ertektipus: $('select[name="ertektipus"] option:selected').val(),
                            arsav: $('select[name="arsav"] option:selected').val(),
                            nevfilter: $('input[name="nevfilter"]').val(),
                            nyelv: $('select[name="nyelv"] option:selected').val(),
                            fafilter: fafilter
                        },
                        success: function(d) {
                            $('#eredmeny').html(d);
                        }
                    })
                })
                .button();
            $('.js-exportbutton').on('click', function(e) {
                var fak, fafilter, $ff;
                e.preventDefault();

                fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                if (fak.length > 0) {
                    fafilter = fak;
                }
                $('#FaFilter').val(fafilter);

                $ff = $('#termekforgalmi');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();
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