$(document).ready(function () {
    const mattkarbconfig = new MattkarbConfig({
        entityName: 'valutanem'
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/valutanem/getlistbody'
            },
            karb: mattkarbconfig
        });
        $('#maincheckbox').change(function () {
            $('.egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        $('#mattable-body').on('click', '.js-flagcheckbox', function (e) {
            e.preventDefault();
            const $this = $(this);
            $.ajax({
                url: '/admin/valutanem/setflag',
                type: 'POST',
                data: {
                    id: $this.attr('data-id'),
                    flag: $this.attr('data-flag'),
                    kibe: !$this.is('.ui-state-hover')
                },
                success: () => $this.toggleClass('ui-state-hover')
            });
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(mattkarbconfig);
        }
    }
});
