$(document).ready(function () {
    const mattkarbconfig = new MattkarbConfig({
        entityName: 'arfolyam',
        beforeShow: function () {
            mkwcomp.datumEdit.init('#DatumEdit');
        }
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            tablebody: {
                url: '/admin/arfolyam/getlistbody'
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
