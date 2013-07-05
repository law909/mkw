$(document).ready(function(){
	var teendo={
			container:'#mattkarb',
			viewUrl:'/admin/teendo/getkarb',
			newWindowUrl:'/admin/teendo/viewkarb',
			saveUrl:'/admin/teendo/save',
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
				url:'/admin/teendo/getlistbody'
			},
			karb:teendo
		});
		$('#maincheckbox').change(function(){
			$('.egyedcheckbox').attr('checked',$(this).attr('checked'));
		});
		$('#dtfilter').datepicker($.datepicker.regional['hu']);
		$('#dtfilter').datepicker('option','dateFormat','yy.mm.dd');
		$('#difilter').datepicker($.datepicker.regional['hu']);
		$('#difilter').datepicker('option','dateFormat','yy.mm.dd');

		$('#mattable-body').on('click','.flagcheckbox',function(e) {
			e.preventDefault();
			var $this=$(this);
			$.ajax({
				url:'/admin/teendo/setflag',
				type:'POST',
				data:{
					id:$this.attr('data-id'),
					flag:$this.attr('data-flag'),
					kibe:!$this.is('.ui-state-hover')
				},
				success:function() {
					if (!$this.is('.ui-state-hover')) $this.toggleClass('ui-state-hover');
				}
			});
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},teendo,{independent:true}));
		}
	}
});