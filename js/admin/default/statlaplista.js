$(document).ready(function(){
	$('#mattable-select').mattable({
		filter:{
			fields:['#nevfilter']
		},
		tablebody:{
			url:'/admin/statlap/getlistbody'
		},
		karb:{
			container:'#mattkarb',
			viewUrl:'/admin/statlap/getkarb',
			newWindowUrl:'/admin/statlap/viewkarb',
			saveUrl:'/admin/statlap/save',
			beforeShow:function() {
				if (!$.browser.mobile) {
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
			}				
		}
	});
	$('#maincheckbox').change(function(){
		$('.egyedcheckbox').attr('checked',$(this).attr('checked'));
	});
});