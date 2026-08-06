$(document).ready(function () {
    const template = new MattkarbConfig({
        entityName: 'template'
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            tablebody: {
                url: '/admin/template/getlistbody'
            },
            filter: {},
            batch: {},
            pager: {},
            karb: template
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(template);
        }
    }
});