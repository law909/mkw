$(document).ready(function(){
	$('#mattable-select').mattable({
		name:'partner',
		filter:{
			fields:['#nevfilter'],
			onClear:function() {
				$('.cimkefilter').removeClass('ui-state-hover');
			},
			onFilter:function(obj) {
				var cimkek=new Array();
				$('.cimkefilter').filter('.ui-state-hover').each(function() {
					cimkek.push($(this).attr('data-id'));
				});
				if (cimkek.length>0) {
					obj['cimkefilter']=cimkek;
				}
			}
		},
		tablebody:{
			url:'/admin/partner/getlistbody'
		},
		karb:{
			container:'#mattkarb',
			viewUrl:'/admin/partner/getkarb',
			newWindowUrl:'/admin/partner/viewkarb',
			saveUrl:'/admin/partner/save',
			beforeShow:function() {
				var szuletesiidoedit=$('#SzuletesiidoEdit');
				szuletesiidoedit.datepicker($.datepicker.regional['hu']);
				szuletesiidoedit.datepicker('option','dateFormat','yy.mm.dd');
				szuletesiidoedit.datepicker('setDate',szuletesiidoedit.attr('data-datum'));
				$('#cimkekarbcontainer').mattaccord({
					header:'',
					page:'.cimkekarbpage',
					closeUp:'.cimkekarbcloseupbutton'
				})
				.on('click','.cimkekarb',function(e) {
					e.preventDefault();
					$(this).toggleClass('selectedcimke ui-state-hover');
				});
				$('.cimkeadd').on('click',function(e) {
					e.preventDefault();
					var ref=$(this).attr('data-refcontrol');
					var cimkenev=$(ref).val(),
						katkod=ref.split('_')[1];
					$.ajax({
						url:'/admin/partnercimke/add',
						type:'POST',
						data:{
							cimkecsoport:katkod,
							nev:cimkenev
						},
						success:function(data) {
							$(ref).val('');
							$(ref).before(data);
						}
					});
				});
				$('#KontaktTab').on('click','.kontaktnewbutton',function(e) {
					var $this=$(this);
					e.preventDefault();
					$.ajax({
						url:'/admin/kontakt/getemptyrow',
						type:'GET',
						success:function(data) {
							var tbody=$('#KontaktTab');
							tbody.append(data);
							$('.kontaktnewbutton,.kontaktdelbutton').button();
							$this.remove();
						}
					});
				})
				.on('click','.kontaktdelbutton',function(e) {
					e.preventDefault();
					var kontaktgomb=$(this),
						kontaktid=kontaktgomb.attr('data-id');
					if (kontaktgomb.attr('data-source')=='client') {
						$('#kontakttable_'+kontaktid).remove();
					}
					else {
						$('#dialogcenter').html('Biztos, hogy törli a kontaktot?').dialog({
							resizable: false,
							height:140,
							modal: true,
							buttons: {
								'Igen': function() {
									$.ajax({
										url:'/admin/kontakt/save',
										type:'POST',
										data:{
											id:kontaktid,
											oper:'del'
										},
										success:function(data) {
											$('#kontakttable_'+data).remove();
										}
									});
									$(this).dialog('close');
								},
								'Nem': function() {
									$(this).dialog('close');
								}
							}
						});
					}
				});
				$('.kontaktnewbutton,.kontaktdelbutton').button();
			},
			beforeSerialize:function(form,opt) {
				var cimkek=new Array();
				$('.cimkekarb').filter('.selectedcimke').each(function() {
					cimkek.push($(this).attr('data-id'));
				});
				var x={};
				x['cimkek[]']=cimkek;
				opt['data']=x;
			}
		}
	});
	$('#maincheckbox').change(function(){
		$('.partnercheckbox').attr('checked',$(this).attr('checked'));
	});
	$('#cimkefiltercontainer').mattaccord({
		header:'',
		page:'.accordpage',
		closeUp:'.cimkefiltercloseupbutton'
	});
	$('.cimkefilter').on('click',function(e) {
		e.preventDefault();
		$(this).toggleClass('ui-state-hover');
	});
});