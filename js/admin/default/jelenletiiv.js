$(document).ready(function(){
	var jelenletiiv={
			container:'#mattkarb',
			viewUrl:'/admin/jelenletiiv/getkarb',
			newWindowUrl:'/admin/jelenletiiv/viewkarb',
			saveUrl:'/admin/jelenletiiv/save',
			beforeShow:function() {
				var datumedit=$('#DatumEdit');
				datumedit.datepicker($.datepicker.regional['hu']);
				datumedit.datepicker('option','dateFormat','yy.mm.dd');
				datumedit.datepicker('setDate',datumedit.attr('data-datum'));
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
			filter:{
				fields:['#tolfilter','#igfilter','#dolgozofilter','#jelenlettipusfilter']
			},
			tablebody:{
				url:'/admin/jelenletiiv/getlistbody'
			},
			karb:jelenletiiv
		});
		$('#maincheckbox').change(function(){
			$('.egyedcheckbox').attr('checked',$(this).attr('checked'));
		});
		var gendatumedit=$('#gendatum');
		gendatumedit.datepicker($.datepicker.regional['hu']);
		gendatumedit.datepicker('option','dateFormat','yy.mm.dd');
		gendatumedit.datepicker('setDate',new Date());
		$('#GeneralBtn').on('click',function(e) {
			e.preventDefault();
			var mehet=false;
			var ertek=$('#genjelenlettipus').val();
			var datum=gendatumedit.val();
			if (ertek>0) {
				mehet=true;
			}
			else {
				mehet=false;
				$("#dialogcenter").html('Válasszon jelenlét tipust.').dialog({
					resizable: false,
					height:140,
					modal: true,
					buttons: {
						'Ok': function() {
							$(this).dialog('close');
						}
					}
				});
			}
			if (!datum) {
				mehet=false;
				$("#dialogcenter").html('Adjon meg egy napot.').dialog({
					resizable: false,
					height:140,
					modal: true,
					buttons: {
						'Ok': function() {
							$(this).dialog('close');
						}
					}
				});
			}
			if (mehet) {
				$.blockUI({
					message:'Kérem várjon...',
					css:{
						border:'none',
						padding:'15px',
						backgroundColor:'#000',
						'-webkit-border-radius':'10px',
						'-moz-border-radius':'10px',
						opacity:.5,
						color:'#fff'
						}
				});
				$.ajax({
					url:'/admin/jelenletiiv/generatenapi',
					type:'POST',
					data:{
						datum:datum,
						jt:ertek
					},
					success:function() {
						$.unblockUI();
						$('mattable-tablerefresh').click();
					}
				});
			}
		})
		.button();
		var tolfilter=$('#tolfilter');
		tolfilter.datepicker($.datepicker.regional['hu']);
		tolfilter.datepicker('option','dateFormat','yy.mm.dd');
	//	tolfilter.datepicker('setDate',tolfilter.attr('data-datum'));
		var igfilter=$('#igfilter');
		igfilter.datepicker($.datepicker.regional['hu']);
		igfilter.datepicker('option','dateFormat','yy.mm.dd');
	//	igfilter.datepicker('setDate',igfilter.attr('data-datum'));
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},jelenletiiv,{independent:true}));
		}
	}
});