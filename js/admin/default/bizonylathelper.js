function setDates() {
	var keltedit=$('#KeltEdit'),
		teljedit=$('#TeljesitesEdit'),
		esededit=$('#EsedekessegEdit'),
		fizmodedit=$('#FizmodEdit option:selected'),
		partneredit=$('#PartnerEdit option:selected'),
		hatido=0,
		kezddatum;
	if (fizmodedit.data('bank')=='1') {
		hatido=partneredit.data('fizhatido')*1;
		if (hatido==0) {
			hatido=fizmodedit.data('fizhatido')*1;
		}
	}
	if (esededit.data('alap')=='1') {
		kezddatum=keltedit.datepicker('getDate');
	}
	else {
		kezddatum=teljedit.datepicker('getDate');
	}
	kezddatum.setDate(kezddatum.getDate()+hatido);
	esededit.datepicker('setDate',kezddatum);
}

function getArfolyam() {
	var d=$('#TeljesitesEdit').datepicker('getDate');
	if (!d) {
		d=$('#KeltEdit').datepicker('getDate');
	}
	$.ajax({
		url:'/admin/arfolyam/getarfolyam',
		data:{
			valutanem:$('#ValutanemEdit').val(),
			datum:d.getFullYear()+'.'+(d.getMonth()+1)+'.'+d.getDate()
		},
		success:function(data) {
			$('#ArfolyamEdit').val(data);
		}
	});
}

function setTermekAr(sorId) {
	$.ajax({
		url:'/admin/bizonylattetel/getar',
		data:{
			valutanem:$('#ValutanemEdit').val(),
			partner:$('#PartnerEdit').val(),
			termek:$('input[name="teteltermek_'+sorId+'"]').val(),
			valtozat:$('select[name="tetelvaltozat_'+sorId+'"]').val()
		},
		success:function(data) {
			var c=$('input[name="tetelnettoegysar_'+sorId+'"]');
			c.val(data);
			c.change();
		}
	});
}

function calcArak(sorId) {
	$.ajax({
		url:'/admin/bizonylattetel/calcar',
		data:{
			valutanem:$('#ValutanemEdit').val(),
			arfolyam:$('#ArfolyamEdit').val(),
			afa:$('select[name="tetelafa_'+sorId+'"]').val(),
			nettoegysar:$('input[name="tetelnettoegysar_'+sorId+'"]').val(),
			mennyiseg:$('input[name="tetelmennyiseg_'+sorId+'"]').val()
		},
		success:function(data) {
			var resp=JSON.parse(data);
			$('input[name="tetelnettoegysar_'+sorId+'"]').val(resp.nettoegysar);
			$('input[name="tetelbruttoegysar_'+sorId+'"]').val(resp.bruttoegysar);
			$('input[name="tetelnetto_'+sorId+'"]').val(resp.netto);
			$('input[name="tetelbrutto_'+sorId+'"]').val(resp.brutto);
			$('input[name="tetelnettoegysarhuf_'+sorId+'"]').val(resp.nettoegysarhuf);
			$('input[name="tetelbruttoegysarhuf_'+sorId+'"]').val(resp.bruttoegysarhuf);
			$('input[name="tetelnettohuf_'+sorId+'"]').val(resp.nettohuf);
			$('input[name="tetelbruttohuf_'+sorId+'"]').val(resp.bruttohuf);

		}
	});
}