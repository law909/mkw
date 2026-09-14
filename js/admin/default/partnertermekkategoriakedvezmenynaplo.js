$(document).ready(function () {
    const mattkarbconfig = new MattkarbConfig({
        entityName: 'partnertermekkategoriakedvezmenynaplo'
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            // a sorokat a mentés naplózza, kézzel felvinni nincs mit
            addVisible: false,
            filter: {
                fields: ['#partnerfilter', '#termekfafilter', '#datumtolfilter', '#datumigfilter']
            },
            tablebody: {
                url: '/admin/partnertermekkategoriakedvezmenynaplo/getlistbody'
            },
            karb: mattkarbconfig
        });
        $('#maincheckbox').change(function () {
            $('.maincheckbox').prop('checked', $(this).prop('checked'));
        });
        mkwcomp.datumEdit.init('#datumtolfilter');
        mkwcomp.datumEdit.init('#datumigfilter');
    }
});
