$(document).ready(function(){
	var kosar={
			container:'#mattkarb',
			viewUrl:'/admin/kosar/getkarb',
			newWindowUrl:'/admin/kosar/viewkarb',
			saveUrl:'/admin/kosar/save',
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
			addVisible:false,
			filter:{
				fields:['#nevfilter']
			},
			tablebody:{
				url:'/admin/kosar/getlistbody'
			},
			karb:kosar
		});

		$('.js-maincheckbox').change(function(){
			$('.js-egyedcheckbox').prop('checked',$(this).prop('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},kosar,{independent:true}));
		}
	}
});