$(document).ready(function(){
	$('#mattable-select').mattable({
		filter:{
			fields:['#nevfilter']
		},
		tablebody:{
			url:'/admin/emailtemplate/getlistbody'
		},
		karb:{
			container:'#mattkarb',
			viewUrl:'/admin/emailtemplate/getkarb',
			newWindowUrl:'/admin/emailtemplate/viewkarb',
			saveUrl:'/admin/emailtemplate/save',
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