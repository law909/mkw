$(document).ready(function() {
    var rendezveny = {
        container: '#mattkarb',
        viewUrl: '/admin/rendezveny/getkarb',
        newWindowUrl: '/admin/rendezveny/viewkarb',
        saveUrl: '/admin/rendezveny/save',
        beforeShow: function() {
            var kezdodatumedit = $('#KezdodatumEdit');
            kezdodatumedit.datepicker($.datepicker.regional['hu']);
            kezdodatumedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            kezdodatumedit.datepicker('setDate', kezdodatumedit.attr('data-datum'));
        },
        onSubmit: function() {
            $('#messagecenter')
                    .html('A mentés sikerült.')
                    .hide()
                    .addClass('matt-messagecenter ui-widget ui-state-highlight')
                    .one('click', messagecenterclick)
                    .slideToggle('slow');
        }
    };

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/rendezveny/getlistbody'
            },
            karb: rendezveny
        });
        $('.js-maincheckbox').change(function() {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    }

    if ($.fn.mattkarb) {
        $('#mattkarb').mattkarb($.extend({}, rendezveny, {independent: true}));
    }
});