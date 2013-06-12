$(document).ready(function(){
	$('#mattable-select').mattable({
		name:'cimke',
		filter:{
			fields:['#nevfilter','#ckfilter']
		},
		tablebody:{
			url:'/admin/kontaktcimke/getlistbody'
		},
		karb:{
			container:'mattkarb',
			viewUrl:'/admin/kontaktcimke/getkarb',
			newWindowUrl:'/admin/kontaktcimke/viewkarb',
			saveUrl:'/admin/kontaktcimke/save',
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
		$('.CimkeCheckbox').attr('checked',$(this).attr('checked'));
	});
	$('#mattable-body').on('click','.menulathatocheckbox',function(e) {
		e.preventDefault();
		var $this=$(this),
			f=$this.closest('tr');
		$.ajax({
			url:'/admin/kontaktcimke/setmenulathato',
			data:{
				id:f.attr('data-cimkeid'),
				num:$this.attr('data-num'),
				kibe:!$this.is('.ui-state-hover')
			},
			success:function() {
				$this.toggleClass('ui-state-hover');
			}
		});
	});
});