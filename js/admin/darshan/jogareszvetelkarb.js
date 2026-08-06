$(document).ready(function () {
    const jogareszvetel = new MattkarbConfig({
        entityName: 'jogareszvetel',
        beforeShow: function () {
            $('.js-partneredit').on('change', function () {
                var $this = $(this);
                $.ajax({
                    url: '/admin/jogareszvetel/getselect',
                    data: {
                        partnerid: $this.val()
                    },
                    type: 'GET',
                    success: function (data) {
                        $('.js-berletedit').innerHTML(data);
                    }
                });
            });
        },
    });

    if ($.fn.mattable) {
        mkwcomp.datumEdit.init('#datumtolfilter');
        mkwcomp.datumEdit.init('#datumigfilter');
        $('#mattable-select').mattable({
            filter: {
                fields: ['#datumtolfilter', '#datumigfilter', '#partnernevfilter', '#partneremailfilter', '#tisztaznikellfilter', '#tanarfilter', '#onlinefilter']
            },
            tablebody: {
                url: '/admin/jogareszvetel/getlistbody'
            },
            karb: jogareszvetel
        });
        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(jogareszvetel);
        }
    }
});