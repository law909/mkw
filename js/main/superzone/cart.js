var cart = (function($) {

	function submitMennyEdit(f) {
		var db = $('input[name="mennyiseg"]', f).val(),
            menny = Math.round(db);
        $.ajax({
            url: f.attr('action'),
            type: 'POST',
            data: {
                id: $('input[name="id"]', f).val(),
                mennyiseg: menny
            },
            beforeSend: function() {
                //mkw.showMessage(mkwmsg.KosarMennyisegModositas);
            },
            success: function(data) {
                var d = JSON.parse(data);
                $('#minikosar').html(d.minikosar);
                //$('table').html(d.tetellist);
                //mkw.initTooltips();
                $('#ertek_' + $('input[name="id"]', f).val()).text(d.tetelertek);
                $('#kosarsum').text(d.kosarertek);
                $('#mennyisegsum').text(d.mennyisegsum);
            },
            complete: function() {
                //mkw.closeMessage();
            }
        });
	}

	function initUI() {
		var $cart = $('.js-cart');

		if ($cart.length > 0) {
			$cart
			.on('input', 'input[name="mennyiseg"]', $.debounce(300, function() {
				var $this = $(this);
				if ((Math.round($this.val()) != 0)) {
					submitMennyEdit($(this).parents('form.kosarform'));
				}
			}))
            .on('blur', 'input[name="mennyiseg"]', function() {
				var $this = $(this);
                if (Math.round($this.val()) == 0) {
                    $this.val($this.data('org'));
                    submitMennyEdit($this.parents('form.kosarform'));
                    superz.showDialog('Please use the Remove button to remove an item from your order.');
                }
                else {
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