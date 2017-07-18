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
			szallnevinput, szallirszaminput, szallvarosinput, szallutcainput, orszagselect,
			checkoutform,
			webshopmessageinput, couriermessageinput,
			szamlaeqszall,
			kosarhash,
            egyediid = guid();

    function getSessid() {
        var x = document.cookie.match(/PHPSESSID=[^;]+/);
        if (!x) {
            return egyediid;
        }
        if (typeof(x) == 'object') {
            x = x[0];
        }
        if (typeof(x) == 'string') {
            return x.substring(10);
        }
        return egyediid;
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

    function loadFoxpostCsoportData(termis) {
        if ($('input[name="szallitasimod"]:checked').hasClass('js-foxpostchk')) {
            $.ajax({
                url: '/checkout/getfoxpostcsoportlist',
                success: function(data) {
                    var d = JSON.parse(data);
                    $('.js-foxpostterminalcontainer').html(d.html).show();
                    if (termis) {
                        loadFoxpostTerminalData();
                    }
                    else {
                        refreshAttekintes();
                    }
                }
            })
        }
        else {
            $('.js-foxpostterminalcontainer').empty().hide();
            refreshAttekintes();
        }
    }

    function loadFoxpostTerminalData() {
        var cs = $('select[name="foxpostcsoport"]').val();
        $.ajax({
            url: '/checkout/getfoxpostterminallist',
            data: {
                cs: cs
            },
            success: function(data) {
                var d = JSON.parse(data);
                $('select[name="foxpostterminal"]').remove();
                $('.js-foxpostterminalcontainer').append(d.html);
                refreshAttekintes();
            }
        })
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
				szallitasimod: $('input[name="szallitasimod"]:checked').val(),
                kupon: $('input[name="kupon"]').val()
			},
            success: function(data) {
                var d = JSON.parse(data);
                $('.js-chktetellist').html(d.html);
                kosarhash = d.hash.value;
                if (d.kuponszoveg) {
                    $('.js-kuponszoveg').text(d.kuponszoveg);
                }
            }
        });
    }

	function refreshAttekintes() {
		$('.js-chkvezeteknev').text(vezeteknevinput.val());
		$('.js-chkkeresztnev').text(keresztnevinput.val());
		$('.js-chktelefon').text(telefoninput.val());
		$('.js-chkkapcsemail').text(kapcsemailinput.val());
		$('.js-chkorszag').text(orszagselect.text());
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
        $('.js-chkfoxpostterminal').text($('select[name="foxpostterminal"] option:selected').text());
		$('.js-chkfizmod').text($('input[name="fizetesimod"]:checked').data('caption'));
		$('.js-chkwebshopmessage').text(webshopmessageinput.val());
		$('.js-chkcouriermessage').text(couriermessageinput.val());
	}

	function openDataContainer(obj) {
/*		var $this = $(obj),
				mycontainer = $($this.data('container'));
		if (mycontainer.hasClass('js-chkclosed')) {
			$('.js-chkdatacontainer').slideUp(0).addClass('js-chkclosed');
			mycontainer.slideDown(0).removeClass('js-chkclosed');
		}
*/
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
			orszagselect = $('select[name="orszag"] option:selected');
			szallnevinput = $('input[name="szallnev"]');
			szallirszaminput = $('input[name="szallirszam"]');
			szallvarosinput = $('input[name="szallvaros"]');
			szallutcainput = $('input[name="szallutca"]');
			szamlaeqszall = $('input[name="szamlaeqszall"]');
			webshopmessageinput = $('textarea[name="webshopmessage"]');
			couriermessageinput = $('textarea[name="couriermessage"]');

			loadFizmodList();
            loadFoxpostCsoportData(true);

			$checkout
            .on('change', 'select[name="foxpostcsoport"]', function() {
                loadFoxpostTerminalData();
            })
			.on('change', 'input[name="szallitasimod"]', function() {
				loadFizmodList();
                loadFoxpostCsoportData(true);
			})
            .on('blur', 'input[name="kupon"]', function() {
                loadTetelList();
            })
			.on('change', 'input[name="regkell"]', function() {
				checkoutpasswordcontainer.empty();
				if ($('input[name="regkell"]:checked').val() * 1 === 2) {
					checkoutpasswordrow.appendTo(checkoutpasswordcontainer);
					$('.js-chktooltipbtn').tooltip({
						html: false,
						placement: 'right',
						container: 'body'
					});
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

            $('input[name="regkell"]').change();

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
                var hibas = false, tofocus = false, hibauzenet;

                hibauzenet = mkwmsg.ChkHiba;

                $('.chk-sendorderbtn').removeClass('cartbtn').addClass('okbtn').val('Feldolgozás alatt');

                if (!$('input[name="szallitasimod"]:checked').val()) {
                    tofocus = $('input[name="szallitasimod"]');
                    hibas = true;
                    hibauzenet = mkwmsg.ChkSzallmodHiba;
                }
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

                if (!szallnevinput.val()) {
                    szallnevinput.addClass('hibas');
                    if (!hibas) {
                        openDataContainer(szallnevinput);
                        tofocus = szallnevinput;
                    }
                    hibas = true;
                }
                else {
                    szallnevinput.removeClass('hibas');
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
                    if (!szamlanevinput.val()) {
                        szamlanevinput.addClass('hibas');
                        if (!hibas) {
                            openDataContainer(szamlanevinput);
                            tofocus = szamlanevinput;
                        }
                        hibas = true;
                    }
                    else {
                        szamlanevinput.removeClass('hibas');
                    }

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
                    $('.chk-sendorderbtn').removeClass('okbtn').addClass('cartbtn').val('Megrendelés elküldése');
                    $('#dialogcenter').on('hidden', function() {
                        $('#dialogcenter').off('hidden');
                        if (tofocus) {
                            tofocus.focus();
                        }
                    });
                    mkw.showDialog(hibauzenet);
                    e.preventDefault();
                    return false;
                }
                else {
                    if (!$('input[name="aszfready"]').prop('checked')) {
                        $('.chk-sendorderbtn').removeClass('okbtn').addClass('cartbtn').val('Megrendelés elküldése');
                        e.preventDefault();
                        mkw.showDialog(mkwmsg.ChkASZF);
                        return false;
                    }
                    else {
                        mkw.showMessage(mkwmsg.ChkSave);
                        return true;
                    }
                }
            });
		}
	}

	return {
		initUI: initUI
	};

})(jQuery, guid);