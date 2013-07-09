$(document).ready(function(){
	var uzletkoto={
			container:'#mattkarb',
			viewUrl:'/admin/uzletkoto/getkarb',
			newWindowUrl:'/admin/uzletkoto/viewkarb',
			saveUrl:'/admin/uzletkoto/save',
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
			name:'uzletkoto',
			filter:{
				fields:['#nevfilter']
			},
			tablebody:{
				url:'/admin/uzletkoto/getlistbody'
			},
			karb: uzletkoto
		});

		$('.js-maincheckbox').change(function(){
			$('.js-egyedcheckbox').prop('checked',$(this).prop('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},uzletkoto,{independent:true}));
		}
	}
});