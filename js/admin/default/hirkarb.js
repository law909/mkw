$(document).ready(function(){
	$('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/hir/getkarb',
		newWindowUrl:'/admin/hir/viewkarb',
		saveUrl:'/admin/hir/save',
		onSubmit:function() {
			$('#messagecenter')
				.html('A mentés sikerült.')
				.hide()
				.addClass('matt-messagecenter ui-widget ui-state-highlight')
				.one('click',messagecenterclick)
				.slideToggle('slow');
		},
		beforeShow:function() {
			if (!$.browser.mobile) {
				$('#SzovegEdit').ckeditor();
				$('#LeadEdit').ckeditor();
			}
			var dedit=$('#DatumEdit');
			dedit.datepicker($.datepicker.regional['hu']);
			dedit.datepicker('option','dateFormat','yy.mm.dd');
			dedit.datepicker('setDate',dedit.attr('data-datum'));
			dedit=$('#ElsoDatumEdit');
			dedit.datepicker($.datepicker.regional['hu']);
			dedit.datepicker('option','dateFormat','yy.mm.dd');
			dedit.datepicker('setDate',dedit.attr('data-datum'));
			dedit=$('#UtolsoDatumEdit');
			dedit.datepicker($.datepicker.regional['hu']);
			dedit.datepicker('option','dateFormat','yy.mm.dd');
			dedit.datepicker('setDate',dedit.attr('data-datum'));
		},
		beforeHide:function() {
			if (!$.browser.mobile) {
				editor=$('#SzovegEdit').ckeditorGet();
				if (editor) {
					editor.destroy();
				}
				editor=$('#LeadEdit').ckeditorGet();
				if (editor) {
					editor.destroy();
				}
			}
		}
	});
});