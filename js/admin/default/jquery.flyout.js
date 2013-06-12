(function($) {
	jQuery.fn.extend({
		flyout : function(options) {
			var o = jQuery.extend({
				xOffset : 210,
				yOffset : 30
			}, options);
			$(this).hover(
				function(e) {
					//this.t = this.title;
					//this.title = '';
					//var c = (this.t != '') ? '<br/>' + this.t : '';
					$('body').append(
						'<p id="flyoutpreview"><img src="' + this.href
						+ '" alt="Image preview" />' //+ c
						+ '</p>');
					$('#flyoutpreview').css('top', (e.pageY - o.xOffset) + 'px')
						.css('left', (e.pageX + o.yOffset) + 'px')
						.fadeIn('fast');
				}, function() {
					//this.title = this.t;
					$('#flyoutpreview').remove();
				})
			.mousemove(
				function(e) {
					$('#flyoutpreview').css('top', (e.pageY - o.xOffset) + 'px')
						.css('left', (e.pageX + o.yOffset) + 'px');
			});
		}
	});
})(jQuery);