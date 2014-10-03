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