$(document).ready(function(){
	$('#mattable-select').mattable({
		filter:{
			fields:['#nevfilter']
		},
		tablebody:{
			url:'/admin/targyieszkoz/getlistbody'
		},
		karb:{
			container:'#mattkarb',
			viewUrl:'/admin/targyieszkoz/getkarb',
			newWindowUrl:'/admin/targyieszkoz/viewkarb',
			saveUrl:'/admin/targyieszkoz/save',
			beforeShow:function() {
				$('#HasznalatihelyEdit').autocomplete({
					source:'/admin/targyieszkoz/gethasznalatihelyek',
					minLength:0,
					delay:600
				});
				var szvtvelszkezdeteedit=$('#SzvtvelszkezdeteEdit');
				szvtvelszkezdeteedit.datepicker(
					$.extend(true,
						$.datepicker.regional['hu'],
						{onSelect:function(dateText,inst){
								$('#TatvelszkezdeteEdit').datepicker('setDate',dateText);
							}
						}
					));
				szvtvelszkezdeteedit.datepicker('option','dateFormat','yy.mm.dd');
				szvtvelszkezdeteedit.datepicker('setDate',szvtvelszkezdeteedit.attr('data-esedekes'));
				var tatvelszkezdeteedit=$('#TatvelszkezdeteEdit');
				tatvelszkezdeteedit.datepicker($.datepicker.regional['hu']);
				tatvelszkezdeteedit.datepicker('option','dateFormat','yy.mm.dd');
				tatvelszkezdeteedit.datepicker('setDate',tatvelszkezdeteedit.attr('data-esedekes'));
				var allapotdatumedit=$('#AllapotDatumEdit');
				allapotdatumedit.datepicker($.datepicker.regional['hu']);
				allapotdatumedit.datepicker('option','dateFormat','yy.mm.dd');
				allapotdatumedit.datepicker('setDate',allapotdatumedit.attr('data-esedekes'));
				$('#AllapotEdit').change(function() {
					$('#AllapotDatumTr').toggle($('#AllapotEdit option:selected').val()!=='1');
				})
				.trigger('change');
				var beszdatumedit=$('#BeszerzesDatumEdit');
				beszdatumedit.datepicker($.datepicker.regional['hu']);
				beszdatumedit.datepicker('option','dateFormat','yy.mm.dd');
				beszdatumedit.datepicker('setDate',beszdatumedit.attr('data-esedekes'));
			}
		}
	});
	$('#maincheckbox').change(function(){
		$('.egyedcheckbox').attr('checked',$(this).attr('checked'));
	});
});