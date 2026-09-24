$(document).ready(function () {
    const mattkarbconfig = new MattkarbConfig({
        entityName: 'berjogcim'
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/berjogcim/getlistbody'
            },
            karb: mattkarbconfig
        });
        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else if ($.fn.mattkarb) {
        $('#mattkarb').mattkarb(mattkarbconfig);
    }
});
