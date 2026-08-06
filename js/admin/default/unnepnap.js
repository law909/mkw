$(document).ready(function () {
    var mattkarbconfig = {
        container: '#mattkarb',
        viewUrl: '/admin/unnepnap/getkarb',
        newWindowUrl: '/admin/unnepnap/viewkarb',
        saveUrl: '/admin/unnepnap/save',
        beforeShow: function () {
            mkwcomp.datumEdit.init('#DatumEdit');
        },
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
            tablebody: {
                url: '/admin/unnepnap/getlistbody'
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
