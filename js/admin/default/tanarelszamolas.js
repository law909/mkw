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
                        url: '/admin/tanarelszamolas/refresh',
                        type: 'GET',
                        data: {
                            tol: $('input[name="tol"]').val(),
                            ig: $('input[name="ig"]').val(),
                        },
                        success: function(d) {
                            $('#eredmeny').html(d);
                        }
                    })
                })
                .button();

            $('.js-exportbutton').on('click', function(e) {
                e.preventDefault();

                $ff = $('#b');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

            $('.js-print').on('click', function(e) {
                var fak, fafilter, $ff, partnercimkefilter;
                e.preventDefault();

                fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                if (fak.length > 0) {
                    fafilter = fak;
                }
                $('#FaFilter').val(fafilter);

                partnercimkefilter = mkwcomp.partnercimkeFilter.getFilter('.js-cimkefilter');
                $('#PartnerCimkeFilter').val(partnercimkefilter);

                $ff = $('#bizonylattetel');
                $ff.attr('action', $(this).attr('href'));
                $ff.submit();
            }).button();

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