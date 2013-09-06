var cart=function($) {

	function initUI() {
		if ($('.js-cart').length>0) {
			$('input[name="mennyiseg"]').on('blur', function() {
				var $this=$(this);
				$this.parents('form.kosarform').submit();
			});
		}
	}

	return {
		initUI: initUI
	};

}(jQuery);