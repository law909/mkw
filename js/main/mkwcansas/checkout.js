var checkout=function($) {

	var checkoutpasswordrow,
		checkoutpasswordcontainer,
		vezeteknevinput,keresztnevinput,telefoninput,kapcsemailinput,
		szamlanevinput,szamlairszaminput,szamlavarosinput,szamlautcainput,szamlaadoszaminput,
		szallnevinput,szallirszaminput,szallvarosinput,szallutcainput,
		webshopmessageinput,couriermessageinput,
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
		$('.js-chkszamlaadoszam').text(szamlaadoszaminput.val());
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
		var $this=$(obj).parent().parent().prev(),
			mycontainer=$($this.data('container'));
		if (mycontainer.hasClass('js-chkclosed')) {
			$('.js-chkdatacontainer').slideUp(0).addClass('js-chkclosed');
			mycontainer.slideDown(0).removeClass('js-chkclosed');
		}
	}

	function initUI() {
		var $checkout=$('.js-checkout');

		if ($checkout.length) {

			$.ajax({
				url: '/kosar/gethash',
				success: function(data) {
					kosarhash=data;
				}
			});

			$('.js-chktooltipbtn').tooltip({
				html: false,
				placement: 'right',
				container: 'body'
			});

			checkoutpasswordcontainer=$('.js-checkoutpasswordcontainer');
			checkoutpasswordrow=$('.js-checkoutpasswordrow').detach();

			vezeteknevinput=$('input[name="vezeteknev"]');
			keresztnevinput=$('input[name="keresztnev"]');
			telefoninput=$('input[name="telefon"]');
			kapcsemailinput=$('input[name="kapcsemail"]');
			szamlanevinput=$('input[name="szamlanev"]');
			szamlairszaminput=$('input[name="szamlairszam"]');
			szamlavarosinput=$('input[name="szamlavaros"]');
			szamlautcainput=$('input[name="szamlautca"]');
			szamlaadoszaminput=$('input[name="szamlaadoszam"]');
			szallnevinput=$('input[name="szallnev"]');
			szallirszaminput=$('input[name="szallirszam"]');
			szallvarosinput=$('input[name="szallvaros"]');
			szallutcainput=$('input[name="szallutca"]');
			szamlaeqszall=$('input[name="szamlaeqszall"]');
			webshopmessageinput=$('textarea[name="webshopmessage"]');
			couriermessageinput=$('textarea[name="couriermessage"]');

			loadFizmodList();

			$checkout
			.on('change','input[name="szallitasimod"]',function() {
				loadFizmodList();
			})
			.on('change','input[name="regkell"]',function() {
				checkoutpasswordcontainer.empty();
				if ($('input[name="regkell"]:checked').val()*1) {
					checkoutpasswordrow.appendTo(checkoutpasswordcontainer);
					$('.js-chktooltipbtn').tooltip({
						html: false,
						placement: 'right',
						container: 'body'
					});
				}
			})
			.on('change','.js-chkrefresh',function() {
				refreshAttekintes();
			});

			var $chklogin=$('.js-chklogin');
			if ($chklogin.length) {
				$('.js-chkszallitasiadatok').hide().addClass('js-chkclosed');
			}
			$('.js-chkszallmod, .js-chkattekintes').hide().addClass('js-chkclosed');
			$('.js-chkdatagroupheader').on('click',function(e) {
				e.preventDefault();
				var regkell=$('input[name="regkell"]:checked');
				if (!regkell.length && $chklogin.length) {
					mkw.showDialog('Válassza ki, hogy szeretne-e regisztrálni a vásárláshoz, vagy jelentkezzen be!');
				}
				else {
					var $this=$(this),
						mycontainer=$($this.data('container'));
					if (mycontainer.hasClass('js-chkclosed')) {
						$('.js-chkdatacontainer').slideUp(100).addClass('js-chkclosed');
						mycontainer.slideDown(100).removeClass('js-chkclosed');
					}
				}
			});

			$('.js-chkopenbtn').on('click',function(e) {
				e.preventDefault();
				var dg=$(this).data('datagroupheader'),
					datagroupheader=$(dg);
				datagroupheader.click();
			});

			szamlaeqszall.on('change',function(e) {
				var obj=$('.js-chkszamlaadatok');
				obj.toggleClass('notvisible');
				if (obj.hasClass('notvisible')) {
					$('input',obj).attr('disabled','disabled');
				}
				else {
					$('input',obj).attr('disabled',null);
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
			szamlaadoszaminput.on('invalid', function() {
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


			$('.js-chksendorderbtn').on('submit',function() {
				if (!$('input[name="aszfready"]').prop('checked')) {
					mkw.showDialog('Megrendelés előtt kérjük fogadja el az ÁSZF-et.');
				}
				else {
					$.ajax({
						url: '/kosar/gethash',
						success: function(data) {
							if (kosarhash && kosarhash != data) {
								var newhash=data;
								mkw.showDialog('A kosár tartalma megrendelés közben megváltozott, kérem ellenőrizze.');
								$.ajax({
									url: '/checkout/gettetellist',
									success: function(data) {
										$('.js-chktetellist').html(data);
										kosarhash=newhash;
									}
								});
							}
							else {
								return true;
							}
						}
					});
				}
				return false;
			});

		}
	}

	return {
		initUI: initUI
	};

}(jQuery);