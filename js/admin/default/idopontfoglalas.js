$(document).ready(function () {

    function isPartnerAutocomplete() {
        return $('#mattkarb-header').data('partnerautocomplete') == '1';
    }

    function setPartnerData(d) {
        $('#PartnernevEdit').val(d.nev || '');
        $('#PartnertelefonEdit').val(d.telefon || '');
        $('#PartneremailEdit').val(d.email || '');
    }

    // Ürítés is kell: a bennragadt emailcím alapján az új felvitel a régi partnert nevezné át.
    function loadPartnerData(partnerid) {
        if (!(partnerid > 0)) {
            setPartnerData({});
            return;
        }
        $.ajax({
            url: '/admin/partner/getdata',
            type: 'GET',
            data: {
                partnerid: partnerid
            },
            success: function (data) {
                setPartnerData(JSON.parse(data));
            }
        });
    }

    function partnerAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylatfej/getpartnerlist',
            select: function (event, ui) {
                var partner = ui.item;
                if (partner) {
                    // a rejtett mező val()-ja nem vált ki change-et, ezért itt közvetlenül töltünk
                    $('.js-partnerid').val(partner.id);
                    $('.js-ujpartnercb').prop('checked', false);
                    loadPartnerData(partner.id);
                }
            }
        };
    }

    function showError(msg) {
        const $dialog = $('#dialogcenter').length ? $('#dialogcenter') : $('<div id="dialogcenter"></div>').appendTo('body');
        $dialog.html(msg).dialog({
            resizable: false,
            height: 160,
            modal: true,
            buttons: {
                'OK': function () {
                    $(this).dialog('close');
                }
            }
        });
    }

    // A sorok gombjai ugyanazt csinálják: POST az id-vel, majd az üzenet kiírása és lista frissítés.
    function sorMuvelet(url, id, frissit) {
        $.ajax({
            url: url,
            type: 'POST',
            data: {
                id: id
            },
            success: function (data) {
                const d = JSON.parse(data);
                showError(d.msg);
                if (frissit) {
                    $('.mattable-tablerefresh').click();
                }
            }
        });
    }

    // Kifizetés: az összeget az alkalom árából kínáljuk fel, a bizonylat a fizetési mód
    // típusa szerint pénztár- vagy bankbizonylat lesz (a szerver dönti el).
    function fizetDialog(id) {
        $.ajax({
            url: '/admin/idopontfoglalas/getar',
            type: 'GET',
            data: {
                id: id
            },
            success: function (data) {
                const d = JSON.parse(data);
                if (d.result !== 'ok') {
                    showError(d.msg);
                    return;
                }
                const $form = $('#fizetform');
                $('#afizetosszegedit').val(d.price);
                mkwcomp.datumEdit.clear('#afizetdatumedit');
                $('#afizetdatumedit').datepicker('setDate', new Date());
                $form.show().dialog({
                    resizable: false,
                    width: 420,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            const $dia = $(this);
                            $.ajax({
                                url: '/admin/idopontfoglalas/fizet',
                                type: 'POST',
                                data: {
                                    id: id,
                                    fizmod: $('#afizetfizmodedit').val(),
                                    bankszamla: $('#afizetbankszamlaedit').val(),
                                    penztar: $('#afizetpenztaredit').val(),
                                    jogcim: $('#afizetjogcimedit').val(),
                                    datum: mkwcomp.datumEdit.getDate('#afizetdatumedit'),
                                    osszeg: $('#afizetosszegedit').val()
                                },
                                success: function (data) {
                                    const r = JSON.parse(data);
                                    $dia.dialog('close').dialog('destroy');
                                    $form.hide();
                                    if (r.result === 'ok') {
                                        $('.mattable-tablerefresh').click();
                                    } else {
                                        showError(r.msg);
                                    }
                                }
                            });
                        },
                        'Mégsem': function () {
                            $(this).dialog('close').dialog('destroy');
                            $form.hide();
                        }
                    }
                });
            }
        });
    }

    // Számlázás: az összeg a kifizetett összeg, a fizetési mód a kifizetésé (a szerver veszi).
    function szamlazDialog(id) {
        $.ajax({
            url: '/admin/idopontfoglalas/getfizetettosszeg',
            type: 'GET',
            data: {
                id: id
            },
            success: function (data) {
                const d = JSON.parse(data);
                if (d.result !== 'ok') {
                    showError(d.msg);
                    return;
                }
                const $form = $('#szamlazform');
                $('#aszamlazosszegedit').val(d.price);
                $('#aszamlazkeltedit').datepicker('setDate', new Date());
                $('#aszamlazteljesitesedit').datepicker('setDate', new Date());
                $form.show().dialog({
                    resizable: false,
                    width: 420,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            const $dia = $(this);
                            $.ajax({
                                url: '/admin/idopontfoglalas/szamlaz',
                                type: 'POST',
                                data: {
                                    id: id,
                                    kelt: mkwcomp.datumEdit.getDate('#aszamlazkeltedit'),
                                    teljesites: mkwcomp.datumEdit.getDate('#aszamlazteljesitesedit'),
                                    osszeg: $('#aszamlazosszegedit').val(),
                                    biztipus: $('input[name="aszamlazbiztipus"]:checked').val()
                                },
                                success: function (data) {
                                    const r = JSON.parse(data);
                                    $dia.dialog('close').dialog('destroy');
                                    $form.hide();
                                    if (r.result === 'ok') {
                                        $('.mattable-tablerefresh').click();
                                    } else {
                                        showError(r.msg);
                                    }
                                }
                            });
                        },
                        'Mégsem': function () {
                            $(this).dialog('close').dialog('destroy');
                            $form.hide();
                        }
                    }
                });
            }
        });
    }

    // A lemondás levelet is küldhet, ezért egy kattintásra nem indul: a doboz a lemondás okát is felveszi.
    function lemondDialog(id) {
        const $form = $('#lemondform');
        $('#alemondasokaedit').val('');
        $form.show().dialog({
            resizable: false,
            width: 420,
            modal: true,
            buttons: {
                'Lemond': function () {
                    $(this).dialog('close').dialog('destroy');
                    $form.hide();
                    $.ajax({
                        url: '/admin/idopontfoglalas/lemond',
                        type: 'POST',
                        data: {
                            id: id,
                            ok: $('#alemondasokaedit').val()
                        },
                        success: function (data) {
                            showError(JSON.parse(data).msg);
                            $('.mattable-tablerefresh').click();
                        }
                    });
                },
                'Mégsem': function () {
                    $(this).dialog('close').dialog('destroy');
                    $form.hide();
                }
            }
        });
    }

    const mattkarbconfig = new MattkarbConfig({
        entityName: 'idopontfoglalas',
        // a szerver a mentést is elutasítja (409), de a hibaüzenetet itt tudjuk megmutatni.
        // Csak felvitelnél kell: szerkesztéskor a rendezvény jelentkezésnek is van időpont
        // választója, de ott a saját sora lenne a "már meglévő foglalás"
        beforeSubmit: function (arr, form) {
            if (!$('.js-idopontedit').length || $(form).find('[name="oper"]').val() !== 'add') {
                return true;
            }
            let ok = true;
            $.ajax({
                url: '/admin/idopontfoglalas/check',
                type: 'GET',
                async: false,
                data: $(form).serialize(),
                success: function (data) {
                    const valasz = JSON.parse(data);
                    if (valasz.result !== 'ok') {
                        ok = false;
                        showError(valasz.msg);
                    }
                }
            });
            return ok;
        },
        beforeShow: function () {
            if (!$('.js-idopontedit').length) {
                return;
            }
            mkwcomp.datumEdit.init('#DatumEdit');
            if (isPartnerAutocomplete()) {
                $('.js-partnerautocomplete').autocomplete(partnerAutocompleteConfig())
                    .autocompleteRenderer(partnerAutocompleteRenderer);
                // az "Új" jelölő üríti a partner azonosítót, így a beírt név/email alapján keletkezik partner
                $('.js-ujpartnercb').on('change', function () {
                    if ($(this).is(':checked')) {
                        $('.js-partnerid').val('');
                        setPartnerData({});
                    }
                });
            }

            // választó listás módban a select maga a .js-partnerid
            $('select.js-partnerid').on('change', function () {
                loadPartnerData($(this).val());
            });

            // egyszeri időpontnál a nap adott, ismétlődőnél a következő olyan napra ugrunk
            $('.js-idopontedit').on('change', function () {
                const $opt = $('option:selected', this),
                    $datum = $('#DatumEdit');
                if (!$opt.val()) {
                    return;
                }
                if ($opt.data('ismetlodo') == 1) {
                    const nap = $opt.data('nap') * 1;
                    if (nap) {
                        const d = new Date();
                        while (((d.getDay() + 6) % 7) + 1 !== nap) {
                            d.setDate(d.getDate() + 1);
                        }
                        $datum.datepicker('setDate', d);
                    }
                } else {
                    $datum.datepicker('setDate', $opt.data('datum'));
                }
            });
        }
    });

    if ($.fn.mattable) {
        mkwcomp.datumEdit.init('#datumtolfilter');
        mkwcomp.datumEdit.init('#datumigfilter');
        mkwcomp.datumEdit.init('#afizetdatumedit');
        mkwcomp.datumEdit.init('#aszamlazkeltedit');
        mkwcomp.datumEdit.init('#aszamlazteljesitesedit');

        $('#mattable-select').mattable({
            filter: {
                fields: [
                    '#tipusfilter',
                    '#idfilter',
                    '#fizmodfilter',
                    '#varolistasfilter',
                    '#partnernevfilter',
                    '#partneremailfilter',
                    '#datumtolfilter',
                    '#datumigfilter',
                    '#dolgozofilter',
                    '#idoponttemafilter',
                    '#idopontfilter'
                ]
            },
            tablebody: {
                url: '/admin/idopontfoglalas/getlistbody',
                onStyle: function () {
                    $('.js-emailemlekezteto, .js-emaildijbekero, .js-lemond, .js-visszaallit, .js-fizet, .js-szamlaz').button();
                }
            },
            karb: mattkarbconfig
        });
        $('#mattable-body')
            .on('click', '.js-emailemlekezteto', function (e) {
                e.preventDefault();
                sorMuvelet('/admin/idopontfoglalas/email/emlekezteto', $(this).data('id'), true);
            })
            .on('click', '.js-emaildijbekero', function (e) {
                e.preventDefault();
                sorMuvelet('/admin/idopontfoglalas/email/dijbekero', $(this).data('id'), true);
            })
            .on('click', '.js-lemond', function (e) {
                e.preventDefault();
                lemondDialog($(this).data('id'));
            })
            .on('click', '.js-visszaallit', function (e) {
                e.preventDefault();
                sorMuvelet('/admin/idopontfoglalas/visszaallit', $(this).data('id'), true);
            })
            .on('click', '.js-fizet', function (e) {
                e.preventDefault();
                fizetDialog($(this).data('id'));
            })
            .on('click', '.js-szamlaz', function (e) {
                e.preventDefault();
                szamlazDialog($(this).data('id'));
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
