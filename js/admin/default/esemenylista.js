$(document).ready(function(){
	$('#mattable-select').mattable({
		name:'egyed',
		filter:{
			fields:['#bejegyzesfilter','#dtfilter','#difilter']
		},
		tablebody:{
			url:'/admin/esemeny/getlistbody'
		},
		karb:{
			container:'#mattkarb',
			viewUrl:'/admin/esemeny/getkarb',
			newWindowUrl:'/admin/esemeny/viewkarb',
			saveUrl:'/admin/esemeny/save',
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
		}
	});
	$('#maincheckbox').change(function(){
		$('.egyedcheckbox').attr('checked',$(this).attr('checked'));
	});
	var dfilter=$('#dtfilter');
	dfilter.datepicker($.datepicker.regional['hu']);
	dfilter.datepicker('option','dateFormat','yy.mm.dd');
	dfilter=$('#difilter');
	dfilter.datepicker($.datepicker.regional['hu']);
	dfilter.datepicker('option','dateFormat','yy.mm.dd');
});