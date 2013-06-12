$(document).ready(function(){
	$('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/template/getkarb',
		newWindowUrl:'/admin/template/viewkarb',
		saveUrl:'/admin/template/save',
		beforeShow:function() {
/*			if (!$.browser.mobile) {
				$('#LeirasEdit').ckeditor();
			}*/
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