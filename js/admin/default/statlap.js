$(document).ready(function () {
    const statlap = new MattkarbConfig({
        entityName: 'statlap',
        beforeShow: function () {
            if (!window.mkwIsMobile) {
                CKFinder.setupCKEditor(null, '/ckfinder/');
                $('.js-ckeditor').each(function () {
                    $(this).ckeditor();
                });
            }
        },
        beforeHide: function () {
            if (!window.mkwIsMobile) {
                let editor;
                $('.js-ckeditor').each(function () {
                    editor = $(this).ckeditorGet();
                    if (editor) {
                        editor.destroy();
                    }
                });
            }
        },
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/statlap/getlistbody'
            },
            karb: statlap
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(statlap);
        }
    }
});