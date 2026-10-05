$(document).ready(function () {
    const tipus = $('#mattable-select').data('tipus');

    $('#mattable-select').mattable({
        name: 'egyed',
        addVisible: false,
        filter: {
            fields: [
                '#idfilter',
                '#vevonevfilter',
                '#datumtipusfilter',
                '#datumtolfilter',
                '#datumigfilter',
                '#bizonylatstornofilter'
            ]
        },
        tablebody: {
            url: `/admin/konyvelo/${tipus}/getlistbody`,
            onStyle: function () {
                $('.js-pdfbizonylat').button();
            }
        },
        // csak olvasható lista: se karb, se mentés
        karb: {}
    });

    mkwcomp.datumEdit.init('#datumtolfilter');
    mkwcomp.datumEdit.init('#datumigfilter');
});
