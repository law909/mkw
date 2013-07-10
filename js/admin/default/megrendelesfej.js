$(document).ready(function(){
	var dialogcenter=$('#dialogcenter'),
		termekautocomplete={
			minLength: 4,
			autoFocus: true,
			source: '/admin/bizonylattetel/gettermeklist',
			select: function(event, ui) {
				if (ui.item) {
					var $this=$(this),
						sorid=$this.attr('name').split('_')[1],
						vtsz=$('select[name="tetelvtsz_'+sorid+'"]'),
						afa=$('select[name="tetelafa_'+sorid+'"]'),
						valtozatplace=$('#ValtozatPlaceholder'+sorid);
					valtozatplace.empty();
					$this.siblings().val(ui.item.id);
					$('input[name="tetelnev_'+sorid+'"]').val(ui.item.value);
					$('input[name="tetelcikkszam_'+sorid+'"]').val(ui.item.cikkszam);
					$('input[name="tetelme_'+sorid+'"]').val(ui.item.me);
					setTermekAr(sorid);
					vtsz.val(ui.item.vtsz);
					vtsz.change();
					afa.val(ui.item.afa);
					afa.change();
					$.ajax({
						url:'/admin/termek/valtozathtmllist',
						data:{
							id:ui.item.id
						},
						success:function(data) {
							$(data)
								.appendTo(valtozatplace)
								.attr('name','tetelvaltozat_'+sorid)
								.addClass('js-tetelvaltozat');
						}
					});
				}
			}
		},
		megrendeles={
			container:'#mattkarb',
			viewUrl:'/admin/megrendelesfej/getkarb',
			newWindowUrl:'/admin/megrendelesfej/viewkarb',
			saveUrl:'/admin/megrendelesfej/save',
			beforeShow:function() {
				var keltedit=$('#KeltEdit'),
					hatidoedit=$('#HataridoEdit'),
					fizmodedit=$('#FizmodEdit'),
					bankszamlaedit=$('#BankszamlaEdit'),
					alttab=$('#AltalanosTab');
				$('#PartnerEdit').change(function() {
					var valasz=$('option:selected',this);
					fizmodedit.val(valasz.data('fizmod'));
					$('#PartnerCim').text(valasz.data('cim'));
				});
				$('#ValutanemEdit').change(function() {
					bankszamlaedit.val($('option:selected',this).data('bankszamla'));
					getArfolyam();
				});
				alttab.on('click','.js-tetelnewbutton',function(e) {
					var $this=$(this);
					e.preventDefault();
					$.ajax({
						url:'/admin/bizonylattetel/getemptyrow',
						type:'GET',
						success:function(data) {
							var tbody=$('#RecepturaTab');
							alttab.append(data);
							$('.js-tetelnewbutton,.js-teteldelbutton').button();
							$('.js-termekselect').autocomplete(termekautocomplete);
							$this.remove();
						}
					});
				})
				.on('click','.js-teteldelbutton',function(e) {
					e.preventDefault();
					var removegomb=$(this),
						removeid=removegomb.attr('data-id');
					if (removegomb.attr('data-source')=='client') {
						dialogcenter.html('Biztos, hogy törli a tételt?').dialog({
							resizable: false,
							height:140,
							modal: true,
							buttons: {
								'Igen':function() {
									$('#teteltable_'+removeid).remove();
									$(this).dialog('close');
								},
								'Nem':function() {
									$(this).dialog('close');
								}
							}
						});
					}
					else {
						dialogcenter.html('Biztos, hogy törli a tételt?').dialog({
							resizable: false,
							height:140,
							modal: true,
							buttons: {
								'Igen': function() {
									$.ajax({
										url:'/admin/bizonylattetel/save',
										type:'POST',
										data:{
											id:removeid,
											oper:'del'
										},
										success:function(data) {
											$('#teteltable_'+data).remove();
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
				})
				.on('change','.js-vtszselect',function(e) {
					e.preventDefault();
					var $this=$(this);
					var sorid=$this.attr('name').split('_')[1],
						valasztott=$('option:selected',$this);
					var afa=$('select[name="tetelafa_'+sorid+'"]');
					afa.val(valasztott.data('afa'));
					afa.change();
				})
				.on('change','.js-afaselect',function(e) {
					e.preventDefault();
					var sorid=$(this).attr('name').split('_')[1];
					calcArak(sorid);
				})
				.on('change','.js-nettoegysarinput',function(e) {
					e.preventDefault();
					var sorid=$(this).attr('name').split('_')[1];
					calcArak(sorid);
				})
				.on('change','.js-bruttoegysarinput',function(e) {
					e.preventDefault();
					var sorid=$(this).attr('name').split('_')[1];
					var afakulcs=$('select[name="tetelafa_'+sorid+'"] option:selected').data('afakulcs');
					var n=$('input[name="tetelnettoegysar_'+sorid+'"]');
					n.val($(this).val()/(100+afakulcs)*100);
					n.change();
				})
				.on('change','.js-nettoinput',function(e) {
					e.preventDefault();
					var sorid=$(this).attr('name').split('_')[1];
					var n=$('input[name="tetelnettoegysar_'+sorid+'"]');
					n.val($(this).val()/$('input[name="tetelmennyiseg_'+sorid+'"]').val());
					n.change();
				})
				.on('change','.js-bruttoinput',function(e) {
					e.preventDefault();
					var sorid=$(this).attr('name').split('_')[1];
					var afakulcs=$('select[name="tetelafa_'+sorid+'"] option:selected').data('afakulcs');
					var n=$('input[name="tetelnetto_'+sorid+'"]');
					n.val($(this).val()/(100+afakulcs)*100);
					n.change();
				})
				.on('change','.js-mennyiseginput',function(e) {
					e.preventDefault();
					var sorid=$(this).attr('name').split('_')[1];
					calcArak(sorid);
				})
				.on('change','.js-tetelvaltozat',function(e) {
					e.preventDefault();
					var sorid=$(this).attr('name').split('_')[1];
					setTermekAr(sorid);
				});
				$('.js-termekselect').autocomplete(termekautocomplete);
				$('.js-tetelnewbutton,.js-teteldelbutton').button();
				keltedit.datepicker($.datepicker.regional['hu']);
				keltedit.datepicker('option','dateFormat','yy.mm.dd');
				keltedit.datepicker('setDate',keltedit.attr('data-datum'));
				hatidoedit.datepicker($.datepicker.regional['hu']);
				hatidoedit.datepicker('option','dateFormat','yy.mm.dd');
				hatidoedit.datepicker('setDate',hatidoedit.attr('data-datum'));
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
				fields:['#idfilter']
			},
			tablebody:{
				url:'/admin/megrendelesfej/getlistbody'
			},
			karb:megrendeles
		});
		$('.js-maincheckbox').change(function(){
			$('.js-egyedcheckbox').prop('checked',$(this).prop('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},megrendeles,{independent:true}));
		}
	}
});
