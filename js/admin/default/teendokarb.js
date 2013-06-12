$(document).ready(function(){
	$('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/teendo/getkarb',
		newWindowUrl:'/admin/teendo/viewkarb',
		saveUrl:'/admin/teendo/save',
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
				$('#LeirasEdit').ckeditor();
			}
			var esedekesedit=$('#EsedekesEdit');
			esedekesedit.datepicker($.datepicker.regional['hu']);
			esedekesedit.datepicker('option','dateFormat','yy.mm.dd');
			esedekesedit.datepicker('setDate',esedekesedit.attr('data-esedekes'));
		},
		beforeHide:function() {
			if (!$.browser.mobile) {
				editor=$('#LeirasEdit').ckeditorGet();
				if (editor) {
					editor.destroy();
				}
			}
		}
	});
});