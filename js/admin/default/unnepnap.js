$(document).ready(function () {
    const mattkarbconfig = new MattkarbConfig({
        entityName: 'unnepnap',
        beforeShow: function () {
            mkwcomp.datumEdit.init('#DatumEdit');
        }
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            tablebody: {
                url: '/admin/unnepnap/getlistbody'
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
