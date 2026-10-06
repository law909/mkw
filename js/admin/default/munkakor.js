$(document).ready(function () {
    // a karb ajaxszal töltődik, ezért delegált esemény
    $(document).on('click', '.js-munkakormenumind', function (e) {
        e.preventDefault();
        const $pipak = $(this).closest('.js-munkakormenucsoport').find('input[name="menuk[]"]');
        $pipak.prop('checked', $pipak.filter(':checked').length < $pipak.length);
    });

    const mattkarbconfig = new MattkarbConfig({
        entityName: 'munkakor'
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/munkakor/getlistbody'
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
