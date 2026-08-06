$(document).ready(function () {

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function partnerAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylatfej/getpartnerlist',
            select: function (event, ui) {
                var partner = ui.item,
                    pi = $('input[name="partner"]');
                if (partner) {
                    pi.val(partner.id);
                    pi.change();
                }
            }
        };
    }

    $('#mattkarb').mattkarb(new MattkarbConfig({
            beforeShow: function () {
                mkwcomp.datumEdit.init('#TolEdit');
                mkwcomp.datumEdit.init('#IgEdit');

                $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                    .autocompleteRenderer(partnerAutocompleteRenderer);

                $('.js-refresh')
                    .on('click', function () {

                        const partnercimkefilter = mkwcomp.partnercimkeFilter.getFilter('.js-cimkefilter');
                        let partnerid;
                        if (isPartnerAutocomplete()) {
                            partnerid = $('.js-partnerid').val();
                        } else {
                            partnerid = $('#PartnerEdit option:selected').val();
                        }

                        $.ajax({
                            url: '/admin/bizomanyosertekesiteslista/refresh',
                            type: 'GET',
                            data: {
                                datumtipus: $('select[name="datumtipus"]').val(),
                                datumtol: $('input[name="tol"]').val(),
                                datumig: $('input[name="ig"]').val(),
                                partnerid: partnerid,
                                ertektipus: $('select[name="ertektipus"]').val(),
                                arsav: $('select[name="arsav"]').val(),
                                partnercimkefilter: partnercimkefilter
                            },
                            success: function (d) {
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
                $('.js-cimkefilter').on('click', function (e) {
                    e.preventDefault();
                    $(this).toggleClass('ui-state-hover');
                });
            },
        })
    );
});