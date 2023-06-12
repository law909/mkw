$(document).ready(function () {
    var dialogcenter = $('#dialogcenter');

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

    var mattkarbconfig = {
        container: '#mattkarb',
        viewUrl: '/admin/banktranzakcio/getkarb',
        newWindowUrl: '/admin/banktranzakcio/viewkarb',
        saveUrl: '/admin/banktranzakcio/save',
        onSubmit: function () {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click', messagecenterclick)
                .slideToggle('slow');
        },
        beforeShow: function () {
            $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                .autocomplete("instance")._renderItem = partnerAutocompleteRenderer;
        }
    }

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
        $('#maincheckbox').change(function () {
            $('.egyedcheckbox').attr('checked', $(this).attr('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, mattkarbconfig, {independent: true}));
        }
    }
});