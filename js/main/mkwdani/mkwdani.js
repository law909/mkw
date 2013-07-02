function lapozas() {
	var lf=$('.lapozoform'),
		url=lf.data('url')+'?pageno='+lf.data('pageno'),
		filterstr='';
	url=url+'&elemperpage='+$('.elemperpageedit').val()+'&order='+$('.orderedit').val();
	$('#szuroform input:checkbox:checked').each(function(){
		filterstr=filterstr+$(this).prop('name')+',';
	});
	if (filterstr!='') {
		url=url+'&filter='+filterstr;
	}
	url=url+'&arfilter='+$('#ArSlider').val();
	url=url+'&keresett='+$('.KeresettEdit').val();
	url=url+'&vt='+$('#ListviewEdit').val();
	document.location=url;
}

var blockuicss={
	border:'none',
	padding:'15px',
	backgroundColor:'#000',
	'-webkit-border-radius':'10px',
	'-moz-border-radius':'10px',
	opacity:.5,
	color:'#fff',
	'font-size':'16px'
}

var regcheck={
	wasinteraction:{
		nev:false,
		email:false,
		pw:false
	},
	nevcheck:function() {
		var vnev=$('#VezeteknevEdit'),
			msg=vnev.data('errormsg'),
			knev=$('#KeresztnevEdit'),
			nevmsg=$('#NevMsg');
		vnev[0].setCustomValidity('');
		knev[0].setCustomValidity('');
		nevmsg.empty();
		if (vnev[0].validity.valueMissing||knev[0].validity.valueMissing) {
			if (regcheck.wasinteraction.nev) {
				nevmsg.append(msg);
			}
			if (vnev[0].validity.valueMissing) {
				vnev[0].setCustomValidity(msg);
			}
			if (knev[0].validity.valueMissing) {
				knev[0].setCustomValidity(msg);
			}
		}
	},
	emailcheck:function() {
		var email=$('#EmailEdit'),
			msg1=email.data('errormsg1'),
			msg2=email.data('errormsg2'),
			emailmsg=$('#EmailMsg'),
			srvhiba=email.data('hiba')||{hibas:false};
		email[0].setCustomValidity('');
		emailmsg.empty();
		if (srvhiba.hibas) {
			emailmsg.append(srvhiba.uzenet);
			email[0].setCustomValidity(srvhiba.uzenet);
			email[0].checkValidity();
		}
		else {
			if (regcheck.wasinteraction.email) {
				email.removeClass('error').addClass('valid');
			}
			if (email[0].validity.valueMissing) {
				if (regcheck.wasinteraction.email) {
					emailmsg.append(msg1);
				}
				email[0].setCustomValidity(msg1);
			}
			else {
				if (email[0].validity.typeMismatch) {
					if (regcheck.wasinteraction.email) {
						emailmsg.append(msg2);
					}
					email[0].setCustomValidity(msg2);
				}
			}
		}
	},
	pwcheck:function() {
		var pw1=$('#Jelszo1Edit'),
			msg1=pw1.data('errormsg1'),
			msg2=pw1.data('errormsg2'),
			pw2=$('#Jelszo2Edit'),
			pwmsg=$('#JelszoMsg');
		pw1[0].setCustomValidity('');
		pw2[0].setCustomValidity('');
		pwmsg.empty();
		if (pw1.val()!=pw2.val()) {
			if (regcheck.wasinteraction.pw) {
				pwmsg.append(msg2);
			}
			pw2[0].setCustomValidity(msg2);
		}
		else {
			if (pw1[0].validity.valueMissing||pw2[0].validity.valueMissing) {
				if (regcheck.wasinteraction.pw) {
					pwmsg.append(msg1);
				}
				if (pw1[0].validity.valueMissing) {
					pw1[0].setCustomValidity(msg1);
				}
				if (pw2[0].validity.valueMissing) {
					pw2[0].setCustomValidity(msg1);
				}
			}
		}
	}
}

var kapcscheck={
	wasinteraction:{
		nev:false,
		email:false,
		tema:false
	},
	nevcheck:function() {
		var vnev=$('#NevEdit'),
			msg=vnev.data('errormsg'),
			nevmsg=$('#NevMsg');
		vnev[0].setCustomValidity('');
		nevmsg.empty();
		if (kapcscheck.wasinteraction.nev) {
			vnev.removeClass('error').addClass('valid');
		}
		if (vnev[0].validity.valueMissing) {
			if (kapcscheck.wasinteraction.nev) {
				nevmsg.append(msg);
			}
			if (vnev[0].validity.valueMissing) {
				vnev[0].setCustomValidity(msg);
			}
		}
	},
	emailcheck:function() {
		var email1=$('#Email1Edit'),
			email1msg=$('#Email1Msg'),
			msg1=email1.data('errormsg1'),
			msg2=email1.data('errormsg2'),
			msg3=email1.data('errormsg3'),
			email2=$('#Email2Edit'),
			email2msg=$('#Email2Msg'),
			srvhiba1=email1.data('hiba')||{hibas:false},
			srvhiba2=email2.data('hiba')||{hibas:false};
		email1[0].setCustomValidity('');
		email2[0].setCustomValidity('');
		email1msg.empty();
		email2msg.empty();
		if (srvhiba1.hibas||srvhiba2.hibas) {
			if (srvhiba1.hibas) {
				email1msg.append(srvhiba1.uzenet);
				email1[0].setCustomValidity(srvhiba1.uzenet);
				email1[0].checkValidity();
			}
			if (srvhiba2.hibas) {
				email2msg.append(srvhiba2.uzenet);
				email2[0].setCustomValidity(srvhiba2.uzenet);
				email2[0].checkValidity();
			}
		}
		else {
			if (kapcscheck.wasinteraction.email) {
				email1.removeClass('error').addClass('valid');
				email2.removeClass('error').addClass('valid');
			}
			if (email1.val()!=email2.val()) {
				if (kapcscheck.wasinteraction.email) {
					email2msg.append(msg3);
				}
				email1[0].setCustomValidity(msg3);
				email2[0].setCustomValidity(msg3);
			}
			else {
				if (email1[0].validity.valueMissing) {
					email1[0].setCustomValidity(msg1);
					if (kapcscheck.wasinteraction.email) {
						email1msg.append(msg1);
					}
				}
				else {
					if (email1[0].validity.typeMismatch) {
						email1[0].setCustomValidity(msg2);
						if (kapcscheck.wasinteraction.email) {
							email1msg.append(msg2);
						}
					}
				}
				if (email2[0].validity.valueMissing) {
					email2[0].setCustomValidity(msg1);
					if (kapcscheck.wasinteraction.email) {
						email2msg.append(msg1);
					}
				}
				else {
					if (email2[0].validity.typeMismatch) {
						email2[0].setCustomValidity(msg2);
						if (kapcscheck.wasinteraction.email) {
							email2msg.append(msg2);
						}
					}
				}
			}
		}
	},
	temacheck:function() {
		var tema=$('#TemaEdit'),
			msg=tema.data('errormsg'),
			temamsg=$('#TemaMsg');
		tema[0].setCustomValidity('');
		temamsg.empty();
		if (kapcscheck.wasinteraction.tema) {
			tema.removeClass('error').addClass('valid');
		}
		if (tema[0].validity.valueMissing) {
			tema[0].setCustomValidity(temamsg);
			if (kapcscheck.wasinteraction.tema) {
				temamsg.append(msg);
			}
		}
	}
};

var logincheck={
	wasinteraction:{
		email:false
	},
	emailcheck:function() {
		var email=$('#EmailEdit'),
			msg1=email.data('errormsg1'),
			msg2=email.data('errormsg2'),
			emailmsg=$('#EmailMsg'),
			srvhiba=email.data('hiba')||{hibas:false};
		email[0].setCustomValidity('');
		emailmsg.empty();
		if (srvhiba.hibas) {
			emailmsg.append(srvhiba.uzenet);
			email[0].setCustomValidity(srvhiba.uzenet);
			email[0].checkValidity();
		}
		else {
			if (logincheck.wasinteraction.email) {
				email.removeClass('error').addClass('valid');
			}
			if (email[0].validity.valueMissing) {
				if (logincheck.wasinteraction.email) {
					emailmsg.append(msg1);
				}
				email[0].setCustomValidity(msg1);
			}
			else {
				if (email[0].validity.typeMismatch) {
					if (logincheck.wasinteraction.email) {
						emailmsg.append(msg2);
					}
					email[0].setCustomValidity(msg2);
				}
			}
		}
	}
}
$(document).ready(function(){
	if ($.fn.mattaccord) {
		$(document).mattaccord();
	}
	if ($.fn.tab) {
		$('#termekTabbable').tab('show');
		$('#adamodositasTabbable').tab('show');
	}
	if ($.fn.slider) {
		var $arslider=$('#ArSlider'),
			maxar=$arslider.data('maxar')*1,
			ti=$arslider.attr('value'),
			step=$arslider.data('step')*1;
		$arslider.slider({
			from: 0,
			to: maxar+step,
			step: step,
			dimension: '&nbsp;Ft',
			skin: 'plastic',
			callback: function(value) {
				lapozas();
			}
		});
		$arslider.slider('value',ti.split(';')[0],ti.split(';')[1]);
	}
	if ($.fn.autocomplete) {
		$('#KeresoEdit').autocomplete({
			source:'/kereses',
			minLength:4,
			select:function(event, ui) {
				$('#KeresoEdit').val(ui.item.value);
				$('#searchform').submit();
			}
		});
	}
	if ($.fn.carousel) {
		$('#maincarousel').carousel();
	}
	if ($.ui.rcarousel) {
		$('#ajanlotttermekslider').rcarousel({
			visible:3,
			step:1,
			width:180,
			height:200,
			margin:10,
			auto:{
				enabled:true
			}
		});
		$('#legnepszerubbtermekslider').rcarousel({
			visible:3,
			step:1,
			width:180,
			height:200,
			margin:10,
			auto:{
				enabled:true
			},
			navigation:{
				next:'#nepszeru-ui-carousel-next',
				prev:'#nepszeru-ui-carousel-prev'
			}
		});
	}
	$('#termekertesitoModal').modal({
		show:false
	});
	// nincs valtozat
	$('.kosarba').on('click',function(e){
		var $this=$(this);
		e.preventDefault();
		$.ajax({
			type:'POST',
			url:$this.attr('href'),
			data:{
				jax:1
			},
			beforeSend:function(x) {
				$.blockUI({
					message:'A terméket a kosarába rakjuk...',
					css:blockuicss
				});
			}
		})
		.done(function(data) {
			$('#minikosar').html(data);
		})
		.always(function() {
			$.unblockUI();
		});
	});
	// lathato valtozat van
	$('.kosarbavaltozat').on('click',function(e){
		var $this=$(this),
			id=$this.attr('data-id');

		e.preventDefault();
		$.ajax({
			type:'POST',
			url:$this.attr('href'),
			data:{
				jax:2,
				vid:$this.attr('data-vid')
			},
			beforeSend:function() {
				$.blockUI({
					message:'A terméket a kosarába rakjuk...',
					css:blockuicss
				});
			}
		})
		.done(function(data) {
			$('.valtozatEdit[data-id="'+id+'"]').selectedIndex=0;
			$('#minikosar').html(data);
		})
		.always(function() {
			$.unblockUI();
		});
	});
	// valaszthato valtozat van
	$('.kosarbamindenvaltozat').on('click',function(e){
		var $this=$(this),
			termekid=$this.attr('data-termek'),
			tipusok=new Array(),ertekek=new Array();

		$('.mindenValtozatEdit[data-termek="'+termekid+'"]').each(function() {
			tipusok.push($(this).data('tipusid'));
			ertekek.push($(this).val());
		});

		e.preventDefault();
		$.ajax({
			type:'POST',
			url:$this.attr('href'),
			data:{
				jax:3,
				tip:tipusok,
				val:ertekek
			},
			beforeSend:function(x) {
				$.blockUI({
					message:'A terméket a kosarába rakjuk...',
					css:blockuicss
				});
			}
		})
		.done(function(data) {
			$('.mindenValtozatEdit[data-termek="'+termekid+'"]').selectedIndex=0;
			$('#minikosar').html(data);
		})
		.always(function() {
			$.unblockUI();
		});
	});

	// valtozat
	$('.valtozatEdit').on('change',function() {
		var $this=$(this),
			termek=$this.data('termek'),
			id=$this.data('id');

		$.ajax({
			url:'/valtozatar',
			data:{
				t:termek,
				vid:$this.val()
			}
		})
		.done(function(data) {
			var d=JSON.parse(data);
			$('#termekprice'+id).text(d['price']);
		})
		.always(function() {
			$('.kosarbavaltozat[data-id="'+id+'"]').attr('data-vid',$this.val());
		});
	});
	$('.mindenValtozatEdit').on('change',function() {
		var $valtedit=$(this),
			tipusid=$valtedit.data('tipusid'),
			termek=$valtedit.data('termek'),
			id=$valtedit.data('id'),
			$masikedit=$('.mindenValtozatEdit[data-termek="'+termek+'"][data-tipusid!="'+tipusid+'"]');

		$.ajax({
			url:'/valtozat',
			data:{
				t:termek,
				ti:tipusid,
				v:$valtedit.val(),
				sel:$masikedit.val(),
				mti:$masikedit.data('tipusid')
			}
		})
		.done(function(data) {
			var d=JSON.parse(data),
				adat=d['adat'];
				sel='';

			$('#termekprice'+id).text(d['price']);
			$('option[value!=""]',$masikedit).remove();
			$.each(adat,function(i,v) {
				if (v['sel']) {
					sel=' selected="selected"';
				}
				else {
					sel='';
				}
				$masikedit.append('<option value="'+v['value']+'"'+sel+'>'+v['value']+'</option>');
			});
		});
	});
	var $regform=$('#Regform');
	if ($regform.length>0) {
		H5F.setup($regform);
		$('#VezeteknevEdit,#KeresztnevEdit')
			.on('input',function(e) {
				regcheck.nevcheck();
				$(this).off('keydown');
			})
			.on('keydown blur',function(e) {regcheck.wasinteraction.nev=true;regcheck.nevcheck()})
			.each(function(i,ez) {regcheck.nevcheck()});
		$('#EmailEdit')
			.on('input',function(e) {
				regcheck.emailcheck();
				$(this).off('keydown');
			})
			.on('keydown blur',function(e) {regcheck.wasinteraction.email=true;regcheck.emailcheck()})
			.on('change',function(e) {
				var $this=$(this);
				$.ajax({
					type:'POST',
					url:'/partner/checkemail',
					data:{email:$this.val()}
				})
				.done(function(data){
					var d=JSON.parse(data);
					$this.data('hiba',d);
					regcheck.emailcheck();
				});
			})
			.each(function(i,ez) {regcheck.emailcheck()});
		$('#Jelszo1Edit,#Jelszo2Edit')
			.on('input',function(e) {
				regcheck.pwcheck();
				$(this).off('keydown');
			})
			.on('keydown blur',function(e) {regcheck.wasinteraction.pw=true;regcheck.pwcheck()})
			.each(function(i,ez) {regcheck.pwcheck()});
	}
	var $kapcsolatform=$('#Kapcsolatform');
	if ($kapcsolatform.length>0) {
		H5F.setup($kapcsolatform);
		$('#NevEdit')
			.on('input',function(e) {
				kapcscheck.nevcheck();
				$(this).off('keydown');
			})
			.on('keydown blur',function(e) {kapcscheck.wasinteraction.nev=true;kapcscheck.nevcheck()})
			.each(function(i,ez) {kapcscheck.nevcheck()});
		$('#Email1Edit,#Email2Edit')
			.on('input',function(e) {
				kapcscheck.emailcheck();
				$(this).off('keydown');
			})
			.on('change',function(e) {
				var $this=$(this);
				$.ajax({
					type:'POST',
					url:'/checkemail',
					data:{email:$this.val()}
				})
				.done(function(data){
					var d=JSON.parse(data);
					$this.data('hiba',d);
					kapcscheck.emailcheck();
				});
			})
			.on('keydown blur',function(e) {kapcscheck.wasinteraction.email=true;kapcscheck.emailcheck()})
			.each(function(i,ez) {kapcscheck.emailcheck()});
		$('#TemaEdit')
			.on('input',function(e) {
				kapcscheck.temacheck();
				$(this).off('keydown');
			})
			.on('keydown blur',function(e) {kapcscheck.wasinteraction.tema=true;kapcscheck.temacheck()})
			.each(function(i,ez) {kapcscheck.temacheck()});
	}
	var $loginform=$('#Loginform');
	if ($loginform.length>0) {
		H5F.setup($loginform);
		$('#EmailEdit')
			.on('input',function(e) {
				logincheck.emailcheck();
				$(this).off('keydown');
			})
			.on('change',function(e) {
				var $this=$(this);
				$.ajax({
					type:'POST',
					url:'/checkemail',
					data:{email:$this.val()}
				})
				.done(function(data){
					var d=JSON.parse(data);
					$this.data('hiba',d);
					logincheck.emailcheck();
				});
			})
			.on('keydown blur',function(e) {logincheck.wasinteraction.email=true;logincheck.emailcheck()})
			.each(function(i,ez) {logincheck.emailcheck()});
	}
	// kategoria navigalas
	var a=$('#navmain li a'),
		b=$('#navmain li .sub');
	$('#navmain li').on('click',function(e) {
		var $this=$(this),
			gy=$this.children('a');
			v=gy.hasClass('active');
		e.preventDefault();
		if (gy.attr('data-cnt')>0) {
			a.removeClass('active');
			b.hide();
			if (!v) {
				gy.addClass('active');
				$this.children('.sub').toggle();
			}
		}
		else {
			document.location=gy.attr('href');
		}
	});
	b.mouseup(function(){
		return false;
	});
	$(document).on('mouseup',function(c) {
		if($(c.target).parent("#navmain li").length==0){
			a.removeClass("active");
			b.hide();
		}
	});
	$('div.kat').on('click',function(e) {
		e.preventDefault();
		document.location=$(this).attr('data-href');
	});
	// lapozo es szuroform
	$('.elemperpageedit').on('change',function() {
		$('.elemperpageedit').val($(this).val());
		lapozas();
	});
	$('.orderedit').on('change',function() {
		$('.orderedit').val($(this).val());
		lapozas();
	});
	$('.pageedit').on('click',function() {
		$('.lapozoform').attr('data-pageno',$(this).attr('data-pageno'));
		lapozas();
	});
	$('.termeklistview').on('click',function() {
		$('#ListviewEdit').val($(this).attr('data-vt'));
		lapozas();
	});
	$('#szuroform input').on('change',function(){
		$('.lapozoform input[name="cimkekatid"]').val($(this).attr('name').split('_')[1]);
		lapozas();
	});
	// kosar
	$('.kosardelbtn').on('click',function(e) {
		var $this=$(this);
		e.preventDefault();
		$.ajax({
			type:'POST',
			url:$this.attr('href'),
			data:{
				jax:1
			},
			beforeSend:function(x) {
				$.blockUI({
					message:'A terméket töröljük a kosarából...',
					css:blockuicss
				});
			}
		})
		.done(function(data) {
			window.location='/kosar/get';
		})
		.always(function() {
			$.unblockUI();
		});
	});
	$('.kosareditbtn').on('click',function(e) {
		var $this=$(this),
			id=$this.attr('data-id');
		e.preventDefault();
		$.ajax({
			type:'POST',
			url:'/kosar/edit',
			data:{
				jax:1,
				id:id,
				mennyiseg:$('#mennyedit_'+id).val()
			},
			beforeSend:function(x) {
				$.blockUI({
					message:'A terméket módosítjuk a kosarában...',
					css:blockuicss
				});
			}
		})
		.done(function(data) {
			window.location='/kosar/get';
		})
		.always(function() {
			$.unblockUI();
		});
	});
	$('.termekertesito').on('click',function() {
		$('#termekertesitoform input[name="termekid"]').val($(this).data('termekid'));
		$('#termekertesitoModal').modal('show');
		return false;
	})
});