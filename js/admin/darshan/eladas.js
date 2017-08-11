function eladas() {

    var dialogcenter = $('#dialogcenter'),
        eladasform = $('#EladasForm'),
        keltedit = $('#ElKeltEdit'),
        teljesitesedit = $('#ElTeljesitesEdit'),
        esedekessegedit = $('#ElEsedekessegEdit'),
        teljesitesrow = $('#ElTeljesitesRow'),
        esedekessegrow = $('#ElEsedekessegRow'),
        penztaredit = $('#ElPenztarEdit'),
        penztarrow = $('#ElPenztarRow'),
        bankszamlaedit = $('#ElBankszamlaEdit'),
        bankszamlarow = $('#ElBankszamlaRow'),
        vanpenzmozgasedit = $('#ElVanPenzmozgas'),
        penzdatumedit = $('#ElPenzdatumEdit'),
        jogcimedit = $('#ElJogcimEdit'),
        osszegedit = $('#ElOsszegEdit');

    function setPartnerData(d) {
        $('input[name="partnervezeteknev"]', eladasform).val(d.vezeteknev);
        $('input[name="partnerkeresztnev"]', eladasform).val(d.keresztnev);
        $('input[name="partnerirszam"]', eladasform).val(d.irszam);
        $('input[name="partnervaros"]', eladasform).val(d.varos);
        $('input[name="partnerutca"]', eladasform).val(d.utca);
        $('input[name="partnertelefon"]', eladasform).val(d.telefon);
        $('input[name="partneremail"]', eladasform).val(d.email);
    }

    function setTermekAr() {
        var partner = $('select[name="partner"] option:selected', eladasform).val();

        $.ajax({
            async: false,
            url: '/admin/bizonylattetel/getar',
            data: {
                partner: partner,
                termek: $('select[name="termek"] option:selected', eladasform).val()
            },
            success: function(data) {
                var inp = $('input[name="egysegar"]', eladasform),
                    adat = JSON.parse(data);
                inp.val(adat.brutto);
                inp.change();
            }
        });
    }

    function calcErtek() {
        var menny = $('input[name="mennyiseg"]', eladasform).val() * 1,
            ear = $('input[name="egysegar"]', eladasform).val() * 1;
        $('.js-ertek', eladasform).text(menny * ear);
        $('input[name="penz"]', eladasform).val(menny * ear);
    }

    function checkKelt(kelt, biztipus, bizszam) {
        var retval = false;
        $.ajax({
            async: false,
            url: '/admin/bizonylatfej/checkkelt',
            data: {
                kelt: kelt,
                biztipus: biztipus,
                bizszam: bizszam
            },
            success: function(data) {
                var d = JSON.parse(data);
                if (d.response == 'ok') {
                    retval = true;
                }
            }
        });
        return retval;
    }

    function checkBizonylatFej(biztipus, bizszam) {
        var ret = checkKelt(keltedit.val(), biztipus, bizszam);
        if (!ret) {
            dialogcenter.html('Már van későbbi keltű bizonylat.').dialog({
                resizable: false,
                height: 140,
                modal: true,
                buttons: {
                    'OK': function() {
                        $(this).dialog('close');
                    }
                }
            });
        }
        return ret;
    }

    function setElDates() {
        var kelt = keltedit.datepicker('getDate'),
            partner;
        if (getBiztipus() === 'szamla') {
            partner = $('#ElPartnerEdit option:selected').val();
            $.ajax({
                url: '/admin/bizonylatfej/calcesedekesseg',
                data: {
                    kelt: kelt.getFullYear() + '.' + (kelt.getMonth() + 1) + '.' + kelt.getDate(),
                    fizmod: $('#ElFizmodEdit option:selected').val(),
                    partner: partner
                },
                success: function (data) {
                    var d = JSON.parse(data);
                    esedekessegedit.datepicker('setDate', d.esedekesseg);
                }
            });
        }
    }

    function isFizmodSelected() {
        return $('#ElFizmodEdit option:selected').val();
    }

    function isKeszpenz() {
        var fm = $('#ElFizmodEdit option:selected');
        return fm.data('tipus') === 'P';
    }

    function isAtutalas() {
        var fm = $('#ElFizmodEdit option:selected');
        return fm.data('tipus') === 'B' && !fm.data('szepkartya') && !fm.data('aycm') && !fm.data('sportkartya');
    }

    function isSZEP() {
        var fm = $('#ElFizmodEdit option:selected');
        return fm.data('szepkartya');
    }

    function getBiztipus() {
        return $('#ElSzamlaEdit:checked').val();
    }

    function setElControls() {
        if (isKeszpenz() || !isFizmodSelected()) {
            showPenztar();
            if (vanpenzmozgasedit.prop('checked')) {
                penztaredit.prop('required', true);
                penzdatumedit.prop('required', true);
                jogcimedit.prop('required', true);
                osszegedit.prop('required', true);
            }
            else {
                penztaredit.removeAttr('required');
                penzdatumedit.removeAttr('required');
                jogcimedit.removeAttr('required');
                osszegedit.removeAttr('required');
            }
        }
        if (isAtutalas()) {
            showBankszamla();
            if (vanpenzmozgasedit.prop('checked')) {
                bankszamlaedit.prop('required', true);
                penzdatumedit.prop('required', true);
                jogcimedit.prop('required', true);
                osszegedit.prop('required', true);
            }
            else {
                bankszamlaedit.removeAttr('required');
                penzdatumedit.removeAttr('required');
                jogcimedit.removeAttr('required');
                osszegedit.removeAttr('required');
            }
        }
    }

    function showPenztar() {
        penztarrow.show();
        bankszamlarow.hide();
        bankszamlaedit.removeAttr('required');

    }

    function showBankszamla() {
        bankszamlarow.show();
        penztarrow.hide();
        penztaredit.removeAttr('required');
    }

    function showSzamlaDatumok() {
        teljesitesrow.show();
        esedekessegrow.show();
        teljesitesedit.prop('required', true);
        esedekessegedit.prop('required', true);
    }

    function hideSzamlaDatumok() {
        teljesitesrow.hide();
        esedekessegrow.hide();
        teljesitesedit.removeAttr('required');
        esedekessegedit.removeAttr('required');
    }

    function init() {
        mkwcomp.datumEdit.init('#ElKeltEdit');
        mkwcomp.datumEdit.init('#ElTeljesitesEdit');
        mkwcomp.datumEdit.init('#ElEsedekessegEdit');
        mkwcomp.datumEdit.init('#ElPenzdatumEdit');
        mkwcomp.datumEdit.init('#SZEPKartyaErvenyessegEdit');
        $('#ElOKButton, #ElCancelButton').button();

        eladasform.ajaxForm({
            type: 'POST',
            beforeSerialize: function(form, opt) {
                if (isAtutalas() && getBiztipus() !== 'szamla') {
                    dialogcenter.html('Átutalás esetén kötelező számlát kiállítani!');
                    dialogcenter.dialog({
                        resizable: true,
                        height: 340,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                    return false;
                }
                if (getBiztipus() === 'szamla') {
                    if (!checkBizonylatFej(getBiztipus())) {
                        return false;
                    }
                }

                $.blockUI({
                    message: 'Kérem várjon...',
                    css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#fff'
                    }
                });
                return true;
            },
            success: function(data) {
                clearForm();
                dialogcenter.html('A mentés sikerült.');
                dialogcenter.dialog({
                    resizable: true,
                    height: 340,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            }
        });

        eladasform
            .on('change', '#ElSzamlaEdit', function(e) {
                if (getBiztipus() === 'szamla') {
                    showSzamlaDatumok();
                }
                else {
                    hideSzamlaDatumok();
                }
            })
            .on('change', '#ElPenztarEdit', function(e) {
                var v = $('#ElPenztarEdit option:selected').data('valutanem');
                $('input[name="valutanem"]', eladasform).val(v);
            })
            .on('change', '#ElPartnerEdit', function(e) {
                var pe = $(this);
                if (pe.val() > 0) {
                    $.ajax({
                        url: '/admin/partner/getdata',
                        type: 'GET',
                        data: {
                            partnerid: pe.val()
                        },
                        success: function(data) {
                            var d = JSON.parse(data);
                            setPartnerData(d);
                            setElDates();
                        }
                    });
                }
            })
            .on('change', '#ElFizmodEdit', function(e) {
                setElControls();
                if (isSZEP()) {
                    $('tr.szepkartya').show();
                }
                else {
                    $('tr.szepkartya').hide();
                }
                vanpenzmozgasedit.prop('checked', isKeszpenz());
                setElDates();
            })
            .on('change', '#ElVanPenzmozgas', function(e) {
                setElControls();
            })
            .on('change', '#ElKeltEdit', function(e) {
                $('#ElPenzdatumEdit').val($('#ElKeltEdit').val());
            })
            .on('change', '#ElTermekEdit', function(e) {
                setTermekAr();
            })
            .on('change', '#ElMennyisegEdit, #ElEgysarEdit', function(e) {
                calcErtek();
            })
            .on('click', '#ElCancelButton', function(e) {
                e.preventDefault();
                clearForm();
            });

    }

    function clearForm() {
        mkwcomp.datumEdit.clear('#ElKeltEdit');
        mkwcomp.datumEdit.clear('#ElTeljesitesEdit');
        mkwcomp.datumEdit.clear('#ElEsedekessegEdit');
        mkwcomp.datumEdit.clear('#ElPenzdatumEdit');
        $('#ElPartnerEdit')[0].selectedIndex = 0;
        $('#ElPartnervezeteknevEdit').val('');
        $('#ElPartnerkeresztnevEdit').val('');
        $('#ElPartnerirszamEdit').val('');
        $('#ElPartnervarosEdit').val('');
        $('#ElPartnerutcaEdit').val('');
        $('#ElPartneremailEdit').val('');
        $('#ElPartnertelefonEdit').val('');
        $('#ElEgysarEdit').val('');
        $('#ElMennyisegEdit').val(1);
        $('#ElTermekEdit')[0].selectedIndex = 0;
        $('#ElFizmodEdit')[0].selectedIndex = 0;
        penztaredit[0].selectedIndex = 0;
        bankszamlaedit[0].selectedIndex = 0;
        $('#ElMegjegyzesEdit').val('');
        $('#ElJogcimEdit')[0].selectedIndex = 0;
        $('#ElErtek').text('');
        $('#ElOsszegEdit').val('');
        $('#SZEPKartyaTipusEdit').selectedIndex = 0;
        $('#SZEPKartyaNevEdit').val('');
        $('#SZEPKartyaSzamEdit').val('');
        vanpenzmozgasedit.attr('checked', 'checked');
        $('#ElSzamlaEdit[value="egyeb"]').prop('checked', true);
        showPenztar();
        hideSzamlaDatumok();
        mkwcomp.datumEdit.clear('#SZEPKartyaErvenyessegEdit');
    }

    return {
        init: init,
        clearForm: clearForm
    }

}