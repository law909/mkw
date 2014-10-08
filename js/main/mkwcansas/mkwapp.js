var mkwmsg = {
	TermekErtesitoKoszonjuk: 'Köszönjük feliratkozását!<br>Azonnal értesíteni fogjuk, amint a termék kapható lesz.',
	TermekErtesitoLeiratkozas: 'A leiratkozás folyamatban van.',
	TermekKosarba: 'A terméket a kosarába tettük...',
	TermekValtozatotValassz: 'Válassza ki a lenyíló listából, hogy pontosan milyen termékváltozatot szeretne!',
	KosarMennyisegModositas: 'Módosítottuk a mennyiséget.',
	KosarMennyisegNulla: 'Ha nem kíván vásárolni a termékből, a <b>"Töröl"</b> gombbal veheti ki a kosárból.',
	ChkRegLogin: 'Válassza ki, hogy szeretne-e regisztrálni a vásárláshoz, vagy jelentkezzen be, ha már van nálunk fiókja.',
	ChkASZF: 'Megrendelés előtt kérjük, fogadja el az <b>ÁSZF</b>-et.<br>Ehhez kattintson az <b>"Elolvastam és elfogadom az ÁSZF-et"</b> sor előtti négyzetre.',
	ChkKosarValtozott: 'A kosár tartalma időközben megváltozott, kérem ellenőrizze.',
	ChkKosarUres: 'Az Ön kosara üres.',
	FiokAdataitModositjuk: 'Adatait módosítjuk...',
	DialogFejlec: 'Értesítés',
	DialogOk: 'OK',
    PassChange: ['A jelszómódosítás sikerült.', 'A két jelszó nem egyezik.', 'Hibás jelszót adott meg.','Nem adott meg új jelszót.'],
    PassReminderCreated: 'Emailcímére elküldtünk egy jelszóemlékeztető linket.',
    PassReminderRequiredEmail: 'Adja meg azt az emailcímet, amellyel regisztrált.'
};
var mkw = (function($) {

    function showMessage(msg) {
        var msgcenter = $('#messagecenter');
        msgcenter.html(msg);
        $.magnificPopup.open({
            modal: true,
            items: [
                {
                    src: msgcenter,
                    type: 'inline'
                }
            ]
        });
    }

    function closeMessage() {
        $.magnificPopup.close();
    }

    function showDialog(msg, options) {
        var dlgcenter = $('#dialogcenter'),
                dlgheader = $('.modal-header', dlgcenter),
                dlgbody = $('.modal-body', dlgcenter).empty(),
                dlgfooter = $('.modal-footer', dlgcenter).empty(),
                classes = 'btn';
        $('h4', dlgheader).remove();
        opts = $.extend(null, options, {
            header: mkwmsg.DialogFejlec,
            buttons: [{
                    caption: mkwmsg.DialogOk,
                    _class: 'okbtn',
                    click: function(e) {
                        e.preventDefault();
                        closeDialog();
                    }
                }]
        });
        if (opts.header) {
            dlgheader.append('<h4>' + opts.header + '</h4>');
        }
        if (msg) {
            dlgbody.append('<p>' + msg + '</p>');
        }
        for (var i = 0; i < opts.buttons.length; i++) {
            if (opts.buttons[i]._class) {
                classes = classes + ' ' + opts.buttons[i]._class;
            }
            $('<button class="' + classes + '">' + opts.buttons[i].caption + '</button>')
                    .appendTo(dlgfooter)
                    .on('click', opts.buttons[i].click);
        }
        dlgcenter.modal();
    }

    function closeDialog() {
        var dlgcenter = $('#dialogcenter');
        dlgcenter.modal('hide');
    }

    function lapozas(page) {
        var lf = $('.lapozoform'), url,
                filterstr = '';
        if (!page) {
            page = lf.data('pageno');
        }
        url = lf.data('url') + '?pageno=' + page,
                url = url + '&elemperpage=' + $('.elemperpageedit').val() + '&order=' + $('.orderedit').val();
        $('#szuroform input:checkbox:checked').each(function() {
            filterstr = filterstr + $(this).prop('name') + ',';
        });
        if (filterstr !== '') {
            url = url + '&filter=' + filterstr;
        }
        url = url + '&arfilter=' + $('#ArSlider').val();
        url = url + '&keresett=' + $('.KeresettEdit').val();
        url = url + '&vt=' + $('#ListviewEdit').val();
        document.location = url;
    }

    function overrideFormSubmit(form, msg, events) {
        var $form = form;
        if (!events) {
            events = {};
        }
        if (typeof form == 'string') {
            $form = $(form);
        }
        $form.on('submit', function(e) {
            e.preventDefault();
            var data = {jax: 1};
            $form.find('input').each(function() {
                var $this = $(this);
                switch ($this.attr('type')) {
                    case 'checkbox':
                        data[$this.attr('name')] = $this.prop('checked');
                        break;
                    default:
                        data[$this.attr('name')] = $this.val();
                        break;
                }
            });
            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: data,
                beforeSend: function(xhr, settings) {
                    var ret = true;
                    if (typeof events.beforeSend == 'function') {
                        ret = events.beforeSend.call($form, xhr, settings, data);
                    }
                    if (msg) {
                        showMessage(msg);
                    }
                    return ret;
                },
                complete: function(xhr, status) {
                    if (msg) {
                        closeMessage();
                    }
                    if (typeof events.complete == 'function') {
                        events.complete.call($form, xhr, status);
                    }
                },
                error: function(xhr, status, error) {
                    if (typeof events.error == 'function') {
                        events.error.call($form, xhr, status);
                    }
                },
                success: function(data, status, xhr) {
                    if (typeof events.success == 'function') {
                        events.success.call($form, data, status, xhr);
                    }
                }
            });
        });

    }

    function irszamTypeahead(irszaminput, varosinput) {
        if ($.fn.typeahead) {
            var map = {};
            $(irszaminput).typeahead({
                source: function(query, process) {
                    var texts = [];
                    return $.ajax({
                        url: '/irszam',
                        type: 'GET',
                        data: {
                            term: query
                        },
                        success: function(data) {
                            var d = JSON.parse(data);
                            $.each(d, function(i, irszam) {
                                map[irszam.id] = irszam;
                                texts.push(irszam.id);
                            });
                            return process(texts);
                        }
                    });
                },
                updater: function(item) {
                    var irsz = map[item];
                    item = irsz.szam;
                    $(varosinput).val(irsz.nev);
                    return item;
                },
                items: 999999,
                minLength: 2
            });
        }
    }

    function varosTypeahead(irszaminput, varosinput) {
        if ($.fn.typeahead) {
            var map = {};
            $(varosinput).typeahead({
                source: function(query, process) {
                    var texts = [];
                    return $.ajax({
                        url: '/varos',
                        type: 'GET',
                        data: {
                            term: query
                        },
                        success: function(data) {
                            var d = JSON.parse(data);
                            $.each(d, function(i, irszam) {
                                map[irszam.id] = irszam;
                                texts.push(irszam.id);
                            });
                            return process(texts);
                        }
                    });
                },
                updater: function(item) {
                    var irsz = map[item];
                    item = irsz.nev;
                    $(irszaminput).val(irsz.szam);
                    return item;
                },
                items: 999999,
                minLength: 4
            });
        }
    }

    function initTooltips() {
        $('.js-tooltipbtn').tooltip({
            html: false,
            placement: 'right',
            container: 'body'
        });
    }

    function showhideFilterClear() {
        var $arslider = $('#ArSlider'),
                arfi = $arslider.val(), arfiarr,
                maxar = $arslider.data('maxar');

        if (arfi) {
            arfiarr = arfi.split(';');
            if ((arfiarr[0] * 1 !== 0) || (arfiarr[1] * 1 <= maxar) || $('#szuroform input[type="checkbox"]:checked').length) {
                $('.js-filterclear').show();
            }
            else {
                $('.js-filterclear').hide();
            }
        }
        else {
            $('.js-filterclear').hide();
        }
    }

    return {
        showMessage: showMessage,
        closeMessage: closeMessage,
        showDialog: showDialog,
        closeDialog: closeDialog,
        lapozas: lapozas,
        overrideFormSubmit: overrideFormSubmit,
        irszamTypeahead: irszamTypeahead,
        varosTypeahead: varosTypeahead,
        initTooltips: initTooltips,
        showhideFilterClear: showhideFilterClear
    };
})(jQuery);
var mkwcheck={

	configs:{
		kapcsolatNev: {
			nev: '#NevEdit',
			msg: '#NevMsg'
		},
		kapcsolatEmail: {
			email1: '#Email1Edit',
			msg1: '#Email1Msg',
			email2: '#Email2Edit',
			msg2: '#Email2Msg'
		},
		kapcsolatTema: {
			tema: '#TemaEdit',
			msg: '#TemaMsg'
		},

		regNev: {
			nev1: '#VezeteknevEdit',
			nev2: '#KeresztnevEdit',
			msg1: ''
		},
		regEmail: {
			email: '#EmailEdit',
			msg: ''
		},
		regJelszo: {
			jelszo1: '#Jelszo1Edit',
			jelszo2: '#Jelszo2Edit',
			msg: ''
		},

		loginEmail: {
			email: 'input[name="email"]',
			msg: ''
		},

		checkoutJelszo: {
			jelszo1: 'input[name="jelszo1"]',
			jelszo2: 'input[name="jelszo2"]',
			msg: ''
		},
		checkoutTelefon: {
			nev: 'input[name="telefon"]',
			msg: ''
		}
	},

	kapcsolatTemaCheck:function() { this.temacheck(this.configs.kapcsolatTema); },
	kapcsolatEmailCheck:function() { this.doubleemailcheck(this.configs.kapcsolatEmail); },
	kapcsolatNevCheck:function() { this.nevcheck(this.configs.kapcsolatNev); },

	regNevCheck:function() { this.doublenevcheck(this.configs.regNev); },
	regEmailCheck:function() { this.emailcheck(this.configs.regEmail); },
	regJelszoCheck:function() { this.pwcheck(this.configs.regJelszo); },

	loginEmailCheck:function() { this.emailcheck(this.configs.loginEmail); },

	checkoutJelszoCheck:function() { this.pwcheck(this.configs.checkoutJelszo); },
	checkoutTelefonCheck:function() {this.nevcheck(this.configs.checkoutTelefon); },

	wasinteraction:{
		nev:false,
		doublenev:false,
		email:false,
		doubleemail:false,
		pw:false,
		tema:false
	},

	nevcheck:function(opt) {
		var vnev=$(opt.nev),
			msg=vnev.data('errormsg'),
			nevmsg=$(opt.msg);
		vnev[0].setCustomValidity('');
		nevmsg.empty();
		if (this.wasinteraction.nev) {
			vnev.removeClass('error').addClass('valid');
		}
		if (vnev[0].validity.valueMissing) {
			if (this.wasinteraction.nev) {
				nevmsg.append(msg);
			}
			if (vnev[0].validity.valueMissing) {
				vnev[0].setCustomValidity(msg);
			}
		}
	},
	doublenevcheck:function(opt) {
		var vnev=$(opt.nev1),
			msg=vnev.data('errormsg'),
			knev=$(opt.nev2),
			nevmsg=$(opt.msg);
		vnev[0].setCustomValidity('');
		knev[0].setCustomValidity('');
		nevmsg.empty();
		if (vnev[0].validity.valueMissing||knev[0].validity.valueMissing) {
			if (this.wasinteraction.doublenev) {
				nevmsg.append(msg);
			}
			if (vnev[0].validity.valueMissing) {
				vnev[0].setCustomValidity(msg);
			}
			if (knev[0].validity.valueMissing) {
				knev[0].setCustomValidity(msg);
			}
		}
	},
	emailcheck:function(opt) {
		var email=$(opt.email),
			msg1=email.data('errormsg1'),
			msg2=email.data('errormsg2'),
			emailmsg=$(opt.msg),
			srvhiba=email.data('hiba')||{hibas:false};
		email[0].setCustomValidity('');
		emailmsg.empty();
		if (srvhiba.hibas) {
			emailmsg.append(srvhiba.uzenet);
			email[0].setCustomValidity(srvhiba.uzenet);
			email[0].checkValidity();
		}
		else {
			if (this.wasinteraction.email) {
				email.removeClass('error').addClass('valid');
			}
			if (email[0].validity.valueMissing) {
				if (this.wasinteraction.email) {
					emailmsg.append(msg1);
				}
				email[0].setCustomValidity(msg1);
			}
			else {
				if (email[0].validity.typeMismatch) {
					if (this.wasinteraction.email) {
						emailmsg.append(msg2);
					}
					email[0].setCustomValidity(msg2);
				}
			}
		}
	},
	doubleemailcheck:function(opt) {
		var email1=$(opt.email1),
			email1msg=$(opt.msg1),
			msg1=email1.data('errormsg1'),
			msg2=email1.data('errormsg2'),
			msg3=email1.data('errormsg3'),
			email2=$(opt.email2),
			email2msg=$(opt.msg2),
			srvhiba1=email1.data('hiba')||{hibas:false},
			srvhiba2=email2.data('hiba')||{hibas:false};
		email1[0].setCustomValidity('');
		email2[0].setCustomValidity('');
		email1msg.empty();
		email2msg.empty();
		if (srvhiba1.hibas||srvhiba2.hibas) {
			if (srvhiba1.hibas) {
				email1msg.append(srvhiba1.uzenet);
				email1[0].setCustomValidity(srvhiba1.uzenet);
				email1[0].checkValidity();
			}
			if (srvhiba2.hibas) {
				email2msg.append(srvhiba2.uzenet);
				email2[0].setCustomValidity(srvhiba2.uzenet);
				email2[0].checkValidity();
			}
		}
		else {
			if (this.wasinteraction.doubleemail) {
				email1.removeClass('error').addClass('valid');
				email2.removeClass('error').addClass('valid');
			}
			if (email1.val()!=email2.val()) {
				if (this.wasinteraction.doubleemail) {
					email2msg.append(msg3);
				}
				email1[0].setCustomValidity(msg3);
				email2[0].setCustomValidity(msg3);
			}
			else {
				if (email1[0].validity.valueMissing) {
					email1[0].setCustomValidity(msg1);
					if (this.wasinteraction.doubleemail) {
						email1msg.append(msg1);
					}
				}
				else {
					if (email1[0].validity.typeMismatch) {
						email1[0].setCustomValidity(msg2);
						if (this.wasinteraction.doubleemail) {
							email1msg.append(msg2);
						}
					}
				}
				if (email2[0].validity.valueMissing) {
					email2[0].setCustomValidity(msg1);
					if (this.wasinteraction.doubleemail) {
						email2msg.append(msg1);
					}
				}
				else {
					if (email2[0].validity.typeMismatch) {
						email2[0].setCustomValidity(msg2);
						if (this.wasinteraction.doubleemail) {
							email2msg.append(msg2);
						}
					}
				}
			}
		}
	},
	pwcheck:function(opt) {
		var pw1=$(opt.jelszo1),
			msg1=pw1.data('errormsg1'),
			msg2=pw1.data('errormsg2'),
			pw2=$(opt.jelszo2),
			pwmsg=$(opt.msg);
		pw1[0].setCustomValidity('');
		pw2[0].setCustomValidity('');
		pwmsg.empty();
		if (pw1.val()!==pw2.val()) {
			if (this.wasinteraction.pw) {
				pwmsg.append(msg2);
			}
			pw2[0].setCustomValidity(msg2);
		}
		else {
			if (pw1[0].validity.valueMissing||pw2[0].validity.valueMissing) {
				if (this.wasinteraction.pw) {
					pwmsg.append(msg1);
				}
				if (pw1[0].validity.valueMissing) {
					pw1[0].setCustomValidity(msg1);
				}
				if (pw2[0].validity.valueMissing) {
					pw2[0].setCustomValidity(msg1);
				}
			}
		}
	},
	temacheck:function(opt) {
		var tema=$(opt.tema),
			msg=tema.data('errormsg'),
			temamsg=$(opt.msg);
		tema[0].setCustomValidity('');
		temamsg.empty();
		if (this.wasinteraction.tema) {
			tema.removeClass('error').addClass('valid');
		}
		if (tema[0].validity.valueMissing) {
			tema[0].setCustomValidity(msg);
			if (this.wasinteraction.tema) {
				temamsg.append(msg);
			}
		}
	}
};
var guid = (function() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
               .toString(16)
               .substring(1);
  }
  return function() {
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
           s4() + '-' + s4() + s4() + s4();
  };
})();

var checkout = (function($, guid) {

	var checkoutpasswordrow,
			checkoutpasswordcontainer,
			vezeteknevinput, keresztnevinput, telefoninput, kapcsemailinput,
			szamlanevinput, szamlairszaminput, szamlavarosinput, szamlautcainput, adoszaminput,
			szallnevinput, szallirszaminput, szallvarosinput, szallutcainput,
			checkoutform,
			webshopmessageinput, couriermessageinput,
			szamlaeqszall,
			kosarhash,
            egyediid = guid();

    function ajaxlog(str) {
        $.ajax({
            type: 'POST',
            url: '/ajaxlogger',
            data: {
                req: 'write',
                data: egyediid + ' ## ' + str
            }
        });
    }

	function loadFizmodList() {
		$.ajax({
			url: '/checkout/getfizmodlist',
			data: {
				szallitasimod: $('input[name="szallitasimod"]:checked').val()
			},
			success: function(data) {
                var d = JSON.parse(data);
				$('.js-chkfizmodlist').html(d.html);
				refreshAttekintes();
			}
		});
        loadTetelList();
	}

    function loadKosarHash() {
        $.ajax({
            url: '/kosar/gethash',
            success: function(data) {
                var d = JSON.parse(data);
                kosarhash = d.value;
            }
        });
    }

    function loadTetelList() {
        $.ajax({
            url: '/checkout/gettetellist',
			data: {
				szallitasimod: $('input[name="szallitasimod"]:checked').val()
			},
            success: function(data) {
                var d = JSON.parse(data);
                $('.js-chktetellist').html(d.html);
                kosarhash = d.hash.value;
            }
        });
    }

	function refreshAttekintes() {
		$('.js-chkvezeteknev').text(vezeteknevinput.val());
		$('.js-chkkeresztnev').text(keresztnevinput.val());
		$('.js-chktelefon').text(telefoninput.val());
		$('.js-chkkapcsemail').text(kapcsemailinput.val());
		$('.js-chkszamlanev').text(szamlanevinput.val());
		$('.js-chkszamlairszam').text(szamlairszaminput.val());
		$('.js-chkszamlavaros').text(szamlavarosinput.val());
		$('.js-chkszamlautca').text(szamlautcainput.val());
		$('.js-chkadoszam').text(adoszaminput.val());
		if (szamlaeqszall.prop('checked')) {
			$('.js-chkszallnev').text(szamlanevinput.val());
			$('.js-chkszallirszam').text(szamlairszaminput.val());
			$('.js-chkszallvaros').text(szamlavarosinput.val());
			$('.js-chkszallutca').text(szamlautcainput.val());
		}
		else {
			$('.js-chkszallnev').text(szallnevinput.val());
			$('.js-chkszallirszam').text(szallirszaminput.val());
			$('.js-chkszallvaros').text(szallvarosinput.val());
			$('.js-chkszallutca').text(szallutcainput.val());
		}
		$('.js-chkszallitasimod').text($('input[name="szallitasimod"]:checked').data('caption'));
		$('.js-chkfizmod').text($('input[name="fizetesimod"]:checked').data('caption'));
		$('.js-chkwebshopmessage').text(webshopmessageinput.val());
		$('.js-chkcouriermessage').text(couriermessageinput.val());
	}

	function openDataContainer(obj) {
		var $this = $(obj),
				mycontainer = $($this.data('container'));
		if (mycontainer.hasClass('js-chkclosed')) {
			$('.js-chkdatacontainer').slideUp(0).addClass('js-chkclosed');
			mycontainer.slideDown(0).removeClass('js-chkclosed');
		}
	}

	function initUI() {
		var $checkout = $('.js-checkout');

		if ($checkout.length) {

			$('.js-chktooltipbtn').tooltip({
				html: false,
				placement: 'right',
				container: 'body'
			});

			checkoutform = $('#CheckoutForm');
			checkoutpasswordcontainer = $('.js-checkoutpasswordcontainer');
            checkoutpasswordrow = $('.js-checkoutpasswordrow').remove();

			vezeteknevinput = $('input[name="vezeteknev"]');
			keresztnevinput = $('input[name="keresztnev"]');
			telefoninput = $('input[name="telefon"]');
			kapcsemailinput = $('input[name="kapcsemail"]');
			szamlanevinput = $('input[name="szamlanev"]');
			szamlairszaminput = $('input[name="szamlairszam"]');
			szamlavarosinput = $('input[name="szamlavaros"]');
			szamlautcainput = $('input[name="szamlautca"]');
			adoszaminput = $('input[name="adoszam"]');
			szallnevinput = $('input[name="szallnev"]');
			szallirszaminput = $('input[name="szallirszam"]');
			szallvarosinput = $('input[name="szallvaros"]');
			szallutcainput = $('input[name="szallutca"]');
			szamlaeqszall = $('input[name="szamlaeqszall"]');
			webshopmessageinput = $('textarea[name="webshopmessage"]');
			couriermessageinput = $('textarea[name="couriermessage"]');

			loadFizmodList();

			$checkout
			.on('change', 'input[name="szallitasimod"]', function() {
				loadFizmodList();
			})
			.on('change', 'input[name="regkell"]', function() {
				checkoutpasswordcontainer.empty();
				if ($('input[name="regkell"]:checked').val() * 1 === 2) {
					$('input[name="szamlasave"]').prop('checked',true);
					$('.js-szamlasave').removeClass('notvisible');
					$('input[name="szallsave"]').prop('checked',true);
					$('.js-szallsave').removeClass('notvisible');
					checkoutpasswordrow.appendTo(checkoutpasswordcontainer);
                    $('input[name="jelszo1"],input[name="jelszo2"]').on('invalid', function() {
                        openDataContainer(this);
                    });
					$('.js-chktooltipbtn').tooltip({
						html: false,
						placement: 'right',
						container: 'body'
					});
				}
				else {
					$('input[name="szamlasave"]').prop('checked',false);
					$('.js-szamlasave').addClass('notvisible');
					$('input[name="szallsave"]').prop('checked',false)
					$('.js-szallsave').addClass('notvisible');
				}
			})
			.on('change', '.js-chkrefresh', function() {
				refreshAttekintes();
			})
			.on('input', 'input[name="jelszo1"],input[name="jelszo2"]', function(e) {
				mkwcheck.checkoutJelszoCheck();
				$(this).off('keydown');
			})
			.on('keydown blur', 'input[name="jelszo1"],input[name="jelszo2"]', function(e) {
				mkwcheck.wasinteraction.pw = true;
				mkwcheck.checkoutJelszoCheck();
			})
			.on('blur', 'input[name="vezeteknev"],input[name="keresztnev"]', function() {
				if (!szamlanevinput.val() && vezeteknevinput.val() && keresztnevinput.val()) {
					szamlanevinput.val(vezeteknevinput.val() + ' ' + keresztnevinput.val());
				}
			});

			telefoninput
			.on('input', function(e) {
				mkwcheck.checkoutTelefonCheck();
				$(this).off('keydown');
			})
			.on('keydown blur', function(e) {
				mkwcheck.wasinteraction.telefon = true;
				mkwcheck.checkoutTelefonCheck();
			})
			.each(function(i, ez) {
				mkwcheck.checkoutTelefonCheck();
			});

			var $chklogin = $('.js-chklogin');
			if ($chklogin.length) {
				$('.js-chkszallitasiadatok').hide().addClass('js-chkclosed');
			}
			$('.js-chkszallmod, .js-chkattekintes').hide().addClass('js-chkclosed');
			$('.js-chkdatagroupheader').on('click', function(e) {
				e.preventDefault();
				var regkell = $('input[name="regkell"]:checked');
				if (!regkell.length && $chklogin.length) {
					mkw.showDialog(mkwmsg.ChkRegLogin);
				}
				else {
					var $this = $(this),
							mycontainer = $($this.data('container'));
					if (mycontainer.hasClass('js-chkclosed')) {
						$('.js-chkdatacontainer').slideUp(100).addClass('js-chkclosed');
						mycontainer.slideDown(100).removeClass('js-chkclosed');
					}
				}
			});

			$('.js-chkopenbtn').on('click', function(e) {
				e.preventDefault();
				var dg = $(this).data('datagroupheader'),
						datagroupheader = $(dg);
				datagroupheader.click();
			});

			szamlaeqszall.on('change', function(e) {
				var obj = $('.js-chkszamlaadatok');
				obj.toggleClass('notvisible');
				if (obj.hasClass('notvisible')) {
					$('input', obj).attr('disabled', 'disabled');
				}
				else {
					$('input', obj).attr('disabled', null);
				}
				refreshAttekintes();
			});

			mkw.irszamTypeahead('input[name="szamlairszam"]', 'input[name="szamlavaros"]');
			mkw.varosTypeahead('input[name="szamlairszam"]', 'input[name="szamlavaros"]');
			mkw.irszamTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');
			mkw.varosTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');

			$('.js-chkaszf, .js-chkhelp').magnificPopup({
				type: 'ajax',
                closeBtnInside: false
			});

			vezeteknevinput.on('invalid', function() {
				openDataContainer(this);
			});
			keresztnevinput.on('invalid', function() {
				openDataContainer(this);
			});
			telefoninput.on('invalid', function() {
				openDataContainer(this);
			});
			kapcsemailinput.on('invalid', function() {
				openDataContainer(this);
			});
			szamlanevinput.on('invalid', function() {
				openDataContainer(this);
			});
			szamlairszaminput.on('invalid', function() {
				openDataContainer(this);
			});
			szamlavarosinput.on('invalid', function() {
				openDataContainer(this);
			});
			szamlautcainput.on('invalid', function() {
				openDataContainer(this);
			});
			adoszaminput.on('invalid', function() {
				openDataContainer(this);
			});
			szallnevinput.on('invalid', function() {
				openDataContainer(this);
			});
			szallirszaminput.on('invalid', function() {
				openDataContainer(this);
			});
			szallvarosinput.on('invalid', function() {
				openDataContainer(this);
			});
			szallutcainput.on('invalid', function() {
				openDataContainer(this);
			});

			H5F.setup(checkoutform);

            $('.js-chksendorderbtn').on('click', function(e) {

                ajaxlog('START: 10 Send order clicked');

                var vals = {};
                $('input').each(function() {
                    vals[$(this).attr('name')] = $(this).val();
                });
                ajaxlog('DATA: 15 ' + JSON.stringify(vals));

                var messages = '';
                $('input:invalid').each(function() {
                    messages += $(this).attr('placeholder') + ': ' + $(this).prop('validationMessage') + '<br>';
                });
                if (messages) {
                    ajaxlog('ERROR: 20 Invalid inputok: ' + messages);
//                    mkw.showDialog(messages);
                }

				if (!$('input[name="aszfready"]').prop('checked')) {
                    ajaxlog('ERROR: 30 ÁSZF nincs pipálva');
					mkw.showDialog(mkwmsg.ChkASZF);
				}
				else {
					$.ajax({
						url: '/kosar/gethash',
						success: function(data) {
                            ajaxlog('OK: 40 Kosár gethash success');
							var d = JSON.parse(data);
                            ajaxlog('OK: 50 Kosár gethash: ' + data);
							if (kosarhash && kosarhash != d.value) {
                                ajaxlog('ERROR: 60 Kosár megváltozott');
								mkw.showDialog(mkwmsg.ChkKosarValtozott);
                                loadTetelList();
							}
							else {
								if (d.cnt <= 0) {
                                    ajaxlog('ERROR: 70 Kosár üres');
									mkw.showDialog(mkwmsg.ChkKosarUres);
								}
								else {
                                    ajaxlog('END:OK: 80 Submit');
									$('.js-checkoutsubmit').click();
								}
							}
						}
					});
				}
			});
		}
	}

	return {
		initUI: initUI
	};

})(jQuery, guid);
var cart = (function($) {

	function submitMennyEdit(f) {
		var menny = Math.round($('input[name="mennyiseg"]', f).val());
		if (menny > 0) {
			$.ajax({
				url: f.attr('action'),
				type: 'POST',
				data: {
					id: $('input[name="id"]', f).val(),
					mennyiseg: menny
				},
				beforeSend: function() {
					mkw.showMessage(mkwmsg.KosarMennyisegModositas);
				},
				success: function(data) {
					var d = JSON.parse(data);
					$('#minikosar').html(d.minikosar);
					$('table').html(d.tetellist);
					mkw.initTooltips();
				},
				complete: function() {
					mkw.closeMessage();
				}
			});
		}
		else {
			$('input[name="mennyiseg"]', f).val($('input[name="mennyiseg"]', f).data('org'));
			mkw.showDialog(mkwmsg.KosarMennyisegNulla);
		}
	}

	function initUI() {
		var $cart = $('.js-cart');

		if ($cart.length > 0) {
			$cart
			.on('blur', 'input[name="mennyiseg"]', function() {
				var $this = $(this);
				if ($this.val() * 1 !== $this.data('org') * 1) {
					submitMennyEdit($(this).parents('form.kosarform'));
				}
			})
			.on('submit', '.kosarform', function() {
				submitMennyEdit($(this));
				return false;
			});
		}
	}

	return {
		initUI: initUI
	};

})(jQuery);
var fiok = (function($) {

	function initUI() {
		var $fiokadataimform = $('#FiokAdataim');

		if ($fiokadataimform.length > 0) {

			H5F.setup($fiokadataimform);

			$('#VezeteknevEdit,#KeresztnevEdit')
			.on('input', function(e) {
				mkwcheck.regNevCheck();
				$(this).off('keydown');
			})
			.on('keydown blur', function(e) {
				mkwcheck.wasinteraction.doublenev = true;
				mkwcheck.regNevCheck();
			})
			.each(function(i, ez) {
				mkwcheck.regNevCheck();
			});

			$('#EmailEdit')
			.on('input', function(e) {
				mkwcheck.regEmailCheck();
				$(this).off('keydown');
			})
			.on('keydown blur', function(e) {
				mkwcheck.wasinteraction.email = true;
				mkwcheck.regEmailCheck();
			})
			.on('change', function(e) {
				var $this = $(this);
				$.ajax({
					type: 'POST',
					url: '/checkemail',
					data: {email: $this.val()}
				})
						.done(function(data) {
					var d = JSON.parse(data);
					$this.data('hiba', d);
					mkwcheck.regEmailCheck();
				});
			})
			.each(function(i, ez) {
				mkwcheck.regEmailCheck();
			});

			$('.js-copyszamlaadat').on('click', function() {
				$('input[name="szallnev"]').val($('input[name="nev"]').val());
				$('input[name="szallirszam"]').val($('input[name="irszam"]').val());
				$('input[name="szallvaros"]').val($('input[name="varos"]').val());
				$('input[name="szallutca"]').val($('input[name="utca"]').val());
				return false;
			});

			$('.js-tooltipbtn').tooltip({
				html: false,
				placement: 'right',
				container: 'body'
			});

			mkw.overrideFormSubmit($fiokadataimform, mkwmsg.FiokAdataitModositjuk);

			$('.js-accmegrendelesopen').on('click', function() {
				$(this).next('tr').toggleClass('notvisible');
				return false;
			});
		}

		var $fiokszamlaadatok = $('#FiokSzamlaAdatok');
		if ($fiokszamlaadatok.length > 0) {
			mkw.irszamTypeahead('input[name="irszam"]', 'input[name="varos"]');
			mkw.varosTypeahead('input[name="irszam"]', 'input[name="varos"]');
			mkw.overrideFormSubmit($fiokszamlaadatok, mkwmsg.FiokAdataitModositjuk);
		}

		var $fiokszallitasiadatok = $('#FiokSzallitasiAdatok');
		if ($fiokszallitasiadatok.length > 0) {
			mkw.irszamTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');
			mkw.varosTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');
			mkw.overrideFormSubmit($fiokszallitasiadatok, mkwmsg.FiokAdataitModositjuk);
		}
	}

	return {
		initUI: initUI
	};
})(jQuery);

$(document).ready(function() {

    var $termekertesitomodal = $('#termekertesitoModal'),
            $termekertesitoform = $('#termekertesitoform');

    if ($.fn.mattaccord) {
        $(document).mattaccord();
    }
    if ($.fn.tab) {
        $('#termekTabbable').tab('show');
        $('#adamodositasTabbable').tab('show');
    }
    if ($.fn.slider) {
        var $arslider = $('#ArSlider');
        if ($arslider.length > 0) {
            var maxar = $arslider.data('maxar') * 1,
                    ti = $arslider.attr('value'),
                    step = $arslider.data('step') * 1;
            $arslider.slider({
                from: 0,
                to: maxar + step,
                step: step,
                dimension: '&nbsp;Ft',
                skin: 'mkwcansas',
                callback: function(value) {
                    mkw.lapozas();
                }
            });
            $arslider.slider('value', ti.split(';')[0], ti.split(';')[1]);
        }
    }
    if ($.fn.typeahead) {
        $('#searchinput').typeahead({
            source: function(query, process) {
                return $.ajax({
                    url: '/kereses',
                    type: 'GET',
                    data: {
                        term: query
                    },
                    success: function(data) {
                        var d = JSON.parse(data);
                        return process(d);
                    }
                });
            },
            onselect: function() {
                $('#searchform').submit();
            },
            items: 999999,
            minLength: 4
        });
    }
    if ($.fn.royalSlider) {
        $('#korhinta').royalSlider({
            autoScaleSlider: true,
            loopRewind: true,
            keyboardNavEnabled: true,
            controlNavigation: 'bullets',
            imageScalePadding: 0,
            autoPlay: {
                enabled: true,
                delay: 4000
            }
        });
        $('#legnepszerubbtermekslider').royalSlider({
            loopRewind: true,
            keyboardNavEnabled: true,
            autoHeight: true,
            controlNavigation: 'bullets'
        });
        $('#ajanlotttermekslider').royalSlider({
            autoHeight: true,
            loopRewind: true,
            keyboardNavEnabled: true,
            controlNavigation: 'bullets'
        });
        $('.js-termekimageslider').royalSlider({
            loopRewind: true,
            keyboardNavEnabled: true,
            controlNavigation: 'bullets',
            autoHeight: true
        });
        $('#hozzavasarolttermekslider').royalSlider({
            autoHeight: true,
            loopRewind: true,
            keyboardNavEnabled: true,
            controlNavigation: 'bullets'
        });
    }
    $termekertesitomodal.modal({
        show: false
    });
    $('.js-termekertesitobtn').on('click', function() {
        $termekertesitoform.find('input[name="termekid"]').val($(this).data('termek'));
        $termekertesitomodal.modal('show');
        return false;
    });
    mkw.overrideFormSubmit($termekertesitoform, false, {
        beforeSend: function(xhr, settings, data) {
            if (!data['email']) {
                alert('Adjon meg email címet.');
                return false;
            }
            return true;
        },
        success: function() {
            mkw.showMessage(mkwmsg.TermekErtesitoKoszonjuk);
            window.setTimeout(function() {
                mkw.closeMessage();
            }, 2500);
        },
        complete: function() {
            $termekertesitomodal.modal('hide');
            $termekertesitoform.find('input[name="termekid"]').val('');
        }
    });
    $('.js-termekertesitomodalok').on('click', function(e) {
        e.preventDefault();
        $termekertesitoform.submit();
    });
    $('.js-termekertesitodel').on('click', function(e) {
        var $this = $(this);
        e.preventDefault();
        $.ajax({
            url: '/termekertesito/save',
            type: 'POST',
            data: {
                oper: 'del',
                id: $this.data('id')
            },
            beforeSend: function() {
                mkw.showMessage(mkwmsg.TermekErtesitoLeiratkozas);
            },
            success: function() {
                $this.parents('div.js-termekertesito').remove();
            },
            complete: function() {
                mkw.closeMessage();
            }
        });
    });
    if ($.fn.magnificPopup) {
        $('.js-lightbox').magnificPopup({
            gallery: {
                enabled: true
            },
            image: {
                cursor: null
            },
            type: 'image'
        });
    }
    // nincs valtozat
    $('.js-kosarba').on('click', function(e) {
        var $this = $(this);
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $this.attr('href'),
            data: {
                jax: 1
            },
            beforeSend: function(x) {
                mkw.showMessage(mkwmsg.TermekKosarba);
            }
        })
                .done(function(data) {
                    $('#minikosar').html(data);
                })
                .always(function() {
                    mkw.closeMessage();
                });
    });
    // lathato valtozat van
    $('.js-kosarbavaltozat').on('click', function(e) {
        var $this = $(this),
                id = $this.attr('data-id');

        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $this.attr('href'),
            data: {
                jax: 2,
                vid: $this.attr('data-vid')
            },
            beforeSend: function() {
                mkw.showMessage(mkwmsg.TermekKosarba);
            }
        })
                .done(function(data) {
                    $('.js-valtozatedit[data-id="' + id + '"]').selectedIndex = 0;
                    $('#minikosar').html(data);
                })
                .always(function() {
                    mkw.closeMessage();
                });
    });
    // valaszthato valtozat van
    $('.js-kosarbamindenvaltozat').on('click', function(e) {
        var $this = $(this),
                termekid = $this.attr('data-termek'),
                tipusok = new Array(), ertekek = new Array(),
                valtozatselect = $('.js-mindenvaltozatedit[data-termek="' + termekid + '"]');

        e.preventDefault();

        valtozatselect.each(function() {
            var $this = $(this);
            if ($this.val()) {
                tipusok.push($this.data('tipusid'));
                ertekek.push($this.val());
            }
        });

        if (valtozatselect.length !== ertekek.length) {
            mkw.showDialog(mkwmsg.TermekValtozatotValassz);
        }
        else {
            $.ajax({
                type: 'POST',
                url: $this.attr('href'),
                data: {
                    jax: 3,
                    tip: tipusok,
                    val: ertekek
                },
                beforeSend: function(x) {
                    mkw.showMessage(mkwmsg.TermekKosarba);
                }
            })
                    .done(function(data) {
                        $('.js-mindenvaltozatedit[data-termek="' + termekid + '"]').selectedIndex = 0;
                        $('#minikosar').html(data);
                    })
                    .always(function() {
                        mkw.closeMessage();
                    });
        }
    });

    // valtozat
    $('.js-valtozatedit').on('change', function() {
        var $this = $(this),
                termek = $this.data('termek'),
                id = $this.data('id');

        $.ajax({
            url: '/valtozatar',
            data: {
                t: termek,
                vid: $this.val()
            }
        })
                .done(function(data) {
                    var d = JSON.parse(data);
                    $('#termekprice' + id).text(d['price']);
                })
                .always(function() {
                    $('.js-kosarbavaltozat[data-id="' + id + '"]').attr('data-vid', $this.val());
                });
    });
    $('.js-mindenvaltozatedit').on('change', function() {
        var $valtedit = $(this),
                tipusid = $valtedit.data('tipusid'),
                termek = $valtedit.data('termek'),
                id = $valtedit.data('id'),
                $masikedit = $('.js-mindenvaltozatedit[data-termek="' + termek + '"][data-tipusid!="' + tipusid + '"]');

        $.ajax({
            url: '/valtozat',
            data: {
                t: termek,
                ti: tipusid,
                v: $valtedit.val(),
                sel: $masikedit.val(),
                mti: $masikedit.data('tipusid')
            }
        })
                .done(function(data) {
                    var d = JSON.parse(data),
                            adat = d['adat'];
                    sel = '';

                    $('#termekprice' + id).text(d['price']);
                    $('option[value!=""]', $masikedit).remove();
                    $.each(adat, function(i, v) {
                        if (v['sel']) {
                            sel = ' selected="selected"';
                        }
                        else {
                            sel = '';
                        }
                        $masikedit.append('<option value="' + v['value'] + '"' + sel + '>' + v['value'] + '</option>');
                    });
                });
    });
    var $regform = $('#Regform');
    if ($regform.length > 0) {
        H5F.setup($regform);
        $('#VezeteknevEdit,#KeresztnevEdit')
                .on('input', function(e) {
                    mkwcheck.regNevCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.doublenev = true;
                    mkwcheck.regNevCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.regNevCheck();
                });
        $('#EmailEdit')
                .on('input', function(e) {
                    mkwcheck.regEmailCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.email = true;
                    mkwcheck.regEmailCheck();
                })
                .on('change', function(e) {
                    var $this = $(this);
                    $.ajax({
                        type: 'POST',
                        url: '/checkemail',
                        data: {email: $this.val()}
                    })
                            .done(function(data) {
                                var d = JSON.parse(data);
                                $this.data('hiba', d);
                                mkwcheck.regEmailCheck();
                            });
                })
                .each(function(i, ez) {
                    mkwcheck.regEmailCheck();
                });
        $('#Jelszo1Edit,#Jelszo2Edit')
                .on('input', function(e) {
                    mkwcheck.regJelszoCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.pw = true;
                    mkwcheck.regJelszoCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.regJelszoCheck();
                });
    }
    var $passreminderform = $('#passreminderform');
    if ($passreminderform.length >0) {
        $('#Jelszo1Edit,#Jelszo2Edit')
                .on('input', function(e) {
                    mkwcheck.regJelszoCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.pw = true;
                    mkwcheck.regJelszoCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.regJelszoCheck();
                });
    }
    var $kapcsolatform = $('#Kapcsolatform');
    if ($kapcsolatform.length > 0) {
        H5F.setup($kapcsolatform);
        $('#NevEdit')
                .on('input', function(e) {
                    mkwcheck.kapcsolatNevCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.nev = true;
                    mkwcheck.kapcsolatNevCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.kapcsolatNevCheck();
                });
        $('#Email1Edit,#Email2Edit')
                .on('input', function(e) {
                    mkwcheck.kapcsolatEmailCheck();
                    $(this).off('keydown');
                })
                .on('change', function(e) {
                    var $this = $(this);
                    $.ajax({
                        type: 'POST',
                        url: '/checkemail',
                        data: {
                            email: $this.val(),
                            dce: 1
                        }
                    })
                            .done(function(data) {
                                var d = JSON.parse(data);
                                $this.data('hiba', d);
                                mkwcheck.kapcsolatEmailCheck();
                            });
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.email = true;
                    mkwcheck.kapcsolatEmailCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.kapcsolatEmailCheck();
                });
        $('#TemaEdit')
                .on('input', function(e) {
                    mkwcheck.kapcsolatTemaCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.tema = true;
                    mkwcheck.kapcsolatTemaCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.kapcsolatTemaCheck();
                });
    }
    var $loginform = $('#Loginform');
    if ($loginform.length > 0) {
        H5F.setup($loginform);
        $('#EmailEdit')
                .on('input', function(e) {
                    mkwcheck.loginEmailCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.email = true;
                    mkwcheck.loginEmailCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.loginEmailCheck();
                });
        $('.js-passreminder').on('click', function() {
            var email = $('input[name="email"]', $loginform).val();
            if (!email) {
                mkw.showDialog(mkwmsg.PassReminderRequiredEmail);
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '/getpassreminder',
                data: {
                    email: email
                },
                success: function() {
                    mkw.showDialog(mkwmsg.PassReminderCreated);
                }
            })
            return false;
        })
    }
    var $passwordchangeform = $('#JelszoChangeForm');
    if ($passwordchangeform.length > 0) {
        mkw.overrideFormSubmit($passwordchangeform, false, {
           beforeSend: function(xhr, settings, data) {
               if (!data['jelszo1'] || !data['jelszo2']) {
                   mkw.showDialog(mkwmsg.PassChange[3]);
                   return false;
               }
               if (data['jelszo1'] !== data['jelszo2']) {
                   mkw.showDialog(mkwmsg.PassChange[1]);
                   return false;
               }
               return true;
           },
           success: function(data) {
               var d = data * 1;
               if (!d) {
                   mkw.showMessage(mkwmsg.PassChange[d]);
                   window.setTimeout(function() {
                       mkw.closeMessage();
                   }, 2500);
               }
               else {
                   mkw.showDialog(mkwmsg.PassChange[d]);
               }
           }
        });
    }
    // kategoria navigalas
    var a = $('#navmain li a'),
            b = $('#navmain li .sub');
    $('#navmain li').on('click', function(e) {
        var $this = $(this),
                gy = $this.children('a');
        v = gy.hasClass('active');
        e.preventDefault();
        if (gy.attr('data-cnt') > 0) {
            a.removeClass('active');
            b.hide();
            if (!v) {
                gy.addClass('active');
                $this.children('.sub').toggle();
            }
        }
        else {
            if (gy.length > 0) {
                document.location = gy.attr('href');
            }
        }
    });
    b.mouseup(function() {
        return false;
    });
    $(document).on('mouseup', function(c) {
        if ($(c.target).parent("#navmain li").length == 0) {
            a.removeClass("active");
            b.hide();
        }
    });
    $('div.kat').on('click', function(e) {
        e.preventDefault();
        document.location = $(this).attr('data-href');
    });
    // lapozo es szuroform
    $('.elemperpageedit').on('change', function() {
        $('.elemperpageedit').val($(this).val());
        mkw.lapozas();
    });
    $('.orderedit').on('change', function() {
        $('.orderedit').val($(this).val());
        mkw.lapozas();
    });
    $('.pageedit').on('click', function() {
        $('.lapozoform').attr('data-pageno', $(this).attr('data-pageno'));
        mkw.lapozas();
    });
    $('.termeklistview').on('click', function() {
        $('#ListviewEdit').val($(this).attr('data-vt'));
        mkw.lapozas();
    });
    $('#szuroform input').on('change', function() {
        $('.lapozoform input[name="cimkekatid"]').val($(this).attr('name').split('_')[1]);
        mkw.lapozas();
    });
    $('.js-filterclear').on('click', function(e) {
        e.preventDefault();
        $('#szuroform input[type="checkbox"]').prop('checked',false);
        $('#ArSlider').val('0;0');
        mkw.lapozas(1);
    });

    mkw.initTooltips();
    mkw.showhideFilterClear();

    cart.initUI();
    checkout.initUI();
    fiok.initUI();

});