$(document).ready(function() {
	$('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/megrendelesfej/getkarb',
		newWindowUrl:'/admin/megrendelesfej/viewkarb',
		saveUrl:'/admin/megrendelesfej/save',
		onSubmit:function() {
			$('#messagecenter')
				.html('A mentés sikerült.')
				.hide()
				.addClass('matt-messagecenter ui-widget ui-state-highlight')
				.one('click',messagecenterclick)
				.slideToggle('slow');
		}
	});
});