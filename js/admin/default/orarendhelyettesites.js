$(document).ready(function () {

    const orarendhelyettesites = new MattkarbConfig({
        entityName: 'orarendhelyettesites',
        beforeShow: function (form, opt) {
            mkwcomp.datumEdit.init('#DatumEdit');
            $('#DatumEdit').on('change', function (e) {
                var d = $('#DatumEdit').datepicker('getDate');
                $.ajax({
                    url: '/admin/orarend/getlistforhelyettesites',
                    type: 'GET',
                    data: {
                        datum: d.getFullYear() + '.' + (d.getMonth() + 1) + '.' + d.getDate()
                    },
                    success: function (data) {
                        if (data) {
                            $('#OrarendEdit').html(data);
                        }
                    }
                });
            });
        },
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            name: 'orarend',
            onGetTBody: function () {
            },
            filter: {
                fields: ['#inaktivfilter', '#elmaradfilter']
            },
            tablebody: {
                url: '/admin/orarendhelyettesites/getlistbody',
                onStyle: function () {
                },
                onDoEditLink: function () {
                }
            },
            karb: orarendhelyettesites
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });

    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(orarendhelyettesites);
        }
    }
});