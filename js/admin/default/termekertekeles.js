$(document).ready(function() {

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function partnerAutocompleteRenderer(ul, item) {
        return $('<li>')
            .append('<a>' + item.value + '</a>')
            .appendTo( ul );
    }

    function partnerAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylatfej/getpartnerlist',
            select: function(event, ui) {
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

    function termekAutocompleteRenderer(ul, item) {
        if (item.nemlathato) {
            return $('<li>')
                .append('<a class="nemelerhetovaltozat">' + item.label + '</a>')
                .appendTo( ul );
        }
        else {
            return $('<li>')
                .append('<a>' + item.label + '</a>')
                .appendTo( ul );
        }
    }

    function termekAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylattetel/gettermeklist',
            select: function(event, ui) {
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

    var termekertekeles = {
        container: '#mattkarb',
        viewUrl: '/admin/termekertekeles/getkarb',
        newWindowUrl: '/admin/termekertekeles/viewkarb',
        saveUrl: '/admin/termekertekeles/save',
        beforeShow: function() {
            $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                .autocomplete( "instance" )._renderItem = partnerAutocompleteRenderer;

            $('.js-termekselect').autocomplete(termekAutocompleteConfig())
                .autocomplete( "instance" )._renderItem = termekAutocompleteRenderer;
        },
        beforeHide: function() {
        },
        onSubmit: function() {
            $('#messagecenter')
                    .html('A mentés sikerült.')
                    .hide()
                    .addClass('matt-messagecenter ui-widget ui-state-highlight')
                    .one('click', messagecenterclick)
                    .slideToggle('slow');
        }
    }

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
        $('.js-maincheckbox').change(function() {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    }
    else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, termekertekeles, {independent: true}));
        }
    }
});