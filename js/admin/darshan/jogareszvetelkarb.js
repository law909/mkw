$(document).ready(function() {
    var jogareszvetel = {
        container: '#mattkarb',
        viewUrl: '/admin/jogareszvetel/getkarb',
        newWindowUrl: '/admin/jogareszvetel/viewkarb',
        saveUrl: '/admin/jogareszvetel/save',
        beforeShow: function() {
            $('.js-partneredit').on('change', function() {
                var $this = $(this);
                $.ajax({
                    url: '/admin/jogareszvetel/getselect',
                    data: {
                        partnerid: $this.val()
                    },
                    type: 'GET',
                    success: function(data) {
                        $('.js-berletedit').innerHTML(data);
                    }
                });
            });
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
                fields: ['#datumtolfilter', '#datumigfilter', '#partnernevfilter', '#partneremailfilter', '#tisztaznikellfilter', '#tanarfilter']
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