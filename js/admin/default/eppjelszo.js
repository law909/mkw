$(document).ready(function () {
    // a nyers jelszó csak a létrehozó mentés válaszában jön vissza, utána nem kérhető le
    const showGeneratedPassword = function (jelszo, done) {
        if ($.unblockUI) {
            $.unblockUI();
        }
        const input = $('<input type="text" readonly size="24">').val(jelszo).css({'font-family': 'monospace', 'font-size': '1.4em'});
        $('#dialogcenter')
            .empty()
            .append($('<p>').text('A jelszó csak most látszik, utána nem kérhető le újra. Másold ki, és add át az oldal látogatóinak.'))
            .append(input)
            .dialog({
                title: 'Az új jelszó',
                resizable: false,
                modal: true,
                width: 460,
                closeOnEscape: false,
                open: function () {
                    input.trigger('focus').trigger('select');
                },
                buttons: {
                    'Másolás': function () {
                        input.trigger('select');
                        if (navigator.clipboard) {
                            navigator.clipboard.writeText(jelszo);
                        } else {
                            document.execCommand('copy');
                        }
                    },
                    'Kész': function () {
                        $(this).dialog('close');
                    }
                },
                close: function () {
                    done();
                }
            });
    };

    const mattkarbconfig = new MattkarbConfig({
        entityName: 'eppjelszo',
        afterSave: function (data, done) {
            const valasz = (typeof data === 'string' && data) ? JSON.parse(data) : (data || {});
            if (valasz.jelszo) {
                showGeneratedPassword(valasz.jelszo, done);
            } else {
                done();
            }
        }
    });

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#oldalidfilter', '#megjegyzesfilter', '#allapotfilter']
            },
            tablebody: {
                url: '/admin/eppjelszo/getlistbody'
            },
            karb: mattkarbconfig
        });
        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(mattkarbconfig);
        }
    }
});
