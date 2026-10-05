$(document).ready(function () {
    const mattkarbconfig = new MattkarbConfig({
        entityName: 'menu'
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter', '#menucsoportfilter']
            },
            tablebody: {
                url: '/admin/menu/getlistbody'
            },
            karb: mattkarbconfig
        });
        $('#maincheckbox').change(function () {
            $('.egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(mattkarbconfig);
        }
    }
});
