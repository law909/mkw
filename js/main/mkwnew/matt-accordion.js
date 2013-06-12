(function($) {
	$.fn.mattaccord=function(options) {
		if (!this.length) {
			return this;
		}

		var baseOptions={
			animationSpeed:50,
			closeUp:'.closeupbutton',
			iconDown:'icon-chevron-down',
			iconUp:'icon-chevron-up',
			icon:'i'
		};

		var setup=$.extend({},baseOptions,options);

		var _dataattr={
			pagevisible:'data-visible',
			pagerefcontrol:'data-refcontrol'
		};

		var initialize=function() {
			$(setup.closeUp).on('click',function(e) {
				e.preventDefault();
				var ref=$($(this).attr(_dataattr.pagerefcontrol));
				if (ref.attr(_dataattr.pagevisible)=='hidden') {
					ref.attr(_dataattr.pagevisible,'visible');
					ref.slideDown(setup.animationSpeed);
					$(setup.icon,this).removeClass(setup.iconDown).addClass(setup.iconUp);
				}
				else {
					ref.attr(_dataattr.pagevisible,'hidden');
					ref.slideUp(setup.animationSpeed);
					$(setup.icon,this).removeClass(setup.iconUp).addClass(setup.iconDown);
				}
			});
		};
		initialize();
		return this;
	}
})(jQuery);