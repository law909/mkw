$(document).ready(function(){
	$('#mattkarb').mattkarb({
		name:'cimke',
		independent:true,
		viewUrl:'/admin/kontaktcimke/getkarb',
		newWindowUrl:'/admin/kontaktcimke/viewkarb',
		saveUrl:'/admin/kontaktcimke/save',
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
			//setTimeout('$("#messagecenter").unbind(messagecenterclick).slideToggle("slow");',5000);
		}
	});
});