var checkout=function($) {

	var checkoutpasswordrow,
		checkoutpasswordcontainer;

	function loadFizmodList() {
		$.ajax({
			url: '/checkout/getfizmodlist',
			data: {
				szallitasimod: $('input[name="szallitasimod"]:checked').val()
			},
			success: function(data) {
				$('.js-chkfizmodlist').html(data);
			}
		});
	}

	function initUI() {
		var $checkout=$('.js-checkout');

		if ($checkout.length) {
			checkoutpasswordcontainer=$('.js-checkoutpasswordcontainer');
			checkoutpasswordrow=$('.js-checkoutpasswordrow').detach();

			loadFizmodList();

			$checkout
			.on('change','input[name="szallitasimod"]',function() {
				loadFizmodList();
			})
			.on('change','input[name="regkell"]',function() {
			checkoutpasswordcontainer.empty();
			if ($('input[name="regkell"]:checked').val()*1) {
					checkoutpasswordrow.appendTo(checkoutpasswordcontainer);
				}
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

			$('input[name="szamlaeqszall"]').on('change',function(e) {
				$('.js-chkszamlaadatok').toggleClass('notvisible');
			});

			$('.js-chktooltipbtn').tooltip({
				html: false,
				placement: 'right',
				container: 'body'
			});

			mkw.irszamTypeahead('input[name="szamlairszam"]', 'input[name="szamlavaros"]');
			mkw.varosTypeahead('input[name="szamlairszam"]', 'input[name="szamlavaros"]');
			mkw.irszamTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');
			mkw.varosTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');
		}
	}

	return {
		initUI: initUI
	};

}(jQuery);