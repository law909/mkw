$(document).ready(function () {

    function valutanemChange(firstrun) {
        if (!firstrun || $('input[name="oper"]').val()==='add') {
            $('#BankszamlaEdit').val($('option:selected', $('#ValutanemEdit')).data('bankszamla'));
        }
    }

    var bankbizonylat = {
        container: '#mattkarb',
        viewUrl: '/admin/bankbizonylatfej/getkarb',
        newWindowUrl: '/admin/bankbizonylatfej/viewkarb',
        saveUrl: '/admin/bankbizonylatfej/save',
        beforeShow: function () {
            var keltedit = $('#KeltEdit');
            keltedit.datepicker($.datepicker.regional['hu']);
            keltedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
            keltedit.datepicker('setDate', keltedit.attr('data-kelt'));

            $('.js-tetelnewbutton,.js-teteldelbutton').button();

            $('#ValutanemEdit').change(function(e) {
                valutanemChange(false);
            });

            $('#AltalanosTab')
                .on('click', '.js-tetelnewbutton', function(e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/bankbizonylattetel/getemptyrow',
                        data: {
                            type: 'bank'
                        },
                        type: 'GET',
                        success: function(data) {
                            $('.js-bizonylatosszesito').before(data);
                            $('.js-tetelnewbutton,.js-teteldelbutton').button();
                            $this.remove();
                        }
                    });
                })
                .on('click', '.js-teteldelbutton', function(e) {
                    e.preventDefault();
                    var removegomb = $(this),
                        removeid = removegomb.attr('data-id');
                    if (removegomb.attr('data-source') == 'client') {
                        dialogcenter.html('Biztos, hogy törli a tételt?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function() {
                                    $('#teteltable_' + removeid).remove();
                                    calcOsszesen();
                                    $(this).dialog('close');
                                },
                                'Nem': function() {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                    else {
                        dialogcenter.html('Biztos, hogy törli a tételt?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function() {
                                    $.ajax({
                                        url: '/admin/bankbizonylattetel/save',
                                        type: 'POST',
                                        data: {
                                            id: removeid,
                                            oper: 'del'
                                        },
                                        success: function(data) {
                                            $('#teteltable_' + data).remove();
                                            calcOsszesen();
                                        }
                                    });
                                    $(this).dialog('close');
                                },
                                'Nem': function() {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                });
        },
        beforeHide: function () {
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
            name: 'egyed',
            filter: {
                fields: ['#bejegyzesfilter', '#dtfilter', '#difilter']
            },
            tablebody: {
                url: '/admin/bankbizonylatfej/getlistbody'
            },
            karb: bankbizonylat
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        var dfilter = $('#dtfilter');
        dfilter.datepicker($.datepicker.regional['hu']);
        dfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
        dfilter = $('#difilter');
        dfilter.datepicker($.datepicker.regional['hu']);
        dfilter.datepicker('option', 'dateFormat', 'yy.mm.dd');
    }
    else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, bankbizonylat, {independent: true}));
        }
    }
});