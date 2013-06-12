$(document).ready(function(){
	$('#mattable-select').mattable({
		tablebody:{
			url:'/admin/template/getlistbody'
		},
		filter:{},
		batch:{},
		pager:{},
		karb:{
			container:'#mattkarb',
			viewUrl:'/admin/template/getkarb',
			newWindowUrl:'/admin/template/viewkarb',
			saveUrl:'/admin/template/save',
/*			beforeShow:function() {
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
*/
		}
	});
});