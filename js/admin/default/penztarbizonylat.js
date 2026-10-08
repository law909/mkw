$(document).ready(function () {

    // A pénztárbizonylatnál az irány a fejen van (a banknál tételenként), ezért a
    // tételösszegeket előjel nélkül adjuk össze – az előjelet a fej irany mezője hordozza.
    // Csak bruttót számolunk: a tétel netto/afa mezőit egyik mentési ág sem tölti ki.
    function calcOsszesen() {
        var osszeg = 0;

        $('input[name^="tetelosszeg_"]').not('.js-torolttetel input').each(function () {
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
            penztar = $('#PenztarEdit option:selected');
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

    // the hidden id is not "required"-checked by the browser, so an unselected partner is caught here
    function checkPartner() {
        const $kereso = $('.js-partnerautocomplete');
        if (!$kereso.length || $('input[name="partner"]').val()) {
            return true;
        }
        $('#dialogcenter').html('Válasszon partnert a listából.').dialog({
            resizable: false,
            height: 140,
            modal: true,
            buttons: {
                'OK': function () {
                    $(this).dialog('close');
                    $kereso.focus();
                }
            }
        });
        return false;
    }

    const penztarbizonylat = new MattkarbConfig({
        entityName: 'penztarbizonylatfej',
        beforeSerialize: function (form, opt) {
            return checkPartner() && checkPenztar();
        },
        beforeShow: function () {
            var dialogcenter = $('#dialogcenter');
            mkwcomp.datumEdit.init('#KeltEdit');

            $('.js-partnerautocomplete')
                .autocomplete({
                    minLength: 4,
                    autoFocus: true,
                    source: '/admin/bizonylatfej/getpartnerlist',
                    select: function (event, ui) {
                        $('input[name="partner"]').val(ui.item ? ui.item.id : '');
                    }
                })
                .autocompleteRenderer(partnerAutocompleteRenderer)
                // typing over the chosen name drops the old partner until a new one is picked
                .on('input', function () {
                    $('input[name="partner"]').val('');
                });

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
                                    // csak az OK-val együtt törlődik, így a Mégsem után a tétel megmarad
                                    $(`input[name="teteloper_${removeid}"]`).val('del');
                                    $(`#teteltable_${removeid}`)
                                        .addClass('js-torolttetel')
                                        .hide()
                                        .find('[required]').prop('required', false);
                                    calcOsszesen();
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
                            partner: $('[name="partner"]').val(),
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
    });

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
                onStyle: function () {
                    // a nyomtatás link href-je szerver oldalon készen jön, itt csak a
                    // jQuery UI gomb-megjelenést kapja meg
                    $('.js-rontbizonylat,.js-printbizonylat').button();
                }
            },
            karb: penztarbizonylat
        });

        // A pénztárak közti átvezetés rögzítője az "Új" gomb mellől indul. Az "Új" linket a
        // mattable a lapozósorba teszi, ezért csak a plugin inicializálása után tudjuk mellé
        // fűzni. Az átvezetés két pénztárbizonylatot képez, ezért nem fér bele a karb-ba.
        $('.mattable-addlink').after(
            '<a class="mattable-atvezeteslink mattable-left" href="/admin/penztaratvezetes/viewkarb?id=0&amp;oper=add" title="Átvezetés">' +
            '<span class="ui-icon ui-icon-transferthick-e-w"></span></a>'
        );
        $('.mattable-atvezeteslink').button();

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        $('#mattable-body').on('click', '.js-rontbizonylat', function (e) {
            e.preventDefault();
            $.ajax({
                url: '/admin/penztarbizonylatfej/ront',
                type: 'POST',
                data: {
                    id: $(this).data('egyedid')
                },
                success: function () {
                    $('.mattable-tablerefresh').click();
                }
            });
        });

        mkwcomp.datumEdit.init('#datumtolfilter');
        mkwcomp.datumEdit.init('#datumigfilter');
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(penztarbizonylat);
        }
    }
});