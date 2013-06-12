(function($) {
	$.fn.mattaccord=function(options) {
		if (!this.length) {
			return this;
		}

		var baseOptions={
			animationSpeed:50,
			header:'#header',
			page:'.accordpage',
			closeUp:'.closeupbutton',
			collapse:'#collapse'
		};

		var setup=$.extend({},baseOptions,options);

		var _dataattr={
			pagevisible:'data-visible',
			pagerefcontrol:'data-refcontrol'
		};

		var container=$(this),
			header=$(setup.header);

		var _collapseExpand=function(par,showhide) {
			var ref=$($(par).attr(_dataattr.pagerefcontrol));
			if (showhide=='show') {
				if (ref.attr(_dataattr.pagevisible)=='hidden') {
					ref.attr(_dataattr.pagevisible,'visible');
					ref.slideDown(setup.animationSpeed);
					$('> a > span',par).removeClass('ui-icon-circle-triangle-s').addClass('ui-icon-circle-triangle-n');
				}
			}
			else {
				if (ref.attr(_dataattr.pagevisible)!=='hidden') {
					ref.attr(_dataattr.pagevisible,'hidden');
					ref.slideUp(setup.animationSpeed);
					$('> a > span',par).removeClass('ui-icon-circle-triangle-n').addClass('ui-icon-circle-triangle-s');
				}
			}
		};

		var initialize=function() {
			container.addClass('ui-widget ui-widget-content ui-corner-all');
			header.addClass('mattable-titlebar ui-widget-header ui-corner-top ui-helper-clearfix');
			$(setup.closeUp).on('click',function(e) {
				e.preventDefault();
				var ref=$($(this).attr(_dataattr.pagerefcontrol));
				if (ref.attr(_dataattr.pagevisible)=='hidden') {
					ref.attr(_dataattr.pagevisible,'visible');
					ref.slideDown(setup.animationSpeed);
					$('> a > span',this).removeClass('ui-icon-circle-triangle-s').addClass('ui-icon-circle-triangle-n');
				}
				else {
					ref.attr(_dataattr.pagevisible,'hidden');
					ref.slideUp(setup.animationSpeed);
					$('> a > span',this).removeClass('ui-icon-circle-triangle-n').addClass('ui-icon-circle-triangle-s');
				}
			});
			$(setup.page).each(function(index) {
				var self=$(this).addClass('mattedit-page');
				if (self.attr(_dataattr.pagevisible)=='hidden') {
					self.hide();
				}
			});
			$(setup.collapse).on('click',function(e) {
				e.preventDefault();
				var $this=$(this);
				if ($this.attr(_dataattr.pagevisible)=='visible') {
					$(setup.closeUp).each(function(i) {
						_collapseExpand(this,'hide');
					});
					$this.attr(_dataattr.pagevisible,'hidden');
				}
				else {
					$(setup.closeUp).each(function(i) {
						_collapseExpand(this,'show');
					});
					$this.attr(_dataattr.pagevisible,'visible');
				}
			});
		};

		initialize();
		return this;
	}
})(jQuery);