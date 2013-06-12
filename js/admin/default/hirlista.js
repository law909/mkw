$(document).ready(function(){
	$('#mattable-select').mattable({
		filter:{
			fields:['#nevfilter']
		},
		tablebody:{
			url:'/admin/hir/getlistbody'
		},
		karb:{
			container:'#mattkarb',
			viewUrl:'/admin/hir/getkarb',
			newWindowUrl:'/admin/hir/viewkarb',
			saveUrl:'/admin/hir/save',
			beforeShow:function() {
				if (!$.browser.mobile) {
					$('#SzovegEdit').ckeditor();
					$('#LeadEdit').ckeditor();
				}
				var dedit=$('#DatumEdit');
				dedit.datepicker($.datepicker.regional['hu']);
				dedit.datepicker('option','dateFormat','yy.mm.dd');
				dedit.datepicker('setDate',dedit.attr('data-datum'));
				dedit=$('#ElsoDatumEdit');
				dedit.datepicker($.datepicker.regional['hu']);
				dedit.datepicker('option','dateFormat','yy.mm.dd');
				dedit.datepicker('setDate',dedit.attr('data-datum'));
				dedit=$('#UtolsoDatumEdit');
				dedit.datepicker($.datepicker.regional['hu']);
				dedit.datepicker('option','dateFormat','yy.mm.dd');
				dedit.datepicker('setDate',dedit.attr('data-datum'));
			},
			beforeHide:function() {
				if (!$.browser.mobile) {
					editor=$('#SzovegEdit').ckeditorGet();
					if (editor) {
						editor.destroy();
					}
					editor=$('#LeadEdit').ckeditorGet();
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
	$('#mattable-body').on('click','.flagcheckbox',function(e) {
		e.preventDefault();
		var $this=$(this);
		$.ajax({
			url:'/admin/hir/setlathato',
			data:{
				id:$this.attr('data-id'),
				kibe:!$this.is('.ui-state-hover')
			},
			success:function() {
				$this.toggleClass('ui-state-hover');
			}
		});
	});
});