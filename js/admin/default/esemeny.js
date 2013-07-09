$(document).ready(function(){
	var esemeny={
			container:'#mattkarb',
			viewUrl:'/admin/esemeny/getkarb',
			newWindowUrl:'/admin/esemeny/viewkarb',
			saveUrl:'/admin/esemeny/save',
			beforeShow:function() {
				if (!$.browser.mobile) {
					CKFinder.setupCKEditor( null, '/ckfinder/' );
					$('#LeirasEdit').ckeditor();
				}
				var esedekesedit=$('#EsedekesEdit');
				esedekesedit.datepicker($.datepicker.regional['hu']);
				esedekesedit.datepicker('option','dateFormat','yy.mm.dd');
				esedekesedit.datepicker('setDate',esedekesedit.attr('data-esedekes'));
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
			}
	};

	if ($.fn.mattable) {
		$('#mattable-select').mattable({
			name:'egyed',
			filter:{
				fields:['#bejegyzesfilter','#dtfilter','#difilter']
			},
			tablebody:{
				url:'/admin/esemeny/getlistbody'
			},
			karb:esemeny
		});

		$('.js-maincheckbox').change(function(){
			$('.js-egyedcheckbox').prop('checked',$(this).prop('checked'));
		});
		var dfilter=$('#dtfilter');
		dfilter.datepicker($.datepicker.regional['hu']);
		dfilter.datepicker('option','dateFormat','yy.mm.dd');
		dfilter=$('#difilter');
		dfilter.datepicker($.datepicker.regional['hu']);
		dfilter.datepicker('option','dateFormat','yy.mm.dd');
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},esemeny,{independent:true}));
		}
	}
});