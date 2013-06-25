$(document).ready(function(){
	var partnercimke={
			container:'#mattkarb',
			viewUrl:'/admin/partnercimke/getkarb',
			newWindowUrl:'/admin/partnercimke/viewkarb',
			saveUrl:'/admin/partnercimke/save',
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
			},
			onSubmit:function() {
				$('#messagecenter')
					.html('A mentés sikerült.')
					.hide()
					.addClass('matt-messagecenter ui-widget ui-state-highlight')
					.one('click',messagecenterclick)
					.slideToggle('slow');
				//setTimeout('$("#messagecenter").unbind(messagecenterclick).slideToggle("slow");',5000);
			}
	};

	if ($.fn.mattable) {
		$('#mattable-select').mattable({
			name:'cimke',
			filter:{
				fields:['#nevfilter','#ckfilter']
			},
			tablebody:{
				url:'/admin/partnercimke/getlistbody'
			},
			karb:partnercimke
		});
		$('#maincheckbox').change(function(){
			$('.egyedcheckbox').attr('checked',$(this).attr('checked'));
		});
		$('#mattable-body').on('click','.menulathatocheckbox',function(e) {
			e.preventDefault();
			var $this=$(this),
				f=$this.closest('tr');
			$.ajax({
				url:'/admin/partnercimke/setmenulathato',
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
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},partnercimke,{independent:true}));
		}
	}
});