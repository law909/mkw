$(document).ready(function () {

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

    var mattkarbconfig = new MattkarbConfig({
        entityName: 'banktranzakcio',
        beforeShow: function () {
            $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                .autocompleteRenderer(partnerAutocompleteRenderer);
        }
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#azonositofilter']
            },
            tablebody: {
                url: '/admin/banktranzakcio/getlistbody'
            },
            karb: mattkarbconfig
        });
        $('.mattable-batchbtn').on('click', function (e) {
            var cbs;
            e.preventDefault();
            cbs = $('.js-egyedcheckbox:checked');
            var tomb = [], $exportform, $sel;
            cbs.closest('tr').each(function (index, elem) {
                tomb.push($(elem).data('egyedid'));
            });
            switch ($('.mattable-batchselect').val()) {
                case 'generatebankbiz':
                    $.ajax({
                        url: '/admin/banktranzakcio/generatebankbizonylat',
                        type: 'POST',
                        data: {
                            ids: tomb,
                        },
                        success: function (data) {
                            $('.mattable-tablerefresh').click();
                        }
                    });
                    break;
            }

        });

        $('#maincheckbox').change(function () {
            $('.egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(mattkarbconfig);
        }
    }
});