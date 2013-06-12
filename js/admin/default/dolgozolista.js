$(document).ready(function(){
	$('#mattable-select').mattable({
		filter:{
			fields:['#nevfilter']
		},
		tablebody:{
			url:'/admin/dolgozo/getlistbody'
		},
		karb:{
			container:'#mattkarb',
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
			}
		}
	});
	$('#maincheckbox').change(function(){
		$('.egyedcheckbox').attr('checked',$(this).attr('checked'));
	});
});