$(document).ready(function(){
	$('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/dolgozo/getkarb',
		newWindowUrl:'/admin/dolgozo/viewkarb',
		saveUrl:'/admin/dolgozo/save',
		beforeShow:function() {
			var szulidoedit=$('#SzulidoEdit');
			szulidoedit.datepicker($.datepicker.regional['hu']);
			szulidoedit.datepicker('option','dateFormat','yy.mm.dd');
			szulidoedit.datepicker('setDate',szulidoedit.attr('data-datum'));
			var mkvkedit=$('#MunkaviszonykezdeteEdit');
			mkvkedit.datepicker($.datepicker.regional['hu']);
			mkvkedit.datepicker('option','dateFormat','yy.mm.dd');
			mkvkedit.datepicker('setDate',mkvkedit.attr('data-datum'));
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