$(document).ready(function(){
	var emailtemplate={
		container:'#mattkarb',
		viewUrl:'/admin/emailtemplate/getkarb',
		newWindowUrl:'/admin/emailtemplate/viewkarb',
		saveUrl:'/admin/emailtemplate/save',
		beforeShow:function() {
			if (!$.browser.mobile) {
				CKFinder.setupCKEditor( null, '/ckfinder/' );
				$('#LeirasEdit').ckeditor();
			}
		},
		beforeHide:function() {
			if (!$.browser.mobile) {
				editor=$('#LeirasEdit').ckeditorGet();
				if (editor) {
					editor.destroy();
				}
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
	}

	if ($.fn.mattable) {
		$('#mattable-select').mattable({
			filter:{
				fields:['#nevfilter']
			},
			tablebody:{
				url:'/admin/emailtemplate/getlistbody'
			},
			karb:emailtemplate
		});
		$('.js-maincheckbox').change(function(){
			$('.js-egyedcheckbox').prop('checked',$(this).prop('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},emailtemplate,{independent:true}));
		}
	}
});