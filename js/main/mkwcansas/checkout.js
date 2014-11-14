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
            url: '/ajaxlogger.php',
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
        $('.js-chkszallnev').text(szallnevinput.val());
        $('.js-chkszallirszam').text(szallirszaminput.val());
        $('.js-chkszallvaros').text(szallvarosinput.val());
        $('.js-chkszallutca').text(szallutcainput.val());

		$('.js-chkadoszam').text(adoszaminput.val());
		if (szamlaeqszall.prop('checked')) {
			$('.js-chkszamlanev').text(szallnevinput.val());
			$('.js-chkszamlairszam').text(szallirszaminput.val());
			$('.js-chkszamlavaros').text(szallvarosinput.val());
			$('.js-chkszamlautca').text(szallutcainput.val());
		}
		else {
            $('.js-chkszamlanev').text(szamlanevinput.val());
            $('.js-chkszamlairszam').text(szamlairszaminput.val());
            $('.js-chkszamlavaros').text(szamlavarosinput.val());
            $('.js-chkszamlautca').text(szamlautcainput.val());
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

    function checkEmail(email) {
        var re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
        return re.test(email);
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
			.on('blur', 'input[name="vezeteknev"],input[name="keresztnev"]', function() {
				if (!szallnevinput.val() && vezeteknevinput.val() && keresztnevinput.val()) {
					szallnevinput.val(vezeteknevinput.val() + ' ' + keresztnevinput.val());
				}
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

            checkoutform.on('submit', function(e) {
                var hibas = false, tofocus = false;

                ajaxlog('START: 10 Send order clicked');

                if (!vezeteknevinput.val()) {
                    vezeteknevinput.addClass('hibas');
                    if (!hibas) {
                        openDataContainer(vezeteknevinput);
                        tofocus = vezeteknevinput;
                    }
                    hibas = true;
                }
                else {
                    vezeteknevinput.removeClass('hibas');
                }

                if (!keresztnevinput.val()) {
                    keresztnevinput.addClass('hibas');
                    if (!hibas) {
                        openDataContainer(keresztnevinput);
                        tofocus = keresztnevinput;
                    }
                    hibas = true;
                }
                else {
                    keresztnevinput.removeClass('hibas');
                }

                if (!telefoninput.val()) {
                    telefoninput.addClass('hibas');
                    if (!hibas) {
                        openDataContainer(telefoninput);
                        tofocus = telefoninput;
                    }
                    hibas = true;
                }
                else {
                    telefoninput.removeClass('hibas');
                }

                if (!kapcsemailinput.val() || !checkEmail(kapcsemailinput.val())) {
                    kapcsemailinput.addClass('hibas');
                    if (!hibas) {
                        openDataContainer(kapcsemailinput);
                        tofocus = kapcsemailinput;
                    }
                    hibas = true;
                }
                else {
                    kapcsemailinput.removeClass('hibas');
                }

                var jelszo1input = $('input[name="jelszo1"]'),
                    jelszo2input = $('input[name="jelszo2"]');
                if (jelszo1input.length && jelszo2input.length) {
                    if ((!jelszo1input.val() || !jelszo2input.val()) || (jelszo1input.val() != jelszo2input.val())) {
                        jelszo1input.addClass('hibas');
                        jelszo2input.addClass('hibas');
                        if (!hibas) {
                            openDataContainer(jelszo1input);
                            tofocus = jelszo1input;
                        }
                        hibas = true;
                    }
                    else {
                        jelszo1input.removeClass('hibas');
                        jelszo2input.removeClass('hibas');
                    }
                }

                if (!szallirszaminput.val()) {
                    szallirszaminput.addClass('hibas');
                    if (!hibas) {
                        openDataContainer(szallirszaminput);
                        tofocus = szallirszaminput;
                    }
                    hibas = true;
                }
                else {
                    szallirszaminput.removeClass('hibas');
                }

                if (!szallvarosinput.val()) {
                    szallvarosinput.addClass('hibas');
                    if (!hibas) {
                        openDataContainer(szallvarosinput);
                        tofocus = szallvarosinput;
                    }
                    hibas = true;
                }
                else {
                    szallvarosinput.removeClass('hibas');
                }

                if (!szallutcainput.val()) {
                    szallutcainput.addClass('hibas');
                    if (!hibas) {
                        openDataContainer(szallutcainput);
                        tofocus = szallutcainput;
                    }
                    hibas = true;
                }
                else {
                    szallutcainput.removeClass('hibas');
                }

                if (!szamlaeqszall.prop('checked')) {
                    if (!szamlairszaminput.val()) {
                        szamlairszaminput.addClass('hibas');
                        if (!hibas) {
                            openDataContainer(szamlairszaminput);
                            tofocus = szamlairszaminput;
                        }
                        hibas = true;
                    }
                    else {
                        szamlairszaminput.removeClass('hibas');
                    }

                    if (!szamlavarosinput.val()) {
                        szamlavarosinput.addClass('hibas');
                        if (!hibas) {
                            openDataContainer(szamlavarosinput);
                            tofocus = szamlavarosinput;
                        }
                        hibas = true;
                    }
                    else {
                        szamlavarosinput.removeClass('hibas');
                    }

                    if (!szamlautcainput.val()) {
                        szamlautcainput.addClass('hibas');
                        if (!hibas) {
                            openDataContainer(szamlautcainput);
                            tofocus = szamlautcainput;
                        }
                        hibas = true;
                    }
                    else {
                        szamlautcainput.removeClass('hibas');
                    }
                }

                if (hibas) {
                    $('#dialogcenter').on('hidden', function() {
                        $('#dialogcenter').off('hidden');
                        if (tofocus) {
                            tofocus.focus();
                        }
                    })
                    mkw.showDialog('Kérjük, adja meg a hiányzó adatokat. Ezeket pirossal megjelöltük.');
                    e.preventDefault();
                    return false;
                }
                else {
                    if (!$('input[name="aszfready"]').prop('checked')) {
                        ajaxlog('ERROR: 30 ÁSZF nincs pipálva');
                        e.preventDefault();
                        mkw.showDialog(mkwmsg.ChkASZF);
                        return false;
                    }
                    else {
                        e.preventDefault();
                        alert('eddig ok');
                        return false;
                    }
                }
            });
		}
	}

    function szirszar() {
                var messages = '';
                $('#CheckoutForm input:invalid').each(function() {
                    messages += $(this).attr('name') + ': ' + $(this).prop('validationMessage') + '<br>';
                });
                if (messages) {
                    ajaxlog('ERROR: 20 Invalid inputok: ' + messages);
                }

                if ($('input[name="jelszo1"]').val() !== $('input[name="jelszo2"]').val()) {
                    var jel1 = $('input[name="jelszo1"]');
                    e.preventDefault();
                    ajaxlog('ERROR: 30 A két jelszó nem egyezik.');
                    openDataContainer(jel1);
                    mkw.showDialog(mkwmsg.PassChange[1]).on('hidden',function() {
                        jel1[0].focus();
                    });
                }
                else {
                    if (!$('input[name="aszfready"]').prop('checked')) {
                        ajaxlog('ERROR: 30 ÁSZF nincs pipálva');
                        e.preventDefault();
                        mkw.showDialog(mkwmsg.ChkASZF);
                    }
                    else {
                        ajaxlog('AJAX: 32 ajax kérés indul');
                        $.ajax({
                            url: '/kosar/gethash',
                            success: function(data) {
                                ajaxlog('OK: 40 Kosár gethash success');
                                var d = JSON.parse(data);
                                ajaxlog('OK: 50 Kosár gethash: ' + data);
                                if (kosarhash && kosarhash != d.value) {
                                    e.preventDefault();
                                    ajaxlog('ERROR: 60 Kosár megváltozott');
                                    mkw.showDialog(mkwmsg.ChkKosarValtozott);
                                    loadTetelList();
                                }
                                else {
                                    if (d.cnt <= 0) {
                                        e.preventDefault();
                                        ajaxlog('ERROR: 70 Kosár üres');
                                        mkw.showDialog(mkwmsg.ChkKosarUres);
                                    }
                                    else {
                                        ajaxlog('END:OK: 80 Submit');
                                    }
                                }
                            },
                            error: function(xhr, stat, error) {
                                e.preventDefault();
                                ajaxlog('AJAX: 90 ERROR. STATUS: ' + stat + ' ERROR TEXT: ' + error);
                            },
                            complete: function(xhr, stat) {
                                e.preventDefault();
                                ajaxlog('AJAX: 100 COMPLETE. STATUS: ' + stat);
                            }
                        });
                    }
                }

    }

	return {
		initUI: initUI
	};

})(jQuery, guid);