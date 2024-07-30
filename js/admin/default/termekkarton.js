$(document).ready(function () {
    const dialogcenter = $('#dialogcenter');

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function partnerAutocompleteRenderer(ul, item) {
        return $('<li>')
            .append('<a>' + item.value + '</a>')
            .appendTo(ul);
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

    $('#mattkarb').mattkarb({
        independent: true,
        viewUrl: '/admin/getkarb',
        newWindowUrl: '/admin/viewkarb',
        saveUrl: '/admin/save',
        beforeShow: function () {
            mkwcomp.datumEdit.init('#TolEdit');
            mkwcomp.datumEdit.init('#IgEdit');

            $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                .autocomplete("instance")._renderItem = partnerAutocompleteRenderer;

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
        onSubmit: function () {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click', messagecenterclick)
                .slideToggle('slow');
        }
    });
});