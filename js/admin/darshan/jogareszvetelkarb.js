$(document).ready(function() {
    var jogareszvetel = {
        container: '#mattkarb',
        viewUrl: '/admin/jogareszvetel/getkarb',
        newWindowUrl: '/admin/jogareszvetel/viewkarb',
        saveUrl: '/admin/jogareszvetel/save',
        beforeShow: function() {
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
        mkwcomp.datumEdit.init('#datumtolfilter');
        mkwcomp.datumEdit.init('#datumigfilter');
        $('#mattable-select').mattable({
            filter: {
                fields: ['#datumtolfilter', '#datumigfilter', '#partnernevfilter', '#partneremailfilter']
            },
            tablebody: {
                url: '/admin/jogareszvetel/getlistbody'
            },
            karb: jogareszvetel
        });
        $('.js-maincheckbox').change(function() {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    }
    else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, emailtemplate, {independent: true}));
        }
    }
});