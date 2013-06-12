$(document).ready(function(){
	var dialogcenter=$('#dialogcenter');
	$('#mattable-select').mattable({
		filter:{
			fields:['#idfilter']
		},
		tablebody:{
			url:'/admin/nullaslista/getlistbody'
		},
		karb:{
			container:'#mattkarb',
			viewUrl:'/admin/nullaslista/getkarb',
			newWindowUrl:'/admin/nullaslista/viewkarb',
			saveUrl:'/admin/nullaslista/save',
			beforeShow:function() {
				var keltedit=$('#KeltEdit');
				$('#PartnerEdit').change(function() {
					var valasz=$('option:selected',this);
					$('#PartnerCim').text(valasz.data('cim'));
				});
				keltedit.datepicker($.datepicker.regional['hu']);
				keltedit.datepicker('option','dateFormat','yy.mm.dd');
				keltedit.datepicker('setDate',keltedit.attr('data-datum'));
				initGrid();
			}
		}
	});
	$('#maincheckbox').change(function(){
		$('.egyedcheckbox').attr('checked',$(this).attr('checked'));
	});
});