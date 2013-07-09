$(document).ready(function(){
	var szamlafej={
			container:'#mattkarb',
			viewUrl:'/admin/megrendelesfej/getkarb',
			newWindowUrl:'/admin/megrendelesfej/viewkarb',
			saveUrl:'/admin/megrendelesfej/save',
			beforeShow:function() {
				var keltedit=$('#KeltEdit'),
					teljedit=$('#TeljesitesEdit'),
					esededit=$('#EsedekessegEdit'),
					hatidoedit=$('#HataridoEdit'),
					fizmodedit=$('#FizmodEdit'),
					bankszamlaedit=$('#BankszamlaEdit'),
					alttab=$('#AltalanosTab');
				$('#PartnerEdit').change(function() {
					var valasz=$('option:selected',this);
					fizmodedit.val(valasz.data('fizmod'));
					$('#PartnerCim').text(valasz.data('cim'));
					setDates();
				});
				fizmodedit.change(function() {
					setDates();
				});
				if (esededit.data('alap')=='1') {
					keltedit.change(function() {
						setDates();
					});
					teljedit.change(function() {
						getArfolyam();
					});
				}
				else {
					teljedit.change(function() {
						setDates();
						getArfolyam();
					});
				}
				$('#ValutanemEdit').change(function() {
					bankszamlaedit.val($('option:selected',this).data('bankszamla'));
					getArfolyam();
				});
				alttab.on('click','.js-tetelnewbutton',function(e) {
					e.preventDefault();
					$.ajax({
						url:'/admin/bizonylattetel/getemptyrow',
						type:'GET',
						success:function(data) {
							var tbody=$('#RecepturaTab');
							alttab.append(data);
							$('.js-tetelnewbutton,.js-teteldelbutton,.js-tetelremovebutton').button();
						}
					});
				})
				.on('click','.js-tetelremovebutton',function(e) {
					e.preventDefault();
					if ($('.js-tetelremovebutton').length>1) {
						var removegomb=$(this);
						dialogcenter.html('Biztos, hogy törli a tételt?').dialog({
							resizable: false,
							height:140,
							modal: true,
							buttons: {
								'Igen':function() {
									$('#teteltable_'+removegomb.data('id')).remove();
									$(this).dialog('close');
								},
								'Nem':function() {
									$(this).dialog('close');
								}
							}
						});
					}
				})
				.on('change','.js-termekselect',function(e) {
					e.preventDefault();
					var $this=$(this);
					var sorid=$this.attr('name').split('_')[1],
						valasztott=$('option:selected',$this);
					$('input[name="tetelnev_'+sorid+'"]').val(valasztott.text());
					$('input[name="tetelcikkszam_'+sorid+'"]').val(valasztott.data('cikkszam'));
					$('input[name="tetelme_'+sorid+'"]').val(valasztott.data('me'));
					setTermekAr(sorid);
					var vtsz=$('select[name="tetelvtsz_'+sorid+'"]');
					vtsz.val(valasztott.data('vtsz'));
					vtsz.change();
					var afa=$('select[name="tetelafa_'+sorid+'"]');
					afa.val(valasztott.data('afa'));
					afa.change();
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
				});
				$('.js-teteldelbutton').on('click',function(e) {
					e.preventDefault();
					var argomb=$(this);
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
										id:argomb.attr('data-id'),
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
				});
				$('.js-tetelnewbutton,.js-teteldelbutton,.js-tetelremovebutton').button();
				keltedit.datepicker($.datepicker.regional['hu']);
				keltedit.datepicker('option','dateFormat','yy.mm.dd');
				keltedit.datepicker('setDate',keltedit.attr('data-datum'));
				teljedit.datepicker($.datepicker.regional['hu']);
				teljedit.datepicker('option','dateFormat','yy.mm.dd');
				teljedit.datepicker('setDate',teljedit.attr('data-datum'));
				esededit.datepicker($.datepicker.regional['hu']);
				esededit.datepicker('option','dateFormat','yy.mm.dd');
				esededit.datepicker('setDate',esededit.attr('data-datum'));
				hatidoedit.datepicker($.datepicker.regional['hu']);
				hatidoedit.datepicker('option','dateFormat','yy.mm.dd');
				hatidoedit.datepicker('setDate',esededit.attr('data-datum'));
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
			karb:szamlafej
		});

		$('.js-maincheckbox').change(function(){
			$('.js-egyedcheckbox').prop('checked',$(this).prop('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},szamlafej,{independent:true}));
		}
	}
});