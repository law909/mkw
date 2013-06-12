$(document).ready(function(){
	$('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/statlap/getkarb',
		newWindowUrl:'/admin/statlap/viewkarb',
		saveUrl:'/admin/statlap/save',
		beforeShow:function() {
			if (!$.browser.mobile) {
				$('#LeirasEdit').ckeditor();
			}				
		},
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