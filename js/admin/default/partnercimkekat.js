$(document).ready(function () {
    var mattkarbconfig = {
        container: '#mattkarb',
        viewUrl: '/admin/partnercimkekat/getkarb',
        newWindowUrl: '/admin/partnercimkekat/viewkarb',
        saveUrl: '/admin/partnercimkekat/save',
        onSubmit: function () {
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
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/partnercimkekat/getlistbody'
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
