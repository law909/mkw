$(document).ready(function () {

    var mattkarbconfig = new MattkarbConfig({
        entityName: 'glsutanvet'
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#csomagszamfilter', '#nevfilter', '#parositatlanfilter']
            },
            tablebody: {
                url: '/admin/glsutanvet/getlistbody'
            },
            karb: mattkarbconfig
        });

        // Csoportos művelet: a kipipált (vagy pipa nélkül az összes párosított) tételből
        // bankbizonylatot képez
        $('.mattable-batchbtn').on('click', function (e) {
            var tomb = [];
            e.preventDefault();
            $('.js-egyedcheckbox:checked').closest('tr').each(function (index, elem) {
                tomb.push($(elem).data('egyedid'));
            });
            switch ($('.mattable-batchselect').val()) {
                case 'generatebankbiz':
                    $.ajax({
                        url: '/admin/glsutanvet/generatebankbizonylat',
                        type: 'POST',
                        data: {
                            ids: tomb
                        },
                        success: function (data) {
                            if (data) {
                                var adat = JSON.parse(data);
                                if (adat.msg) {
                                    alert(adat.msg);
                                }
                            }
                            $('.mattable-tablerefresh').click();
                        }
                    });
                    break;
            }
        }).button();

        // Párosít: a bizonylatszám nélküli tételeken újra lefuttatja a keresést
        // (pl. ha az importálás óta elkészült a számla)
        $('.js-parosit').on('click', function (e) {
            e.preventDefault();
            $.ajax({
                url: '/admin/glsutanvet/parosit',
                type: 'POST',
                success: function (data) {
                    if (data) {
                        var adat = JSON.parse(data);
                        if (adat.msg) {
                            alert(adat.msg);
                        }
                    }
                    $('.mattable-tablerefresh').click();
                }
            });
        }).button();
        $('.js-import').button();

        $('#maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(mattkarbconfig);
        }
    }
});
