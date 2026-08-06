$(document).ready(function () {
    const keresoszolog = new MattkarbConfig({
        entityName: 'keresoszolog',
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/keresoszolog/getlistbody'
            },
            karb: keresoszolog
        });
        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(keresoszolog);
        }
    }
});