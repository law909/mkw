$(document).ready(function () {

    function calcOsszesen() {

    }

    var penztarbizonylat = {
        container: '#mattkarb',
        viewUrl: '/admin/penztarbizonylatfej/getkarb',
        newWindowUrl: '/admin/penztarbizonylatfej/viewkarb',
        saveUrl: '/admin/penztarbizonylatfej/save',
        beforeShow: function () {
            var dialogcenter = $('#dialogcenter');
            mkwcomp.datumEdit.init('#KeltEdit');

            $('.js-tetelnewbutton,.js-teteldelbutton,.js-hivatkozottbizonylatbutton').button();

            $('input[name^="teteldatum_"]').each(function() {
                mkwcomp.datumEdit.init($(this));
            });

            $('#AltalanosTab')
                .on('click', '.js-tetelnewbutton', function(e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/penztarbizonylattetel/getemptyrow',
                        data: {
                            type: 'penztar'
                        },
                        type: 'GET',
                        success: function(data) {
                            var d = JSON.parse(data);

                            $('.js-bizonylatosszesito').before(d.html);
                            mkwcomp.datumEdit.init('#DatumEdit' + d.id);

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
                                        url: '/admin/penztarbizonylattetel/save',
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
                        tid = $this.data('id'),
                        irany;

                    irany = $('input[name="irany"]:checked').val();
                    if (!irany) {
                        irany = $('input[name="irany"]').val();
                    }

                    $.ajax({
                        type: 'POST',
                        url: '/admin/partner/getkiegyenlitetlenbiz',
                        data: {
                            partner: $('select[name="partner"]').val(),
                            irany: irany
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
                })
                .on('change', '#PenztarEdit', function(e) {
                    var v = $('#PenztarEdit option:selected').data('valutanem');
                    $('#ValutanemEdit').val(v);
                    $('input[name="valutanem"]').val(v);
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
                    '#erbizonylatszamfilter',
                    '#valutanemfilter',
                    '#penztarfilter',
                    '#vevonevfilter',
                    '#iranyfilter'
                ]
            },
            tablebody: {
                url: '/admin/penztarbizonylatfej/getlistbody',
                onStyle: function() {
                    $('.js-rontbizonylat').button();
                }
            },
            karb: penztarbizonylat
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        $('#mattable-body').on('click', '.js-rontbizonylat', function(e) {
            e.preventDefault();
            $.ajax({
                url:'/admin/penztarbizonylatfej/ront',
                type: 'POST',
                data: {
                    id: $(this).data('egyedid')
                },
                success:function() {
                    $('.mattable-tablerefresh').click();
                }
            });
        });

        mkwcomp.datumEdit.init('#datumtolfilter');
        mkwcomp.datumEdit.init('#datumigfilter');
    }
    else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, penztarbizonylat, {independent: true}));
        }
    }
});