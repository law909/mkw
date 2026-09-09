$(document).ready(function () {
    const dolgozoszabadsag = new MattkarbConfig({
        entityName: 'dolgozoszabadsag',
        beforeShow: function () {
            mkwcomp.datumEdit.init('#DatumtolEdit');
            mkwcomp.datumEdit.init('#DatumigEdit');
        }
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#tolfilter', '#igfilter', '#dolgozofilter', '#tipusfilter']
            },
            tablebody: {
                url: '/admin/dolgozoszabadsag/getlistbody'
            },
            karb: dolgozoszabadsag
        });
        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        // a szűrő mezőknek nincs kezdőértékük, ezért csak a datepickert kapják meg
        $('#tolfilter, #igfilter')
            .datepicker($.datepicker.regional['hu'])
            .datepicker('option', 'dateFormat', 'yy.mm.dd');
    } else if ($.fn.mattkarb) {
        $('#mattkarb').mattkarb(dolgozoszabadsag);
    }
});
