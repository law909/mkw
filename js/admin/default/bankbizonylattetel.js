$(document).ready(function () {

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function partnerAutocompleteRenderer(ul, item) {
        return $('<li>')
            .append('<a>' + item.value + '</a>')
            .appendTo(ul);
    }

    function partnerAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylatfej/getpartnerlist',
            select: function (event, ui) {
                var partner = ui.item,
                    pi = $('input[name="tetelpartner_' + $(this).data('tetelid') + '"]');
                if (partner) {
                    pi.val(partner.id);
                    pi.change();
                }
            }
        };
    }

    function valutanemChange(firstrun) {
        if (!firstrun || $('input[name="oper"]').val() === 'add') {
            $('#BankszamlaEdit').val($('option:selected', $('#ValutanemEdit')).data('bankszamla'));
        }
    }

    function calcOsszesen() {
        var osszeg = 0;
        $('input[name^="tetelosszeg_"]').each(function () {
            var $this = $(this),
                tetelid = $this.attr('name').split('_')[1];
            tetelertek = $('input[name="tetelirany_' + tetelid + '"]:checked').val() * 1 * $this.val();
            osszeg = osszeg + tetelertek;
        });

        $('.js-osszegsum').text(accounting.formatNumber(tools.round(osszeg, -2), 2, ' '));
    }

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
                    '#partnerfilter',
                    '#hivatkozottbizonylatfilter'
                ]
            },
            tablebody: {
                url: '/admin/bankbizonylattetel/getlistbody',
                onStyle: function () {
                }
            },
            karb: {
                container: '#mattkarb',
                viewUrl: '/admin/bankbizonylatfej/getkarb',
                newWindowUrl: '/admin/bankbizonylatfej/viewkarb',
                saveUrl: '/admin/bankbizonylatfej/save',
                beforeShow: function () {
                    var dialogcenter = $('#dialogcenter');
                    mkwcomp.datumEdit.init('#KeltEdit');

                    $('.js-tetelnewbutton,.js-teteldelbutton,.js-hivatkozottbizonylatbutton').button();

                    $('#ValutanemEdit').change(function (e) {
                        valutanemChange(false);
                    });

                    $('input[name^="teteldatum_"]').each(function () {
                        mkwcomp.datumEdit.init($(this));
                    });

                    $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                        .autocomplete("instance")._renderItem = partnerAutocompleteRenderer;

                    $('#AltalanosTab')
                        .on('change', '.js-osszegedit', function (e) {
                            calcOsszesen();
                        })
                        .on('change', '.js-iranyedit', function (e) {
                            calcOsszesen();
                        })
                        .on('click', '.js-tetelnewbutton', function (e) {
                            var $this = $(this);
                            e.preventDefault();
                            calcOsszesen();
                            $.ajax({
                                url: '/admin/bankbizonylattetel/getemptyrow',
                                data: {
                                    type: 'bank'
                                },
                                type: 'GET',
                                success: function (data) {
                                    var d = JSON.parse(data);

                                    $('.js-bizonylatosszesito').before(d.html);
                                    mkwcomp.datumEdit.init('#DatumEdit' + d.id);

                                    $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                                        .autocomplete("instance")._renderItem = partnerAutocompleteRenderer;

                                    $('.js-tetelnewbutton,.js-teteldelbutton,.js-hivatkozottbizonylatbutton').button();
                                    $this.remove();
                                }
                            });
                        })
                        .on('click', '.js-teteldelbutton', function (e) {
                            e.preventDefault();
                            var removegomb = $(this),
                                removeid = removegomb.attr('data-id');
                            if (removegomb.attr('data-source') == 'client') {
                                dialogcenter.html('Biztos, hogy törli a tételt?').dialog({
                                    resizable: false,
                                    height: 140,
                                    modal: true,
                                    buttons: {
                                        'Igen': function () {
                                            $('#teteltable_' + removeid).remove();
                                            calcOsszesen();
                                            $(this).dialog('close');
                                        },
                                        'Nem': function () {
                                            $(this).dialog('close');
                                        }
                                    }
                                });
                            } else {
                                dialogcenter.html('Biztos, hogy törli a tételt?').dialog({
                                    resizable: false,
                                    height: 140,
                                    modal: true,
                                    buttons: {
                                        'Igen': function () {
                                            $.ajax({
                                                url: '/admin/bankbizonylattetel/save',
                                                type: 'POST',
                                                data: {
                                                    id: removeid,
                                                    oper: 'del'
                                                },
                                                success: function (data) {
                                                    $('#teteltable_' + data).remove();
                                                    calcOsszesen();
                                                }
                                            });
                                            $(this).dialog('close');
                                        },
                                        'Nem': function () {
                                            $(this).dialog('close');
                                        }
                                    }
                                });
                            }
                        })
                        .on('click', '.js-hivatkozottbizonylatbutton', function (e) {
                            e.preventDefault();
                            var $this = $(this),
                                tid = $this.data('id'),
                                partner;

                            if (isPartnerAutocomplete()) {
                                partner = $('input[name="tetelpartner_' + tid + '"]').val();
                            } else {
                                partner = $('select[name="tetelpartner_' + tid + '"]').val();
                            }

                            $.ajax({
                                type: 'POST',
                                url: '/admin/partner/getkiegyenlitetlenbiz',
                                data: {
                                    partner: partner,
                                    irany: $('input[name="tetelirany_' + tid + '"]:checked').val()
                                },
                                success: function (d) {
                                    var data = JSON.parse(d);
                                    dialogcenter.html(data.html);
                                    dialogcenter.dialog({
                                        resizable: true,
                                        height: 340,
                                        width: 400,
                                        modal: true,
                                        buttons: {
                                            'OK': function () {
                                                var sor = $('tr.js-selected', dialogcenter);
                                                $('input[name="tetelhivatkozottbizonylat_' + tid + '"]').val(sor.data('bizszam'));
                                                $('input[name="tetelhivatkozottdatum_' + tid + '"]').val(sor.data('datum'));
                                                $('input[name="tetelosszeg_' + tid + '"]').val(sor.data('egyenleg'));
                                                calcOsszesen();
                                                $(this).dialog('close');
                                            },
                                            'Bezár': function () {
                                                $(this).dialog('close');
                                            }
                                        }
                                    });
                                }
                            });
                        });

                    calcOsszesen();

                    dialogcenter.on('click', 'tr', function (e) {
                        e.preventDefault();
                        $('tr', dialogcenter).removeClass('ui-state-highlight js-selected');
                        $(this).addClass('ui-state-highlight js-selected');
                    })
                },
                beforeHide: function () {
                    $('.mattable-tablerefresh').trigger('click');
                },
                onSubmit: function () {
                    $('#messagecenter')
                        .html('A mentés sikerült.')
                        .hide()
                        .addClass('matt-messagecenter ui-widget ui-state-highlight')
                        .one('click', messagecenterclick)
                        .slideToggle('slow');
                }
            }
        });

        $('#maincheckbox').change(function () {
            $('.maincheckbox').prop('checked', $(this).prop('checked'));
        });

        mkwcomp.datumEdit.init('#datumtolfilter');
        mkwcomp.datumEdit.init('#datumigfilter');
    }
});
