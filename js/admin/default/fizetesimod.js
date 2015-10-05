$(document).ready(function(){
	var mattkarbconfig={
			container:'#mattkarb',
			viewUrl:'/admin/fizetesimod/getkarb',
			newWindowUrl:'/admin/fizetesimod/viewkarb',
			saveUrl:'/admin/fizetesimod/save',
			onSubmit:function() {
				$('#messagecenter')
					.html('A mentés sikerült.')
					.hide()
					.addClass('matt-messagecenter ui-widget ui-state-highlight')
					.one('click',messagecenterclick)
					.slideToggle('slow');
			}
	}

	if ($.fn.mattable) {
		$('#mattable-select').mattable({
			filter:{
				fields:['#nevfilter']
			},
			tablebody:{
				url:'/admin/fizetesimod/getlistbody'
			},
			karb:mattkarbconfig
		});
		$('#maincheckbox').change(function(){
			$('.egyedcheckbox').attr('checked',$(this).attr('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},mattkarbconfig,{independent:true}));
		}
	}
});