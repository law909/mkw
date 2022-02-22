$(document).ready(function(){
	var mattkarbconfig={
			container: '#mattkarb',
			viewUrl: '/admin/kupon/getkarb',
			newWindowUrl: '/admin/kupon/viewkarb',
			saveUrl: '/admin/kupon/save',
			onSubmit: function() {
				$('#messagecenter')
					.html('A mentés sikerült.')
					.hide()
					.addClass('matt-messagecenter ui-widget ui-state-highlight')
					.one('click',messagecenterclick)
					.slideToggle('slow');
			},
            beforeHide: function() {
			    $('.mattable-tablerefresh').click();
            }
	}

	if ($.fn.mattable) {
		$('#mattable-select').mattable({
			filter:{
				fields:['#idfilter']
			},
			tablebody:{
				url:'/admin/kupon/getlistbody',
                onStyle: function() {
				    $('.js-printkupon').button();
                },
                onDoEditLink: function() {
                    $('.js-printkupon').each(function() {
                        var $this = $(this);
                        $this.attr('href', '/admin/kupon/print?id=' + $this.data('egyedid'));
                    });
                }
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