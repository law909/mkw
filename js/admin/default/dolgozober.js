$(document).ready(function () {
    const dolgozober = new MattkarbConfig({
        entityName: 'dolgozober',
        beforeShow: function () {
            mkwcomp.datumEdit.init('#DatumEdit');
        }
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#tolfilter', '#igfilter', '#rontottfilter', '#dolgozofilter', '#berjogcimfilter']
            },
            tablebody: {
                url: '/admin/dolgozober/getlistbody'
            },
            karb: dolgozober
        });
        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        $('#mattable-body').on('click', '.js-rontber', function (e) {
            e.preventDefault();
            const id = $(this).data('egyedid');
            $('#dialogcenter').text('A sor rontott lesz: nem módosítható és nem számít bele az összegbe. Nem vonható vissza.').dialog({
                title: 'Rontás',
                modal: true,
                resizable: false,
                buttons: {
                    'Ront': function () {
                        const $dialog = $(this);
                        $.ajax({
                            url: '/admin/dolgozober/ront',
                            type: 'POST',
                            dataType: 'json',
                            data: {id},
                            success: () => {
                                $dialog.dialog('close');
                                $('.mattable-tablerefresh').click();
                            }
                        });
                    },
                    'Mégsem': function () {
                        $(this).dialog('close');
                    }
                }
            });
        });
        $('#tolfilter, #igfilter')
            .datepicker($.datepicker.regional['hu'])
            .datepicker('option', 'dateFormat', 'yy.mm.dd');
    } else if ($.fn.mattkarb) {
        $('#mattkarb').mattkarb(dolgozober);
    }
});
