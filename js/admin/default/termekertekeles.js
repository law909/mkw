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
                    $('.js-ujpartnercb').prop('checked', false);
                    pi.change();
                }
            }
        };
    }

    function termekAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylattetel/gettermeklist',
            select: function (event, ui) {
                var termek = ui.item;
                if (termek) {
                    var $this = $(this),
                        sorid = $this.attr('name').split('_')[1];
                    $this.siblings().val(termek.id);
                    $('input[name="tetelnev_' + sorid + '"]').val(termek.value);
                }
            }
        };
    }

    const termekertekeles = new MattkarbConfig({
        entityName: 'termekertekeles',
        beforeShow: function () {
            $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                .autocompleteRenderer(partnerAutocompleteRenderer);

            $('.js-termekselect').autocomplete(termekAutocompleteConfig())
                .autocompleteRenderer(termekAutocompleteRenderer);
        },
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#partnerfilter']
            },
            tablebody: {
                url: '/admin/termekertekeles/getlistbody'
            },
            karb: termekertekeles
        });
        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(termekertekeles);
        }
    }
});