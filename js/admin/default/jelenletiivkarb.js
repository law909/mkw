$(document).ready(function(){
	$('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/jelenletiiv/getkarb',
		newWindowUrl:'/admin/jelenletiiv/viewkarb',
		saveUrl:'/admin/jelenletiiv/save',
		beforeShow:function() {
			var datumedit=$('#DatumEdit');
			datumedit.datepicker($.datepicker.regional['hu']);
			datumedit.datepicker('option','dateFormat','yy.mm.dd');
			datumedit.datepicker('setDate',datumedit.attr('data-datum'));				
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