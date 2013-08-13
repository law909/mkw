var checkout=function($) {

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
		loadFizmodList();
		$('.js-checkout').on('change','input[name="szallitasimod"]',function() {
			loadFizmodList();
		});
		var $chklogin=$('.js-chklogin');
		if ($chklogin.length) {
			$('.js-chkszallitasiadatok').hide().addClass('js-chkclosed');
		}
		$('.js-chkszallmod, .js-chkattekintes').hide().addClass('js-chkclosed');
		$('.js-chkdatagroupheader').on('click',function(e) {
			e.preventDefault();
			var $this=$(this),
				mycontainer=$($this.data('container'));
			if (mycontainer.hasClass('js-chkclosed')) {
				$('.js-chkdatacontainer').slideUp(100).addClass('js-chkclosed');
				mycontainer.slideDown(100).removeClass('js-chkclosed');
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
	}

	return {
		initUI: initUI
	};

}(jQuery);