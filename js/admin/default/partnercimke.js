$(document).ready(function () {
    const partnercimke = new MattkarbConfig({
        entityName: 'partnercimke',
        beforeShow: function () {
            if (!window.mkwIsMobile) {
                CKFinder.setupCKEditor(null, '/ckfinder/');
                $('#LeirasEdit').ckeditor();
            }
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
            name: 'cimke',
            filter: {
                fields: ['#nevfilter', '#ckfilter']
            },
            tablebody: {
                url: '/admin/partnercimke/getlistbody'
            },
            karb: partnercimke
        });
        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        $('#mattable-body').on('click', '.js-menulathatocheckbox', function (e) {
            e.preventDefault();
            var $this = $(this),
                f = $this.closest('tr');
            $.ajax({
                url: '/admin/partnercimke/setmenulathato',
                type: 'POST',
                data: {
                    id: f.attr('data-cimkeid'),
                    num: $this.attr('data-num'),
                    kibe: !$this.is('.ui-state-hover')
                },
                success: function () {
                    $this.toggleClass('ui-state-hover');
                }
            });
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(partnercimke);
        }
    }
});