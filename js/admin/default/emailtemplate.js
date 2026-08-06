$(document).ready(function () {
    const emailtemplate = new MattkarbConfig({
        entityName: 'emailtemplate',
        beforeShow: function () {
            var edit = $('#LeirasEdit');
            if (!window.mkwIsMobile) {
                CKFinder.setupCKEditor(null, '/ckfinder/');
                edit.ckeditor();
            }
        },
        beforeHide: function () {
            var edit = $('#LeirasEdit'),
                editor;
            if (!window.mkwIsMobile) {
                editor = edit.ckeditorGet();
                if (editor) {
                    editor.destroy();
                }
            }
        },
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/emailtemplate/getlistbody'
            },
            karb: emailtemplate
        });
        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(emailtemplate);
        }
    }
});