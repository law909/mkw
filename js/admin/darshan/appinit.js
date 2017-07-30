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

function messagecenterclick(e){
	e.preventDefault();
	$(this)
		.slideToggle('slow',function(){
			$(this).removeClass('matt-messagecenter ui-widget ui-state-highlight');
		});
}

function messagecenterclickonerror(e){
	e.preventDefault();
	$(this)
		.slideToggle('slow',function(){
			$(this).removeClass('matt-messagecenter ui-widget ui-state-error');
		});
}

$(document).ready(
	function(){

		var msgcenter = $('#messagecenter').hide(),
            dialogcenter = $('#dialogcenter'),
            kipenztarform = $('#KipenztarForm'),
            bepenztarform = $('#BepenztarForm'),
            eladasform = $('#EladasForm'),
            koltsegform = $('#KoltsegForm');

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



        $('.menupont').button();
		var menu=$('#menu');
		$('#kozep').css('left',menu.outerWidth()+menu.offset().left*2+'px');
		var menubar=$('.menu-titlebar');
		menubar.addClass('mattedit-titlebar ui-widget-header ui-helper-clearfix ui-corner-all');
		menubar.each(function() {
			$this=$(this);
			var ref=$($this.attr('data-refcontrol'));
			if (ref.attr('data-visible')=='hidden') {
				$this.append('<a href="#" class="mattedit-titlebar-close">'+
					'<span class="ui-icon ui-icon-circle-triangle-s"></span></a>'+
					'<span class="ui-jqgrid-title">'+$this.attr('data-caption')+'</span>');
				ref.hide();
			}
			else {
				$this.append('<a href="#" class="mattedit-titlebar-close">'+
					'<span class="ui-icon ui-icon-circle-triangle-n"></span></a>'+
					'<span class="ui-jqgrid-title">'+$this.attr('data-caption')+'</span>');
			}
		});
		menubar.on('click',function(e) {
			e.preventDefault();
			var ref=$($(this).attr('data-refcontrol'));
			if (ref.attr('data-visible')=='hidden') {
				ref.attr('data-visible','visible');
				ref.slideDown(50);
				$('> a > span',this).removeClass('ui-icon-circle-triangle-s').addClass('ui-icon-circle-triangle-n');
			}
			else {
				ref.attr('data-visible','hidden');
				ref.slideUp(50);
				$('> a > span',this).removeClass('ui-icon-circle-triangle-n').addClass('ui-icon-circle-triangle-s');
			}
		});
		$(document)
				.ajaxStart(pleaseWait)
				.ajaxStop($.unblockUI)
				.ajaxError(function(e, xhr, settings, exception) {
					alert('error in: ' + settings.url + ' \n'+'error:\n' + exception);
				});
		$('#ThemeSelect').change(function(e) {
			$.ajax({url:'/admin/setuitheme',
				data:{uitheme:this.options[this.selectedIndex].value},
				success:function(data) {
					window.location.reload();
				}
			});
		});
		$('.js-regeneratekarkod').on('click', function(e) {
            e.preventDefault();
			$.ajax({
                url:'/admin/regeneratekarkod'
			});
		});

        mkwcomp.datumEdit.init('#KiKeltEdit');
        mkwcomp.datumEdit.init('#BeKeltEdit');
        mkwcomp.datumEdit.init('#ElKeltEdit');
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
                msgcenter
                    .html('A mentés sikerült.')
                    .hide()
                    .addClass('matt-messagecenter ui-widget ui-state-highlight')
                    .one('click', messagecenterclick)
                    .slideToggle('slow');
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
            $('#ElMegjegyzesEdit').val('');
            $('#ElJogcimEdit')[0].selectedIndex = 0;
            $('#ElErtek').text('');
            $('#ElOsszegEdit').val('');
            $('#SZEPKartyaTipusEdit').selectedIndex = 0;
            $('#SZEPKartyaNevEdit').val('');
            $('#SZEPKartyaSzamEdit').val('');
            $('#ElVanPenzmozgas').attr('checked', 'checked');
            mkwcomp.datumEdit.clear('#SZEPKartyaErvenyessegEdit');
        }

        eladasform.ajaxForm({
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
                clearEladasform();
                msgcenter
                    .html('A mentés sikerült.')
                    .hide()
                    .addClass('matt-messagecenter ui-widget ui-state-highlight')
                    .one('click', messagecenterclick)
                    .slideToggle('slow');
            }
        });

        eladasform
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
                        }
                    });
                }
            })
            .on('change', '#ElFizmodEdit', function(e) {
                var tip = $('#ElFizmodEdit option:selected').data('tipus'),
                    szep = $('#ElFizmodEdit option:selected').data('szepkartya');
                if (tip === 'P' && $('#ElVanPenzmozgas').prop('checked')) {
                    $('#ElPenztarEdit').prop('required', true);
                    $('#ElPenzdatumEdit').prop('required', true);
                    $('#ElJogcimEdit').prop('required', true);
                    $('#ElOsszegEdit').prop('required', true);
                }
                else {
                    $('#ElPenztarEdit').removeAttr('required');
                    $('#ElPenzdatumEdit').removeAttr('required');
                    $('#ElJogcimEdit').removeAttr('required');
                    $('#ElOsszegEdit').removeAttr('required');
                }
                if (szep == 1) {
                    $('tr.szepkartya').removeClass('hidden');
                }
                else {
                    $('tr.szepkartya').addClass('hidden');
                }
            })
            .on('change', '#ElVanPenzmozgas', function(e) {
                var tip = $('#ElFizmodEdit option:selected').data('tipus');
                if (tip === 'P' && $('#ElVanPenzmozgas').prop('checked')) {
                    $('#ElPenztarEdit').prop('required', true);
                    $('#ElPenzdatumEdit').prop('required', true);
                    $('#ElJogcimEdit').prop('required', true);
                    $('#ElOsszegEdit').prop('required', true);
                }
                else {
                    $('#ElPenztarEdit').removeAttr('required');
                    $('#ElPenzdatumEdit').removeAttr('required');
                    $('#ElJogcimEdit').removeAttr('required');
                    $('#ElOsszegEdit').removeAttr('required');
                }
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
                msgcenter
                    .html('A mentés sikerült.')
                    .hide()
                    .addClass('matt-messagecenter ui-widget ui-state-highlight')
                    .one('click', messagecenterclick)
                    .slideToggle('slow');
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
                msgcenter
                    .html('A mentés sikerült.')
                    .hide()
                    .addClass('matt-messagecenter ui-widget ui-state-highlight')
                    .one('click', messagecenterclick)
                    .slideToggle('slow');
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

	}
);