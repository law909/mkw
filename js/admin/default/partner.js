function irszamAutocomplete(irszam,varos) {
	$(irszam).autocomplete({
		minLength: 2,
		source: function(req,resp) {
			$.ajax({
				url: '/admin/irszam',
				type: 'GET',
				data: {
					term: req.term,
					tip: 1
				},
				success: function(data) {
					var d=JSON.parse(data);
					resp(d);
				},
				error: function() {
					resp();
				}
			});
		},
		select: function(event,ui) {
			$(varos).val(ui.item.nev);
		}
	});
}

function varosAutocomplete(irszam,varos) {
	$(varos).autocomplete({
		minLength: 4,
		source: function(req,resp) {
			$.ajax({
				url: '/admin/varos',
				type: 'GET',
				data: {
					term: req.term,
					tip: 1
				},
				success: function(data) {
					var d=JSON.parse(data);
					resp(d);
				},
				error: function() {
					resp();
				}
			});
		},
		select: function(event,ui) {
			$(irszam).val(ui.item.szam);
		}
	});
}

$(document).ready(function(){
	var partner={
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
					page:'.js-cimkekarbpage',
					closeUp:'.js-cimkekarbcloseupbutton'
				})
				.on('click','.js-cimkekarb',function(e) {
					e.preventDefault();
					$(this).toggleClass('js-selectedcimke ui-state-hover');
				});
				$('.js-cimkeadd').on('click',function(e) {
					e.preventDefault();
					var ref=$(this).attr('data-refcontrol');
					var cimkenev=$(ref).val(),
						katkod=ref.split('_')[1];
					if (cimkenev.length>0) {
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
					}
				});
				irszamAutocomplete('#IrszamEdit','#VarosEdit');
				varosAutocomplete('#IrszamEdit','#VarosEdit');
				irszamAutocomplete('#SzamlaIrszamEdit','#SzamlaVarosEdit');
				varosAutocomplete('#SzamlaIrszamEdit','#SzamlaVarosEdit');
				irszamAutocomplete('#SzallIrszamEdit','#SzallVarosEdit');
				varosAutocomplete('#SzallIrszamEdit','#SzallVarosEdit');
			},
			beforeSerialize:function(form,opt) {
				var cimkek=new Array();
				$('.js-cimkekarb').filter('.js-selectedcimke').each(function() {
					cimkek.push($(this).attr('data-id'));
				});
				var x={};
				x['cimkek[]']=cimkek;
				opt['data']=x;
			},
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
			name:'partner',
			filter:{
				fields:[
                    '#nevfilter',
                    '#emailfilter',
                    '#szallitasiirszamfilter',
                    '#szallitasivarosfilter',
                    '#szallitasiutcafilter',
                    '#szamlazasiirszamfilter',
                    '#szamlazasivarosfilter',
                    '#szamlazasiutcafilter',
                    '#beszallitofilter'
                ],
				onClear:function() {
					$('.js-cimkefilter').removeClass('ui-state-hover');
				},
				onFilter:function(obj) {
					var cimkek=new Array();
					$('.js-cimkefilter').filter('.ui-state-hover').each(function() {
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
			karb:partner
		});
        $('#cimkefiltercontainer').mattaccord({
            header: '#cimkefiltercontainerhead',
            page: '.accordpage',
            closeUp: '.js-cimkefiltercloseupbutton',
            collapse: '#cimkefiltercollapse'
        });
        $('.js-cimkefilter').on('click', function(e) {
            e.preventDefault();
            $(this).toggleClass('ui-state-hover');
        });
		$('.js-maincheckbox').change(function(){
			$('.js-egyedcheckbox').prop('checked',$(this).prop('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},partner,{independent:true}));
		}
	}
});