$(document).ready(function() {
    var emailtemplate = {
        container: '#mattkarb',
        viewUrl: '/admin/helyszin/getkarb',
        newWindowUrl: '/admin/helyszin/viewkarb',
        saveUrl: '/admin/helyszin/save',
        beforeShow: function() {
            var edit = $('#LeirasEdit');
            if (!$.browser.mobile) {
                CKFinder.setupCKEditor(null, '/ckfinder/');
                edit.ckeditor();
            }
        },
        beforeHide: function() {
            var edit = $('#LeirasEdit'),
                editor;
            if (!$.browser.mobile) {
                editor = edit.ckeditorGet();
                if (editor) {
                    editor.destroy();
                }
            }
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
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/helyszin/getlistbody'
            },
            karb: emailtemplate
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