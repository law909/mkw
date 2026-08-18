$(document).ready(function () {
    const mattkarbconfig = new MattkarbConfig({
        entityName: 'jogahelyszin'
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter', '#inaktivfilter']
            },
            tablebody: {
                url: '/admin/jogahelyszin/getlistbody'
            },
            karb: mattkarbconfig
        });
        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(mattkarbconfig);
        }
    }
});
