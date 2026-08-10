$(document).ready(function () {
    const leltarfej = new MattkarbConfig({
        entityName: 'leltarfej',
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            name: 'leltarfej',
            filter: {
                fields: [
                    '#nevfilter',
                    '#raktarfilter'
                ]
            },
            tablebody: {
                url: '/admin/leltarfej/getlistbody',
                onStyle: function () {
                    $('.js-felvetel, .js-export, .js-import, .js-zar').button();
                }
            },
            karb: leltarfej
        });
        const $zarasdatumedit = $('#DatumEdit');
        if ($zarasdatumedit) {
            $zarasdatumedit.datepicker($.datepicker.regional['hu']);
            $zarasdatumedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            $zarasdatumedit.datepicker('setDate', $zarasdatumedit.attr('data-datum'));
        }

        $('#mattable-body').on('click', '.js-zar', function (e) {
            var $this = $(this);
            e.preventDefault();
            $('#zarasdatumform').dialog({
                resizable: false,
                height: 140,
                modal: true,
                buttons: {
                    'OK': function () {
                        var dial = $(this),
                            tol = $('#DatumEdit').datepicker('getDate');
                        tol = tol.getFullYear() + '.' + (tol.getMonth() + 1) + '.' + tol.getDate();
                        $.ajax({
                            url: $this.data('href'),
                            type: 'POST',
                            data: {
                                datum: tol,
                                leltarid: $this.data('leltarfejid')
                            },
                            success: function () {
                                $('.mattable-tablerefresh').click();
                                dial.dialog('close');
                            }
                        });
                    },
                    'Mégsem': function () {
                        $('.mattable-tablerefresh').click();
                        $(this).dialog('close');
                    }
                }
            });
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(leltarfej);
        }
    }
});