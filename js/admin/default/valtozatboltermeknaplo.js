$(document).ready(function () {
    const mattkarbconfig = new MattkarbConfig({
        entityName: 'valtozatboltermeknaplo'
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            // a naplósorokat a "Termék a változatból" művelet írja
            addVisible: false,
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/valtozatboltermeknaplo/getlistbody'
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
