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
			vezeteknevinput, keresztnevinput, telkorzetinput, telszaminput, kapcsemailinput,
			szamlanevinput, szamlairszaminput, szamlavarosinput, szamlautcainput, adoszaminput,
			szallnevinput, szallirszaminput, szallvarosinput, szallutcainput,
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
                loadTetelList();
				refreshAttekintes();
			}
		});
	}

    function loadCsomagterminalData(termis) {
        $('.js-foxpostterminalcontainer').empty().hide();
        $('.js-glsterminalcontainer').empty().hide();
        $('.js-tofmapcontainer').empty().hide();
        $('.js-tofnev').val('');
        $('.js-tofid').val('');
        var $szallmodchk = $('input[name="szallitasimod"]:checked');
        if ($szallmodchk.hasClass('js-foxpostchk')) {
            $.ajax({
                url: '/checkout/getfoxpostcsoportlist',
                data: {
                    szmid: $szallmodchk.val()
                },
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
        else
        if ($szallmodchk.hasClass('js-glschk')) {
            $.ajax({
                url: '/checkout/getglscsoportlist',
                data: {
                    szmid: $szallmodchk.val()
                },
                success: function(data) {
                    var d = JSON.parse(data);
                    $('.js-glsterminalcontainer').html(d.html).show();
                    if (termis) {
                        loadGLSTerminalData();
                    }
                    else {
                        refreshAttekintes();
                    }
                }
            })
        }
        else
        if ($szallmodchk.hasClass('js-tofchk')) {
            $('.js-tofmapcontainer').html('<iframe width="100%" height="471px" src="http://tofweb.hu/tofshops/ts_api_v2.php"></iframe>').show();
            refreshAttekintes();
        }
        else {
            refreshAttekintes();
        }
    }

    function loadFoxpostTerminalData() {
        var cs = $('select[name="foxpostcsoport"]').val(),
            $szallmodchk = $('input[name="szallitasimod"]:checked');
        $.ajax({
            url: '/checkout/getfoxpostterminallist',
            data: {
                cs: cs,
                szmid: $szallmodchk.val()
            },
            success: function(data) {
                var d = JSON.parse(data);
                $('select[name="foxpostterminal"]').remove();
                $('.js-foxpostterminalcontainer').append(d.html);
                refreshAttekintes();
            }
        })
    }

    function loadGLSTerminalData() {
        var cs = $('select[name="glscsoport"]').val(),
            $szallmodchk = $('input[name="szallitasimod"]:checked');
        $.ajax({
            url: '/checkout/getglsterminallist',
            data: {
                cs: cs,
                szmid: $szallmodchk.val()
            },
            success: function(data) {
                var d = JSON.parse(data);
                $('select[name="glsterminal"]').remove();
                $('.js-glsterminalcontainer').append(d.html);
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
                fizmod: $('input[name="fizetesimod"]:checked').val(),
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

    function updateSubmitButton() {
        var fizmodi = $('input[name="fizetesimod"]:checked');
        if (fizmodi && fizmodi.data('biztonsagikerdeskell')) {
            $('.js-chksendorderbtn').attr('type', 'button');
            $('.js-chksendorderbtn').click(function(e) {
                e.preventDefault();
                if (checkSubmitData(e)) {
                    mkw.showDialog('Önt most átirányítjuk a Barionhoz az online kártyás fizetéshez.', {
                        buttons: [
                            {
                                caption: 'Fizetek a bankkártyámmal',
                                _class: 'cartbtn margin-right-5',
                                click: function (e) {
                                    e.preventDefault();
                                    mkw.closeDialog();
                                    checkoutform.submit();
                                }
                            },
                            {
                                caption: 'Másik fizetési módot választok',
                                _class: 'okbtn margin-right-5',
                                click: function (e) {
                                    e.preventDefault();
                                    mkw.closeDialog();
                                }
                            }
                        ],
                        events: [
                            {
                                name: 'hide.bs.modal',
                                fn: function(e) {
                                    $('.chk-sendorderbtn').removeClass('okbtn').addClass('cartbtn').val('Megrendelés elküldése');
                                }
                            }
                        ]
                    });
                }
            });
        }
        else {
            $('.js-chksendorderbtn').attr('type', 'submit');
            $('.js-chksendorderbtn').unbind('click');
        }
    }

	function refreshAttekintes() {
        var $szallmodchk = $('input[name="szallitasimod"]:checked');
		$('.js-chkvezeteknev').text(vezeteknevinput.val());
		$('.js-chkkeresztnev').text(keresztnevinput.val());
		$('.js-chktelefon').text('+36 ' + telkorzetinput.val() + ' ' + telszaminput.val());
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
        $('.js-chkcsomagterminal').text('');
        if ($szallmodchk.hasClass('js-foxpostchk')) {
            $('.js-chkcsomagterminal').text($('select[name="foxpostterminal"] option:selected').text());
        }
        else {
            if ($szallmodchk.hasClass('js-glschk')) {
                $('.js-chkcsomagterminal').text($('select[name="glsterminal"] option:selected').text());
            }
            else {
                if ($szallmodchk.hasClass('js-tofchk')) {
                    $('.js-chkcsomagterminal').text($('.js-tofnev').val());
                }
            }
        }
		$('.js-chkfizetesimod').text($('input[name="fizetesimod"]:checked').data('caption'));
		$('.js-chkwebshopmessage').text(webshopmessageinput.val());
		$('.js-chkcouriermessage').text(couriermessageinput.val());

		updateSubmitButton();
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

    function checkSubmitData(e) {
        var hibas = false, tofocus = false, hibauzenet;

        $('.chk-sendorderbtn').removeClass('cartbtn').addClass('okbtn').val('Feldolgozás alatt');
        hibauzenet = mkwmsg.ChkHiba;

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

        var telkorzetsel = $('option:checked', telkorzetinput);

        telkorzetinput.removeClass('hibas');
        if (!telkorzetsel.val()) {
            telkorzetinput.addClass('hibas');
            if (!hibas) {
                tofocus = telkorzetinput;
            }
            hibas = true;
        }

        telszaminput.removeClass('hibas');
        if (telkorzetsel.val()) {
            if (telszaminput[0].value.length !== telkorzetsel.data('hossz')) {
                telszaminput.addClass('hibas');
                if (!hibas) {
                    tofocus = telszaminput;
                }
                hibas = true;
            }
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
            return true;
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
            telkorzetinput = $('select[name="telkorzet"]');
            telszaminput = $('input[name="telszam"]');
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

            mkw.onlyNumberInput('#TelszamEdit');

			loadFizmodList();
            loadCsomagterminalData(true);

			$checkout
            .on('change', 'select[name="foxpostcsoport"]', function() {
                loadFoxpostTerminalData();
            })
            .on('change', 'select[name="glscsoport"]', function() {
                loadGLSTerminalData();
            })
			.on('change', 'input[name="szallitasimod"]', function(e) {
				loadFizmodList();
                loadCsomagterminalData(true);
			})
            .on('change', 'input[name="fizetesimod"]', function(e) {
                loadTetelList();
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

            window.addEventListener('message', function(e) {
                // TOF csomagpont választó
                if (e.origin === 'http://tofweb.hu') {
                    $('.js-tofnev').val(e.data.name + ' - ' + e.data.county + ', ' + e.data.zip_code + ' ' + e.data.city + ', ' + e.data.street);
                    $.ajax({
                        url: '/checkout/getcsomagterminalid',
                        data: {
                            tipus: 'tofshop',
                            id: e.data.tof_shop_id,
                            nev: e.data.name,
                            cim: e.data.county + ', ' + e.data.zip_code + ' ' + e.data.city + ', ' + e.data.street,
                            csoport: e.data.city,
                            nyitva: e.data.opening,
                            findme: e.data.phone1,
                            geolat: e.data.gis_y,
                            geolng: e.data.gis_x
                        },
                        success: function(data) {
                            var d = JSON.parse(data);
                            $('.js-tofid').val(d.id);
                            refreshAttekintes();
                        }
                    });
                }
            });

            checkoutform.on('submit', function(e) {
                var x = checkSubmitData(e);
                if (x) {
                    mkw.showMessage(mkwmsg.ChkSave);
                }
                return x;
            });
		}
	}

	return {
		initUI: initUI
	};

})(jQuery, guid);