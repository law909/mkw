$(document).ready(function(){
	$('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/nullaslista/getkarb',
		newWindowUrl:'/admin/nullaslista/viewkarb',
		saveUrl:'/admin/nullaslista/save',
		beforeShow:function() {
			var keltedit=$('#KeltEdit');
			$('#PartnerEdit').change(function() {
				var valasz=$('option:selected',this);
				$('#PartnerCim').text(valasz.data('cim'));
			});
			keltedit.datepicker($.datepicker.regional['hu']);
			keltedit.datepicker('option','dateFormat','yy.mm.dd');
			keltedit.datepicker('setDate',keltedit.attr('data-datum'));
			initGrid();				
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