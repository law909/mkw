$(document).ready(function () {

    // A pénztárbizonylatnál az irány a fejen van (a banknál tételenként), ezért a
    // tételösszegeket előjel nélkül adjuk össze – az előjelet a fej irany mezője hordozza.
    // Csak bruttót számolunk: a tétel netto/afa mezőit egyik mentési ág sem tölti ki.
    function calcOsszesen() {
        var osszeg = 0;

        $('input[name^="tetelosszeg_"]').each(function () {
            var ertek = $(this).val() * 1;
            if (!isNaN(ertek)) {
                osszeg = osszeg + ertek;
            }
        });

        $('.js-bruttosum').text(accounting.formatNumber(tools.round(osszeg, -2), 2, ' '));
    }

    function checkPenztarDatum(kelt, penztar) {
        var retval = false;
        $.ajax({
            async: false,
            url: '/admin/penztarbizonylatfej/checkdatum',
            data: {
                datum: kelt,
                penztar: penztar
            },
            success: function (data) {
                var d = JSON.parse(data);
                if (d.response == 'ok') {
                    retval = true;
                }
            }
        });
        return retval;
    }

    function checkPenztar() {
        var dialogcenter = $('#dialogcenter'),
            keltedit = $('#KeltEdit'),
            kelt = keltedit.datepicker('getDate'),
            penztar = $('#PenztarEdit option:selected'),
            ret;
        kelt = kelt.getFullYear() + '.' + (kelt.getMonth() + 1) + '.' + kelt.getDate();
        if (!penztar.length) {
            penztar = $('input[name="penztar"]');
        }
        ret = checkPenztarDatum(kelt, penztar.val());
        if (!ret) {
            dialogcenter.html('Az időszakra a pénztár le van zárva.').dialog({
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
        return ret;
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
                    '#penztarfilter',
                    '#iranyfilter',
                    '#jogcimfilter',
                    '#hivatkozottbizonylatfilter'
                ]
            },
            tablebody: {
                url: '/admin/penztarbizonylattetel/getlistbody',
                onStyle: function () {
                    // a nyomtatás link href-je szerver oldalon készen jön, itt csak a
                    // jQuery UI gomb-megjelenést kapja meg (a mattable az editlinket
                    // magától stílusozza, a többit nem)
                    $('.js-printbizonylat').button();
                }
            },
            // a tételt magát nem lehet önállóan szerkeszteni, a sorra kattintva a
            // pénztárbizonylat karbantartója nyílik meg
            karb: {
                container: '#mattkarb',
                viewUrl: '/admin/penztarbizonylatfej/getkarb',
                newWindowUrl: '/admin/penztarbizonylatfej/viewkarb',
                saveUrl: '/admin/penztarbizonylatfej/save',
                beforeSerialize: function (form, opt) {
                    if (!checkPenztar()) {
                        return false;
                    }
                    return true;
                },
                beforeShow: function () {
                    var dialogcenter = $('#dialogcenter');
                    mkwcomp.datumEdit.init('#KeltEdit');

                    $('.js-tetelnewbutton,.js-teteldelbutton,.js-hivatkozottbizonylatbutton').button();

                    $('input[name^="teteldatum_"]').each(function () {
                        mkwcomp.datumEdit.init($(this));
                    });

                    $('#AltalanosTab')
                        .on('change', 'input[name^="tetelosszeg_"]', function (e) {
                            calcOsszesen();
                        })
                        .on('click', '.js-tetelnewbutton', function (e) {
                            var $this = $(this);
                            e.preventDefault();
                            $.ajax({
                                url: '/admin/penztarbizonylattetel/getemptyrow',
                                data: {
                                    type: 'penztar'
                                },
                                type: 'GET',
                                success: function (data) {
                                    var d = JSON.parse(data);

                                    $('.js-bizonylatosszesito').before(d.html);
                                    mkwcomp.datumEdit.init('#DatumEdit' + d.id);

                                    $('.js-tetelnewbutton,.js-teteldelbutton,.js-hivatkozottbizonylatbutton').button();
                                    $this.remove();
                                    calcOsszesen();
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
                                                url: '/admin/penztarbizonylattetel/save',
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
                                success: function (d) {
                                    var data = JSON.parse(d);
                                    dialogcenter.html(data.html);
                                    dialogcenter.dialog({
                                        resizable: true,
                                        height: 340,
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
                        })
                        .on('change', '#PenztarEdit', function (e) {
                            var v = $('#PenztarEdit option:selected').data('valutanem');
                            $('#ValutanemEdit').val(v);
                            $('input[name="valutanem"]').val(v);
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
