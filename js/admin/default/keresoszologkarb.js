$(document).ready(function(){
	$('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/keresoszolog/getkarb',
		newWindowUrl:'/admin/keresoszolog/viewkarb',
		saveUrl:'/admin/keresoszolog/save',
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