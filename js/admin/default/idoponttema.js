$(document).ready(function () {
    const mattkarbconfig = new MattkarbConfig({
        entityName: 'idoponttema',
        beforeShow: function () {
            mkwcomp.kerdoivSzerkeszto.init($('#KerdoivTab'));
        }
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter', '#inaktivfilter']
            },
            tablebody: {
                url: '/admin/idoponttema/getlistbody'
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
