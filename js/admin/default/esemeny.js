$(document).ready(function () {
    const esemeny = new MattkarbConfig({
        entityName: 'esemeny',
        beforeShow: function () {
            if (!window.mkwIsMobile) {
                CKFinder.setupCKEditor(null, '/ckfinder/');
                $('#LeirasEdit').ckeditor();
            }
            var esedekesedit = $('#EsedekesEdit');
            esedekesedit.datepicker($.datepicker.regional['hu']);
            esedekesedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            esedekesedit.datepicker('setDate', esedekesedit.attr('data-esedekes'));
        },
        beforeHide: function () {
            if (!window.mkwIsMobile) {
                editor = $('#LeirasEdit').ckeditorGet();
                if (editor) {
                    editor.destroy();
                }
            }
        },
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            name: 'egyed',
            filter: {
                fields: ['#bejegyzesfilter', '#dtfilter', '#difilter']
            },
            tablebody: {
                url: '/admin/esemeny/getlistbody'
            },
            karb: esemeny
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        var dfilter = $('#dtfilter');
        dfilter.datepicker($.datepicker.regional['hu']);
        dfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
        dfilter = $('#difilter');
        dfilter.datepicker($.datepicker.regional['hu']);
        dfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(esemeny);
        }
    }
});