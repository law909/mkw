$(document).ready(function(){
	var statlap={
			container:'#mattkarb',
			viewUrl:'/admin/statlap/getkarb',
			newWindowUrl:'/admin/statlap/viewkarb',
			saveUrl:'/admin/statlap/save',
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
	};

	if ($.fn.mattable) {
		$('#mattable-select').mattable({
			filter:{
				fields:['#nevfilter']
			},
			tablebody:{
				url:'/admin/statlap/getlistbody'
			},
			karb:statlap
		});

		$('.js-maincheckbox').change(function(){
			$('.js-egyedcheckbox').prop('checked',$(this).prop('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},statlap,{independent:true}));
		}
	}
});