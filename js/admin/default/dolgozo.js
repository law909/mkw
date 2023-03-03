$(document).ready(function () {
    let dialogcenter = $('#dialogcenter');
    let dolgozo = {
        container: '#mattkarb',
        viewUrl: '/admin/dolgozo/getkarb',
        newWindowUrl: '/admin/dolgozo/viewkarb',
        saveUrl: '/admin/dolgozo/save',
        beforeShow: function () {
            let szulidoedit = $('#SzulidoEdit');
            szulidoedit.datepicker($.datepicker.regional['hu']);
            szulidoedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            szulidoedit.datepicker('setDate', szulidoedit.attr('data-datum'));
            let mkvkedit = $('#MunkaviszonykezdeteEdit');
            mkvkedit.datepicker($.datepicker.regional['hu']);
            mkvkedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            mkvkedit.datepicker('setDate', mkvkedit.attr('data-datum'));
        },
        onSubmit: function () {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click', messagecenterclick)
                .slideToggle('slow');
        }
    };

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            filter: {
                fields: ['#nevfilter']
            },
            tablebody: {
                url: '/admin/dolgozo/getlistbody'
            },
            karb: dolgozo
        });
        $('.mattable-batchbtn').on('click', function (e) {
            let cbs;
            e.preventDefault();
            cbs = $('.js-egyedcheckbox:checked');
            if (cbs.length) {
                let tomb = [], $exportform, $sel;
                cbs.closest('tr').each(function (index, elem) {
                    tomb.push($(elem).data('egyedid'));
                });
                switch ($('.mattable-batchselect').val()) {
                    case 'sendemailsablon':
                        let $dia = $('#emailsablondialog');
                        $dia.dialog({
                            title: 'Email sablon',
                            resizable: true,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    let dial = $(this),
                                        sablon = $('select[name="emailsablon"]').val();
                                    $('select[name="emailsablon"]').val('');
                                    $.ajax({
                                        url: '/admin/dolgozo/sendemailsablonok',
                                        type: 'POST',
                                        data: {
                                            ids: tomb,
                                            sablon: sablon
                                        },
                                        success: function () {
                                            dial.dialog('close');
                                        }
                                    });
                                },
                                'Mégsem': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                }
            } else {
                dialogcenter.html('Válasszon ki legalább egy dolgozót!').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            }

            $('.js-maincheckbox').change(function () {
                $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
            });
        });
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, dolgozo, {independent: true}));
        }
    }
});