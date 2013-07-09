$(document).ready(function(){
	var keresoszolog={
			container:'#mattkarb',
			viewUrl:'/admin/keresoszolog/getkarb',
			newWindowUrl:'/admin/keresoszolog/viewkarb',
			saveUrl:'/admin/keresoszolog/save',
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
			filter:{
				fields:['#nevfilter']
			},
			tablebody:{
				url:'/admin/keresoszolog/getlistbody'
			},
			karb:keresoszolog
		});
		$('.js-maincheckbox').change(function(){
			$('.js-egyedcheckbox').prop('checked',$(this).prop('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},keresoszolog,{independent:true}));
		}
	}
});