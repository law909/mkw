var checkout = function($) {

	var checkoutpasswordrow,
			checkoutpasswordcontainer,
			vezeteknevinput, keresztnevinput, telefoninput, kapcsemailinput,
			szamlanevinput, szamlairszaminput, szamlavarosinput, szamlautcainput, adoszaminput,
			szallnevinput, szallirszaminput, szallvarosinput, szallutcainput,
			checkoutform,
			webshopmessageinput, couriermessageinput,
			szamlaeqszall,
			kosarhash;

	function loadFizmodList() {
		$.ajax({
			url: '/checkout/getfizmodlist',
			data: {
				szallitasimod: $('input[name="szallitasimod"]:checked').val()
			},
			success: function(data) {
				$('.js-chkfizmodlist').html(data);
				refreshAttekintes();
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

			$.ajax({
				url: '/kosar/gethash',
				success: function(data) {
					var d = JSON.parse(data);
					kosarhash = d.hash;
				}
			});

			$('.js-chktooltipbtn').tooltip({
				html: false,
				placement: 'right',
				container: 'body'
			});

			checkoutform = $('#CheckoutForm');
			checkoutpasswordcontainer = $('.js-checkoutpasswordcontainer');
			checkoutpasswordrow = $('.js-checkoutpasswordrow').detach();

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
					mkw.showDialog('Válassza ki, hogy szeretne-e regisztrálni a vásárláshoz, vagy jelentkezzen be!');
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

			$('.js-chkaszf').magnificPopup({
				type: 'ajax'
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
				if (!$('input[name="aszfready"]').prop('checked')) {
					mkw.showDialog('Megrendelés előtt kérjük fogadja el az ÁSZF-et.');
				}
				else {
					$.ajax({
						url: '/kosar/gethash',
						success: function(data) {
							var d = JSON.parse(data);
							if (kosarhash && kosarhash != d.hash) {
								var newhash = d.hash;
								mkw.showDialog('A kosár tartalma megrendelés közben megváltozott, kérem ellenőrizze.');
								$.ajax({
									url: '/checkout/gettetellist',
									success: function(data) {
										$('.js-chktetellist').html(data);
										kosarhash = newhash;
									}
								});
							}
							else {
								if (d.cnt <= 0) {
									mkw.showDialog('Az Ön kosara üres.');
								}
								else {
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

}(jQuery);