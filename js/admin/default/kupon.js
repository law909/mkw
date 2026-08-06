$(document).ready(function () {
    const mattkarbconfig = new MattkarbConfig({
        entityName: 'kupon',
        beforeHide: function () {
            $('.mattable-tablerefresh').click();
        }
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#idfilter']
            },
            tablebody: {
                url: '/admin/kupon/getlistbody',
                onStyle: function () {
                    $('.js-printkupon').button();
                },
                onDoEditLink: function () {
                    $('.js-printkupon').each(function () {
                        var $this = $(this);
                        $this.attr('href', '/admin/kupon/print?id=' + $this.data('egyedid'));
                    });
                }
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