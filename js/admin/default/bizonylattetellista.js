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

                    var fak, fafilter, biztipusfilter, partnercimkefilter;
                    fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                    if (fak.length > 0) {
                        fafilter = fak;
                    }

                    partnercimkefilter = mkwcomp.partnercimkeFilter.getFilter('.js-cimkefilter');

                    biztipusfilter = mkwcomp.bizonylattipusFilter.getFilter('input[name="bizonylattipus[]"]');

                    $.ajax({
                        url: '/admin/bizonylattetellista/refresh',
                        type: 'GET',
                        data: {
                            datumtipus: $('select[name="datumtipus"] option:selected').val(),
                            tol: $('input[name="tol"]').val(),
                            ig: $('input[name="ig"]').val(),
                            raktar: $('select[name="raktar"] option:selected').val(),
                            gyarto: $('select[name="gyarto"] option:selected').val(),
                            uzletkoto: $('select[name="uzletkoto"] option:selected').val(),
                            partner: $('select[name="partner"] option:selected').val(),
                            fizmod: $('select[name="fizmod"] option:selected').val(),
                            forgalomfilter: $('select[name="forgalomfilter"] option:selected').val(),
                            ertektipus: $('select[name="ertektipus"] option:selected').val(),
                            arsav: $('select[name="arsav"] option:selected').val(),
                            nevfilter: $('input[name="nevfilter"]').val(),
                            nyelv: $('select[name="nyelv"] option:selected').val(),
                            fafilter: fafilter,
                            bizonylatstatusz: $('select[name="bizonylatstatusz"] option:selected').val(),
                            bizonylatstatuszcsoport: $('select[name="bizonylatstatuszcsoport"] option:selected').val(),
                            bizonylattipus: biztipusfilter,
                            partnercimkefilter: partnercimkefilter,
                            csoportositas: $('select[name="csoportositas"] option:selected').val(),
                            keszletkell: $('input[name="keszletkell"]').prop('checked')
                        },
                        success: function(d) {
                            $('#eredmeny').html(d);
                        }
                    })
                })
                .button();

            $('.js-exportbutton').on('click', function(e) {
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