$(document).ready(function(){
	$('#mattkarb').mattkarb({
		independent:true,
		viewUrl:'/admin/megrendelesfej/getkarb',
		newWindowUrl:'/admin/megrendelesfej/viewkarb',
		saveUrl:'/admin/megrendelesfej/save',
		beforeShow:function() {
			var keltedit=$('#KeltEdit'),
				teljedit=$('#TeljesitesEdit'),
				esededit=$('#EsedekessegEdit'),
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
			alttab.on('click','.tetelnewbutton',function(e) {
				e.preventDefault();
				$.ajax({
					url:'/admin/bizonylattetel/getemptyrow',
					type:'GET',
					success:function(data) {
						var tbody=$('#RecepturaTab');
						alttab.append(data);
						$('.tetelnewbutton,.teteldelbutton,.tetelremovebutton').button();
					}
				});
			})
			.on('click','.tetelremovebutton',function(e) {
				e.preventDefault();
				if ($('.tetelremovebutton').length>1) {
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
			.on('change','.termekselect',function(e) {
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
			.on('change','.vtszselect',function(e) {
				e.preventDefault();
				var $this=$(this);
				var sorid=$this.attr('name').split('_')[1],
					valasztott=$('option:selected',$this);
				var afa=$('select[name="tetelafa_'+sorid+'"]');
				afa.val(valasztott.data('afa'));
				afa.change();
			})
			.on('change','.afaselect',function(e) {
				e.preventDefault();
				var sorid=$(this).attr('name').split('_')[1];
				calcArak(sorid);
			})
			.on('change','.nettoegysarinput',function(e) {
				e.preventDefault();
				var sorid=$(this).attr('name').split('_')[1];
				calcArak(sorid);
			})
			.on('change','.bruttoegysarinput',function(e) {
				e.preventDefault();
				var sorid=$(this).attr('name').split('_')[1];
				var afakulcs=$('select[name="tetelafa_'+sorid+'"] option:selected').data('afakulcs');
				var n=$('input[name="tetelnettoegysar_'+sorid+'"]');
				n.val($(this).val()/(100+afakulcs)*100);
				n.change();
			})
			.on('change','.nettoinput',function(e) {
				e.preventDefault();
				var sorid=$(this).attr('name').split('_')[1];
				var n=$('input[name="tetelnettoegysar_'+sorid+'"]');
				n.val($(this).val()/$('input[name="tetelmennyiseg_'+sorid+'"]').val());
				n.change();
			})
			.on('change','.bruttoinput',function(e) {
				e.preventDefault();
				var sorid=$(this).attr('name').split('_')[1];
				var afakulcs=$('select[name="tetelafa_'+sorid+'"] option:selected').data('afakulcs');
				var n=$('input[name="tetelnetto_'+sorid+'"]');
				n.val($(this).val()/(100+afakulcs)*100);
				n.change();
			})
			.on('change','.mennyiseginput',function(e) {
				e.preventDefault();
				var sorid=$(this).attr('name').split('_')[1];
				calcArak(sorid);
			});
			$('.teteldelbutton').on('click',function(e) {
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
			$('.tetelnewbutton,.teteldelbutton,.tetelremovebutton').button();
			keltedit.datepicker($.datepicker.regional['hu']);
			keltedit.datepicker('option','dateFormat','yy.mm.dd');
			keltedit.datepicker('setDate',keltedit.attr('data-datum'));
			teljedit.datepicker($.datepicker.regional['hu']);
			teljedit.datepicker('option','dateFormat','yy.mm.dd');
			teljedit.datepicker('setDate',teljedit.attr('data-datum'));
			esededit.datepicker($.datepicker.regional['hu']);
			esededit.datepicker('option','dateFormat','yy.mm.dd');
			esededit.datepicker('setDate',esededit.attr('data-datum'));
		},
		onSubmit:function() {
			$('#messagecenter')
				.html('A mentés sikerült.')
				.hide()
				.addClass('matt-messagecenter ui-widget ui-state-highlight')
				.one('click',messagecenterclick)
				.slideToggle('slow');
		}
	});
});