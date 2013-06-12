$(document).ready(function(){
	$('#mattkarb').mattkarb({
		name:'uzletkoto',
		independent:true,
		viewUrl:'/admin/uzletkoto/getkarb',
		newWindowUrl:'/admin/uzletkoto/viewkarb',
		saveUrl:'/admin/uzletkoto/save',
		onSubmit:function() {
			$('#messagecenter')
				.html('A mentés sikerült.')
				.hide()
				.addClass('matt-messagecenter ui-widget ui-state-highlight')
				.one('click',messagecenterclick)
				.slideToggle('slow');
			//setTimeout('$("#messagecenter").unbind(messagecenterclick).slideToggle("slow");',5000);
		}
	});
});