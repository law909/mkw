$(document).ready(function () {

    function valutanemChange(firstrun) {
        if (!firstrun || $('input[name="oper"]').val()==='add') {
            $('#BankszamlaEdit').val($('option:selected', $('#ValutanemEdit')).data('bankszamla'));
        }
    }

    function calcOsszesen() {

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
            keltedit.datepicker('setDate', keltedit.attr('data-datum'));

            $('.js-tetelnewbutton,.js-teteldelbutton,.js-hivatkozottbizonylatbutton').button();

            $('#ValutanemEdit').change(function(e) {
                valutanemChange(false);
            });

            $('input[name^="teteldatum_"]').each(function() {
                var o = $(this);
                o.datepicker($.datepicker.regional['hu']);
                o.datepicker('option', 'dateFormat', 'yy.mm.dd');
                o.datepicker('setDate', o.attr('data-datum'));
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
                            var dedit, d = JSON.parse(data);

                            $('.js-bizonylatosszesito').before(d.html);
                            dedit = $('#DatumEdit' + d.id);
                            dedit.datepicker($.datepicker.regional['hu']);
                            dedit.datepicker('option', 'dateFormat', 'yy.mm.dd');
                            dedit.datepicker('setDate', dedit.attr('data-datum'));

                            $('.js-tetelnewbutton,.js-teteldelbutton,.js-hivatkozottbizonylatbutton').button();
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
                })
                .on('click', '.js-hivatkozottbizonylatbutton', function(e) {
                    e.preventDefault();
                    var $this = $(this),
                        tid = $this.data('id');

                    $.ajax({
                        type: 'POST',
                        url: '/admin/partner/getkiegyenlitetlenbiz',
                        data: {
                            partner: $('select[name="tetelpartner_' + tid + '"]').val()
                        },
                        success: function(d) {
                            var data = JSON.parse(d);
                            dialogcenter.html(data.html);
                            dialogcenter.dialog({
                                resizable: true,
                                height: 340,
                                modal: true,
                                buttons: {
                                    'OK': function() {
                                        var sor = $('tr.js-selected', dialogcenter);
                                        $('input[name="tetelhivatkozottbizonylat_' + tid + '"]').val(sor.data('bizszam'));
                                        $('input[name="tetelhivatkozottdatum_' + tid + '"]').val(sor.data('datum'));
                                        $('input[name="tetelosszeg_' + tid + '"]').val(sor.data('egyenleg'));
                                        $(this).dialog('close');
                                    },
                                    'Bezár': function() {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                    });
                });
            dialogcenter.on('click', 'tr', function(e) {
                e.preventDefault();
                $('tr', dialogcenter).removeClass('ui-state-highlight js-selected');
                $(this).addClass('ui-state-highlight js-selected');
            })
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
                fields: [
                    '#idfilter',
                    '#datumtolfilter',
                    '#datumigfilter',
                    '#bizonylatrontottfilter',
                    '#erbizonylatszamfilter'
                ]
            },
            tablebody: {
                url: '/admin/bankbizonylatfej/getlistbody',
                onStyle: function() {
                    $('.js-rontbizonylat').button();
                }
            },
            karb: bankbizonylat
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        $('#mattable-body').on('click', '.js-rontbizonylat', function(e) {
            e.preventDefault();
            $.ajax({
                url:'/admin/bankbizonylatfej/ront',
                type: 'POST',
                data: {
                    id: $(this).data('egyedid')
                },
                success:function() {
                    $('.mattable-tablerefresh').click();
                }
            });
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