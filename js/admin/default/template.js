$(document).ready(function(){
	var template={
			container:'#mattkarb',
			viewUrl:'/admin/template/getkarb',
			newWindowUrl:'/admin/template/viewkarb',
			saveUrl:'/admin/template/save',
			onSubmit:function() {
				$('#messagecenter')
					.html('A mentés sikerült.')
					.hide()
					.addClass('matt-messagecenter ui-widget ui-state-highlight')
					.one('click',messagecenterclick)
					.slideToggle('slow');
			}
	};

	if ($.fn.mattable) {
		$('#mattable-select').mattable({
			tablebody:{
				url:'/admin/template/getlistbody'
			},
			filter:{},
			batch:{},
			pager:{},
			karb:template
		});

		$('#maincheckbox').change(function(){
			$('.egyedcheckbox').attr('checked',$(this).attr('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},template,{independent:true}));
		}
	}
});