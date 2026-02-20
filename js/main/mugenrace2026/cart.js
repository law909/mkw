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
                $('#minikosaringyenes').html(d.minikosaringyenes);
                //$('table').html(d.tetellist);
                //mkw.initTooltips();
                $('#ertek_' + $('input[name="id"]', f).val()).text(d.tetelertek);
                $('#kosarsum').text(d.kosarertek);
            },
            complete: function() {
                //mkw.closeMessage();
            }
        });
	}

	function initUI() {
		// Event delegation: document-re kötjük, .js-cart a szűrő
		$(document)
			.on('input', '.js-cart input[name="mennyiseg"]', $.debounce(300, function() {
                console.log('cart input 1');
				var $this = $(this);
				if ((Math.round($this.val()) != 0)) {
					submitMennyEdit($this.parents('form.kosarform'));
				}
			}))
            .on('blur', '.js-cart input[name="mennyiseg"]', function() {
				var $this = $(this);
                console.log('cart input blur 1');
                if (Math.round($this.val()) == 0) {
                    $this.val($this.data('org'));
                    submitMennyEdit($this.parents('form.kosarform'));
                    mkw.showDialog(mkwmsg.KosarMennyisegNulla);
                }
                else {
					submitMennyEdit($this.parents('form.kosarform'));
                }
            })
			.on('submit', '.js-cart .kosarform', function() {
                console.log('cart input submit 1');
				submitMennyEdit($(this));
				return false;
			});
	}

	return {
		initUI: initUI
	};

})(jQuery);