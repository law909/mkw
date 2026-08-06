$(document).ready(function () {
    const uzletkoto = new MattkarbConfig({
        entityName: 'uzletkoto',
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            name: 'uzletkoto',
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/uzletkoto/getlistbody'
            },
            karb: uzletkoto
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(uzletkoto);
        }
    }
});