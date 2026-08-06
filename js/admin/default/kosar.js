$(document).ready(function () {
    const kosar = new MattkarbConfig({
        entityName: 'kosar',
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            addVisible: false,
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/kosar/getlistbody'
            },
            karb: kosar
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(kosar);
        }
    }
});