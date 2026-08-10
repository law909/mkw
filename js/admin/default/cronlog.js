$(document).ready(function () {
    const mattkarbconfig = new MattkarbConfig({
        entityName: 'cronlog'
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            // a naplósorokat a cron írja, kézzel felvinni nincs mit
            addVisible: false,
            filter: {
                fields: ['#feladatfilter', '#allapotfilter', '#nevfilter']
            },
            tablebody: {
                url: '/admin/cronlog/getlistbody'
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
