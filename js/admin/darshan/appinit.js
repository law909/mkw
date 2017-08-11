function pleaseWait(msg) {
	if (typeof(msg)!=='string') {
		msg='Kérem várjon...';
	}
	$.blockUI({
		message:msg,
		css:{
			border:'none',
			padding:'15px',
			backgroundColor:'#000',
			'-webkit-border-radius':'10px',
			'-moz-border-radius':'10px',
			opacity:.5,
			color:'#fff'
		}
	});
}

$(document).ready(
	function(){

		var dialogcenter = $('#dialogcenter'),
            kipenztarform = $('#KipenztarForm'),
            bepenztarform = $('#BepenztarForm'),
            eladasform = $('#EladasForm'),
            koltsegform = $('#KoltsegForm');

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
            var ret = checkKelt($('#ElKeltEdit').val(), biztipus, bizszam);
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
            var keltedit = $('#ElKeltEdit'),
                esededit = $('#ElEsedekessegEdit'),
                kelt = keltedit.datepicker('getDate'),
                partner;
            if ($('#ElSzamlaEdit:checked').val() === 'szamla') {
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
                        esededit.datepicker('setDate', d.esedekesseg);
                    }
                });
            }
        }

        function setElControls() {
            var fm = $('#ElFizmodEdit option:selected'),
                tip = fm.data('tipus'),
                penztaredit = $('#ElPenztarEdit'),
                penztarrow = $('#ElPenztarRow'),
                bankszamlaedit = $('#ElBankszamlaEdit'),
                bankszamlarow = $('#ElBankszamlaRow');
            if (tip === 'P' || !fm.val()) {
                penztarrow.show();
                bankszamlarow.hide();
                bankszamlaedit.removeAttr('required');
                if ($('#ElVanPenzmozgas').prop('checked')) {
                    penztaredit.prop('required', true);
                    $('#ElPenzdatumEdit').prop('required', true);
                    $('#ElJogcimEdit').prop('required', true);
                    $('#ElOsszegEdit').prop('required', true);
                }
                else {
                    penztaredit.removeAttr('required');
                    $('#ElPenzdatumEdit').removeAttr('required');
                    $('#ElJogcimEdit').removeAttr('required');
                    $('#ElOsszegEdit').removeAttr('required');
                }
            }
            if (tip === 'B' && !fm.data('szepkartya') && !fm.data('aycm') && !fm.data('sportkartya')) {
                bankszamlarow.show();
                penztarrow.hide();
                penztaredit.removeAttr('required');
                if ($('#ElVanPenzmozgas').prop('checked')) {
                    bankszamlaedit.prop('required', true);
                    $('#ElPenzdatumEdit').prop('required', true);
                    $('#ElJogcimEdit').prop('required', true);
                    $('#ElOsszegEdit').prop('required', true);
                }
                else {
                    bankszamlaedit.removeAttr('required');
                    $('#ElPenzdatumEdit').removeAttr('required');
                    $('#ElJogcimEdit').removeAttr('required');
                    $('#ElOsszegEdit').removeAttr('required');
                }
            }
        }

        function setPartnerData(d, form) {
            $('input[name="partnervezeteknev"]', form).val(d.vezeteknev);
            $('input[name="partnerkeresztnev"]', form).val(d.keresztnev);
            $('input[name="partnerirszam"]', form).val(d.irszam);
            $('input[name="partnervaros"]', form).val(d.varos);
            $('input[name="partnerutca"]', form).val(d.utca);
            $('input[name="partnertelefon"]', form).val(d.telefon);
            $('input[name="partneremail"]', form).val(d.email);
        }

        function setTermekAr(form) {
            var partner = $('select[name="partner"] option:selected', form).val();

            $.ajax({
                async: false,
                url: '/admin/bizonylattetel/getar',
                data: {
                    partner: partner,
                    termek: $('select[name="termek"] option:selected', form).val()
                },
                success: function(data) {
                    var inp = $('input[name="egysegar"]', form),
                        adat = JSON.parse(data);
                    inp.val(adat.brutto);
                    inp.change();
                }
            });
        }

        function calcErtek(form) {
            var menny = $('input[name="mennyiseg"]', form).val() * 1,
                ear = $('input[name="egysegar"]', form).val() * 1;
            $('.js-ertek', form).text(menny * ear);
            $('input[name="penz"]', form).val(menny * ear);
        }

        mkwcomp.datumEdit.init('#KiKeltEdit');
        mkwcomp.datumEdit.init('#BeKeltEdit');
        mkwcomp.datumEdit.init('#ElKeltEdit');
        mkwcomp.datumEdit.init('#ElTeljesitesEdit');
        mkwcomp.datumEdit.init('#ElEsedekessegEdit');
        mkwcomp.datumEdit.init('#ElPenzdatumEdit');
        mkwcomp.datumEdit.init('#KtgKeltEdit');
        mkwcomp.datumEdit.init('#KtgTeljesitesEdit');
        mkwcomp.datumEdit.init('#KtgEsedekessegEdit');
        mkwcomp.datumEdit.init('#KtgPenzdatumEdit');
        mkwcomp.datumEdit.init('#SZEPKartyaErvenyessegEdit');

        $('.js-kihivatkozottbizonylatbutton, #KiOKButton, #KiCancelButton,' +
            '.js-behivatkozottbizonylatbutton, #BeOKButton, #BeCancelButton,' +
            '#ElOKButton, #ElCancelButton,' +
            '#KtgOKButton, #KtgCancelButton').button();

        function clearKoltsegform() {
            mkwcomp.datumEdit.clear('#KtgKeltEdit');
            mkwcomp.datumEdit.clear('#KtgTeljesitesEdit');
            mkwcomp.datumEdit.clear('#KtgEsedekessegEdit');
            mkwcomp.datumEdit.clear('#KtgPenzdatumEdit');
            $('#KtgPartnerEdit')[0].selectedIndex = 0;
            $('#KtgPartnervezeteknevEdit').val('');
            $('#KtgPartnerkeresztnevEdit').val('');
            $('#KtgPartnerirszamEdit').val('');
            $('#KtgPartnervarosEdit').val('');
            $('#KtgPartnerutcaEdit').val('');
            $('#KtgPartneremailEdit').val('');
            $('#KtgPartnertelefonEdit').val('');
            $('#KtgDolgozoEdit')[0].selectedIndex = 0;
            $('#KtgEgysarEdit').val('');
            $('#KtgMennyisegEdit').val(1);
            $('#KtgTermekEdit')[0].selectedIndex = 0;
            $('#KtgTermeknevEdit').val('');
            $('#KtgFizmodEdit')[0].selectedIndex = 0;
            $('#KtgPenztarEdit')[0].selectedIndex = 0;
            $('#KtgMegjegyzesEdit').val('');
            $('#KtgJogcimEdit')[0].selectedIndex = 0;
            $('#KtgErtek').text('');
            $('#KtgOsszegEdit').val('');
            $('#KtgVanPenzmozgas').attr('checked', 'checked');
            $('#KtgSzamlaEdit[value="koltsegszamla"]').prop('checked', true);
        }

        koltsegform.ajaxForm({
            type: 'POST',
            beforeSerialize: function(form, opt) {
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
                clearKoltsegform();
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

        koltsegform
            .on('change', '#KtgPenztarEdit', function(e) {
                var v = $('#KtgPenztarEdit option:selected').data('valutanem');
                $('input[name="valutanem"]', koltsegform).val(v);
            })
            .on('change', '#KtgPartnerEdit', function(e) {
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
                            setPartnerData(d, koltsegform);
                        }
                    });
                }
            })
            .on('change', '#KtgFizmodEdit', function(e) {
                var tip = $('#KtgFizmodEdit option:selected').data('tipus');
                if (tip === 'P' && $('#KtgVanPenzmozgas').prop('checked')) {
                    $('#KtgPenztarEdit').prop('required', true);
                    $('#KtgPenzdatumEdit').prop('required', true);
                    $('#KtgJogcimEdit').prop('required', true);
                    $('#KtgOsszegEdit').prop('required', true);
                }
                else {
                    $('#KtgPenztarEdit').removeAttr('required');
                    $('#KtgPenzdatumEdit').removeAttr('required');
                    $('#KtgJogcimEdit').removeAttr('required');
                    $('#KtgOsszegEdit').removeAttr('required');
                }
                $('#KtgVanPenzmozgas').prop('checked', tip === 'P');
            })
            .on('change', '#KtgVanPenzmozgas', function(e) {
                var tip = $('#KtgFizmodEdit option:selected').data('tipus');
                if (tip === 'P' && $('#KtgVanPenzmozgas').prop('checked')) {
                    $('#KtgPenztarEdit').prop('required', true);
                    $('#KtgPenzdatumEdit').prop('required', true);
                    $('#KtgJogcimEdit').prop('required', true);
                    $('#KtgOsszegEdit').prop('required', true);
                }
                else {
                    $('#KtgPenztarEdit').removeAttr('required');
                    $('#KtgPenzdatumEdit').removeAttr('required');
                    $('#KtgJogcimEdit').removeAttr('required');
                    $('#KtgOsszegEdit').removeAttr('required');
                }
            })
            .on('change', '#KtgTeljesitesEdit', function(e) {
                $('#KtgPenzdatumEdit').val($('#KtgTeljesitesEdit').val());
            })
            .on('change', '#KtgTermekEdit', function(e) {
                $('#KtgTermeknevEdit').val($('#KtgTermekEdit option:selected').text());
                setTermekAr(koltsegform);
            })
            .on('change', '#KtgMennyisegEdit, #KtgEgysarEdit', function(e) {
                calcErtek(koltsegform);
            })
            .on('click', '#KtgCancelButton', function(e) {
                e.preventDefault();
                clearKoltsegform();
            });

        function clearEladasform() {
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
            $('#ElPenztarEdit')[0].selectedIndex = 0;
            $('#ElBankszamlaEdit')[0].selectedIndex = 0;
            $('#ElMegjegyzesEdit').val('');
            $('#ElJogcimEdit')[0].selectedIndex = 0;
            $('#ElErtek').text('');
            $('#ElOsszegEdit').val('');
            $('#SZEPKartyaTipusEdit').selectedIndex = 0;
            $('#SZEPKartyaNevEdit').val('');
            $('#SZEPKartyaSzamEdit').val('');
            $('#ElVanPenzmozgas').attr('checked', 'checked');
            $('#ElBankszamlaRow').hide();
            $('#ElPenztarRow').show();
            $('#ElSzamlaEdit[value="egyeb"]').prop('checked', true);
            $('#ElTeljesitesRow').hide();
            $('#ElEsedekessegRow').hide();
            $('#ElTeljesitesEdit').removeAttr('required');
            $('#ElEsedekessegEdit').removeAttr('required');
            mkwcomp.datumEdit.clear('#SZEPKartyaErvenyessegEdit');
        }

        eladasform.ajaxForm({
            type: 'POST',
            beforeSerialize: function(form, opt) {
                var fm = $('#ElFizmodEdit option:selected'),
                    tip = fm.data('tipus');
                if (tip === 'B' && !fm.data('szepkartya') && !fm.data('aycm') && !fm.data('sportkartya') && $('#ElSzamlaEdit:checked').val() !== 'szamla') {
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
                if ($('#ElSzamlaEdit:checked').val() === 'szamla') {
                    if (!checkBizonylatFej($('#ElSzamlaEdit:checked').val())) {
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
                clearEladasform();
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
                if ($('#ElSzamlaEdit:checked').val() === 'szamla') {
                    $('#ElTeljesitesRow').show();
                    $('#ElEsedekessegRow').show();
                    $('#ElTeljesitesEdit').prop('required', true);
                    $('#ElEsedekessegEdit').prop('required', true);
                }
                else {
                    $('#ElTeljesitesRow').hide();
                    $('#ElEsedekessegRow').hide();
                    $('#ElTeljesitesEdit').removeAttr('required');
                    $('#ElEsedekessegEdit').removeAttr('required');
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
                            setPartnerData(d, eladasform);
                            setElDates();
                        }
                    });
                }
            })
            .on('change', '#ElFizmodEdit', function(e) {
                var fm = $('#ElFizmodEdit option:selected'),
                    szep = fm.data('szepkartya'),
                    tip = fm.data('tipus');
                setElControls();
                if (szep == 1) {
                    $('tr.szepkartya').removeClass('hidden');
                }
                else {
                    $('tr.szepkartya').addClass('hidden');
                }
                $('#ElVanPenzmozgas').prop('checked', tip === 'P');
                setElDates();
            })
            .on('change', '#ElVanPenzmozgas', function(e) {
                setElControls();
            })
            .on('change', '#ElKeltEdit', function(e) {
                $('#ElPenzdatumEdit').val($('#ElKeltEdit').val());
            })
            .on('change', '#ElTermekEdit', function(e) {
                setTermekAr(eladasform);
            })
            .on('change', '#ElMennyisegEdit, #ElEgysarEdit', function(e) {
                calcErtek(eladasform);
            })
            .on('click', '#ElCancelButton', function(e) {
                e.preventDefault();
                clearEladasform();
            });

        function clearKipenztarform() {
            mkwcomp.datumEdit.clear('#KiKeltEdit');
            $('#KiPenztarEdit')[0].selectedIndex = 0;
            $('#KiPartnerEdit')[0].selectedIndex = 0;
            $('#KiPartnervezeteknevEdit').val('');
            $('#KiPartnerkeresztnevEdit').val('');
            $('#KiPartnerirszamEdit').val('');
            $('#KiPartnervarosEdit').val('');
            $('#KiPartnerutcaEdit').val('');
            $('#KiEmailEdit').val('');
            $('#KiTelefonEdit').val('');
            $('#KiMegjegyzesEdit').val('');
            $('#KiSzovegEdit').val('');
            $('#KiJogcimEdit')[0].selectedIndex = 0;
            $('#KiHivatkozottBizonylatEdit').val('');
            $('#KiEsedekessegEdit').val('');
            $('#KiOsszegEdit').val('');
        }

        kipenztarform.ajaxForm({
            type: 'POST',
            beforeSerialize: function(form, opt) {
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
                clearKipenztarform();
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

        kipenztarform
            .on('change', '#KiPenztarEdit', function(e) {
                var v = $('#KiPenztarEdit option:selected').data('valutanem');
                $('input[name="valutanem"]', kipenztarform).val(v);
            })
            .on('click', '.js-kihivatkozottbizonylatbutton', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '/admin/partner/getkiegyenlitetlenbiz',
                    data: {
                        partner: $('select[name="partner"]', kipenztarform).val(),
                        irany: $('input[name="irany"]', kipenztarform).val()
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
                                    $('input[name="tetelhivatkozottbizonylat"]', kipenztarform).val(sor.data('bizszam'));
                                    $('input[name="tetelhivatkozottdatum"]', kipenztarform).val(sor.data('datum'));
                                    $('input[name="tetelosszeg"]', kipenztarform).val(sor.data('egyenleg'));
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
            .on('change', '#KiPartnerEdit', function(e) {
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
                            setPartnerData(d, kipenztarform);
                        }
                    });
                }
            })
            .on('click', '#KiCancelButton', function(e) {
                e.preventDefault();
                clearKipenztarform();
            });

        function clearBepenztarform() {
            mkwcomp.datumEdit.clear('#BeKeltEdit');
            $('#BePenztarEdit')[0].selectedIndex = 0;
            $('#BePartnerEdit')[0].selectedIndex = 0;
            $('#BePartnervezeteknevEdit').val('');
            $('#BePartnerkeresztnevEdit').val('');
            $('#BePartnerirszamEdit').val('');
            $('#BePartnervarosEdit').val('');
            $('#BePartnerutcaEdit').val('');
            $('#BeEmailEdit').val('');
            $('#BeTelefonEdit').val('');
            $('#BeMegjegyzesEdit').val('');
            $('#BeSzovegEdit').val('');
            $('#BeJogcimEdit')[0].selectedIndex = 0;
            $('#BeHivatkozottBizonylatEdit').val('');
            $('#BeEsedekessegEdit').val('');
            $('#BeOsszegEdit').val('');
        }

        bepenztarform.ajaxForm({
            type: 'POST',
            beforeSerialize: function(form, opt) {
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
                clearBepenztarform();
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
        bepenztarform
            .on('change', '#BePenztarEdit', function(e) {
                var v = $('#BePenztarEdit option:selected').data('valutanem');
                $('input[name="valutanem"]', bepenztarform).val(v);
            })
            .on('click', '.js-behivatkozottbizonylatbutton', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '/admin/partner/getkiegyenlitetlenbiz',
                    data: {
                        partner: $('select[name="partner"]', bepenztarform).val(),
                        irany: $('input[name="irany"]', bepenztarform).val()
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
                                    $('input[name="tetelhivatkozottbizonylat"]', bepenztarform).val(sor.data('bizszam'));
                                    $('input[name="tetelhivatkozottdatum"]', bepenztarform).val(sor.data('datum'));
                                    $('input[name="tetelosszeg"]', bepenztarform).val(sor.data('egyenleg'));
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
            .on('change', '#BePartnerEdit', function(e) {
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
                            setPartnerData(d, bepenztarform);
                        }
                    });
                }
            })
            .on('click', '#BeCancelButton', function(e) {
                e.preventDefault();
                clearBepenztarform();
            });
        dialogcenter.on('click', 'tr', function(e) {
            e.preventDefault();
            $('tr', dialogcenter).removeClass('ui-state-highlight js-selected');
            $(this).addClass('ui-state-highlight js-selected');
        })
	}
);