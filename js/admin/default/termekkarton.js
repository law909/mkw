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

            // Egyedi azonosító szűrő autocomplete: a termékhez (és kiválasztott változatához)
            // tartozó, bizonylattételekben szereplő azonosítókat ajánlja, 1 karakter után.
            $('.js-egyediazonositoszuro').autocomplete({
                minLength: 0,
                source: function (request, response) {
                    $.ajax({
                        url: '/admin/termekkarton/egyediazonositolista',
                        type: 'GET',
                        dataType: 'json',
                        data: {
                            termekid: $('input[name="termekid"]').val(),
                            valtozatid: $('select[name="valtozat"]').val(),
                            term: request.term
                        },
                        success: function (data) {
                            response(data);
                        }
                    });
                }
            });

            $('.js-refresh')
                .on('click', function () {

                    let partnercimkefilter = mkwcomp.partnercimkeFilter.getFilter('.js-cimkefilter'),
                        partnerid;
                    if (isPartnerAutocomplete()) {
                        partnerid = $('.js-partnerid').val();
                    } else {
                        partnerid = $('#PartnerEdit option:selected').val();
                    }

                    $.ajax({
                        url: '/admin/termekkarton/refresh',
                        type: 'GET',
                        data: {
                            termekid: $('input[name="termekid"]').val(),
                            valtozatid: $('select[name="valtozat"]').val(),
                            datumtipus: $('select[name="datumtipus"]').val(),
                            datumtol: $('input[name="tol"]').val(),
                            datumig: $('input[name="ig"]').val(),
                            mozgat: $('select[name="mozgat"]').val(),
                            rontott: $('select[name="rontott"]').val(),
                            raktarid: $('select[name="raktar"]').val(),
                            partnerid: partnerid,
                            partnercimkefilter: partnercimkefilter,
                            egyediazonosito: $('input[name="egyediazonosito"]').val()
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
    }));
});