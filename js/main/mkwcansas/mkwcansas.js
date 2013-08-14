
$(document).ready(function(){

	var $termekertesitomodal=$('#termekertesitoModal'),
		$termekertesitoform=$('#termekertesitoform');

	if ($.fn.mattaccord) {
		$(document).mattaccord();
	}
	if ($.fn.tab) {
		$('#termekTabbable').tab('show');
		$('#adamodositasTabbable').tab('show');
	}
	if ($.fn.slider) {
		var $arslider=$('#ArSlider');
		if ($arslider.length>0) {
			var	maxar=$arslider.data('maxar')*1,
				ti=$arslider.attr('value'),
				step=$arslider.data('step')*1;
			$arslider.slider({
				from: 0,
				to: maxar+step,
				step: step,
				dimension: '&nbsp;Ft',
				skin: 'mkwcansas',
				callback: function(value) {
					mkw.lapozas();
				}
			});
			$arslider.slider('value',ti.split(';')[0],ti.split(';')[1]);
		}
	}
	if ($.fn.typeahead) {
		$('#searchinput').typeahead({
			source: function(query, process) {
				return $.ajax({
					url: '/kereses',
					type: 'GET',
					data: {
						term: query
					},
					success: function(data) {
						var d=JSON.parse(data);
						return process(d);
					}
				});
			},
			onselect: function() {
				$('#searchform').submit();
			},
			items: 999999,
			minLength: 4
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
	$termekertesitomodal.modal({
		show:false
	});
	$('.js-termekertesitobtn').on('click',function() {
		$termekertesitoform.find('input[name="termekid"]').val($(this).data('termek'));
		$termekertesitomodal.modal('show');
		return false;
	});
	mkw.overrideFormSubmit($termekertesitoform,false,{
		complete: function(){
			$termekertesitoform.find('input[name="termekid"]').val('');
			$termekertesitomodal.modal('hide');
		}
	});
	$('.js-termekertesitomodalok').on('click',function(e) {
		e.preventDefault();
		$termekertesitoform.submit();
	});
	$('.js-termekertesitodel').on('click',function(e) {
		var $this=$(this);
		e.preventDefault();
		$.ajax({
			url:'/termekertesito/save',
			type:'POST',
			data:{
				oper: 'del',
				id: $this.data('id')
			},
			beforeSend:function() {
				mkw.showMessage('A leíratkozás folyamatban van.');
			},
			success:function() {
				$this.parents('div.js-termekertesito').remove();
			},
			complete:function() {
				mkw.closeMessage();
			}
		});
	});
	if ($.fn.magnificPopup) {
		$('.js-lightbox').magnificPopup({
			gallery: {
				enabled: true
			},
			image: {
				cursor: null
			},
			type: 'image'
		});
	}
	// nincs valtozat
	$('.js-kosarba').on('click',function(e){
		var $this=$(this);
		e.preventDefault();
		$.ajax({
			type:'POST',
			url:$this.attr('href'),
			data:{
				jax:1
			},
			beforeSend:function(x) {
				mkw.showMessage('A terméket a kosarába rakjuk...');
			}
		})
		.done(function(data) {
			$('#minikosar').html(data);
		})
		.always(function() {
			mkw.closeMessage();
		});
	});
	// lathato valtozat van
	$('.js-kosarbavaltozat').on('click',function(e){
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
				mkw.showMessage('A terméket a kosarába rakjuk...');
			}
		})
		.done(function(data) {
			$('.valtozatEdit[data-id="'+id+'"]').selectedIndex=0;
			$('#minikosar').html(data);
		})
		.always(function() {
			mkw.closeMessage();
		});
	});
	// valaszthato valtozat van
	$('.js-kosarbamindenvaltozat').on('click',function(e){
		var $this=$(this),
			termekid=$this.attr('data-termek'),
			tipusok=new Array(),ertekek=new Array(),
			valtozatselect=$('.mindenValtozatEdit[data-termek="'+termekid+'"]');

		e.preventDefault();

		valtozatselect.each(function() {
			var $this=$(this);
			if ($this.val()) {
				tipusok.push($this.data('tipusid'));
				ertekek.push($this.val());
			}
		});

		if (valtozatselect.length!==ertekek.length) {
			mkw.showDialog('Válassza ki, pontosan milyen terméket szeretne.');
		}
		else {
			$.ajax({
				type:'POST',
				url:$this.attr('href'),
				data:{
					jax:3,
					tip:tipusok,
					val:ertekek
				},
				beforeSend:function(x) {
					mkw.showMessage('A terméket a kosarába rakjuk...');
				}
			})
			.done(function(data) {
				$('.mindenValtozatEdit[data-termek="'+termekid+'"]').selectedIndex=0;
				$('#minikosar').html(data);
			})
			.always(function() {
				mkw.closeMessage();
			});
		}
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
			$('.js-kosarbavaltozat[data-id="'+id+'"]').attr('data-vid',$this.val());
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
				mkwcheck.regNevCheck();
				$(this).off('keydown');
			})
			.on('keydown blur',function(e) {mkwcheck.wasinteraction.doublenev=true;mkwcheck.regNevCheck();})
			.each(function(i,ez) {mkwcheck.regNevCheck();});
		$('#EmailEdit')
			.on('input',function(e) {
				mkwcheck.regEmailCheck();
				$(this).off('keydown');
			})
			.on('keydown blur',function(e) {mkwcheck.wasinteraction.email=true;mkwcheck.regEmailCheck();})
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
					mkwcheck.regEmailCheck();
				});
			})
			.each(function(i,ez) {mkwcheck.regEmailCheck();});
		$('#Jelszo1Edit,#Jelszo2Edit')
			.on('input',function(e) {
				mkwcheck.regJelszoCheck();
				$(this).off('keydown');
			})
			.on('keydown blur',function(e) {mkwcheck.wasinteraction.pw=true;mkwcheck.regJelszoCheck();})
			.each(function(i,ez) {mkwcheck.regJelszoCheck();});
	}
	var $kapcsolatform=$('#Kapcsolatform');
	if ($kapcsolatform.length>0) {
		H5F.setup($kapcsolatform);
		$('#NevEdit')
			.on('input',function(e) {
				mkwcheck.kapcsolatNevCheck();
				$(this).off('keydown');
			})
			.on('keydown blur',function(e) {mkwcheck.wasinteraction.nev=true;mkwcheck.kapcsolatNevCheck();})
			.each(function(i,ez) {mkwcheck.kapcsolatNevCheck();});
		$('#Email1Edit,#Email2Edit')
			.on('input',function(e) {
				mkwcheck.kapcsolatEmailCheck();
				$(this).off('keydown');
			})
			.on('change',function(e) {
				var $this=$(this);
				$.ajax({
					type:'POST',
					url:'/checkemail',
					data:{
						email:$this.val(),
						dce: 1
					}
				})
				.done(function(data){
					var d=JSON.parse(data);
					$this.data('hiba',d);
					mkwcheck.kapcsolatEmailCheck();
				});
			})
			.on('keydown blur',function(e) {mkwcheck.wasinteraction.email=true;mkwcheck.kapcsolatEmailCheck();})
			.each(function(i,ez) {mkwcheck.kapcsolatEmailCheck();});
		$('#TemaEdit')
			.on('input',function(e) {
				mkwcheck.kapcsolatTemaCheck();
				$(this).off('keydown');
			})
			.on('keydown blur',function(e) {mkwcheck.wasinteraction.tema=true;mkwcheck.kapcsolatTemaCheck();})
			.each(function(i,ez) {mkwcheck.kapcsolatTemaCheck();});
	}
	var $loginform=$('#Loginform');
	if ($loginform.length>0) {
		H5F.setup($loginform);
		$('#EmailEdit')
			.on('input',function(e) {
				mkwcheck.loginEmailCheck();
				$(this).off('keydown');
			})
			.on('keydown blur',function(e) {mkwcheck.wasinteraction.email=true;mkwcheck.loginEmailCheck();})
			.each(function(i,ez) {mkwcheck.loginEmailCheck();});
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
			if (gy.length>0) {
				document.location=gy.attr('href');
			}
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
		mkw.lapozas();
	});
	$('.orderedit').on('change',function() {
		$('.orderedit').val($(this).val());
		mkw.lapozas();
	});
	$('.pageedit').on('click',function() {
		$('.lapozoform').attr('data-pageno',$(this).attr('data-pageno'));
		mkw.lapozas();
	});
	$('.termeklistview').on('click',function() {
		$('#ListviewEdit').val($(this).attr('data-vt'));
		mkw.lapozas();
	});
	$('#szuroform input').on('change',function(){
		$('.lapozoform input[name="cimkekatid"]').val($(this).attr('name').split('_')[1]);
		mkw.lapozas();
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
				mkw.showMessage('A terméket töröljük a kosarából...');
			}
		})
		.done(function(data) {
			window.location='/kosar/get';
		})
		.always(function() {
			mkw.closeMessage();
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
				mkw.showMessage('A terméket módosítjuk a kosarában...');
			}
		})
		.done(function(data) {
			window.location='/kosar/get';
		})
		.always(function() {
			mkw.closeMessage();
		});
	});
	var $fiokadataimform=$('#FiokAdataim');
	if ($fiokadataimform.length>0) {
		H5F.setup($fiokadataimform);
		$('#VezeteknevEdit,#KeresztnevEdit')
			.on('input',function(e) {
				mkwcheck.regNevCheck();
				$(this).off('keydown');
			})
			.on('keydown blur',function(e) {mkwcheck.wasinteraction.doublenev=true;mkwcheck.regNevCheck();})
			.each(function(i,ez) {mkwcheck.regNevCheck();});
		$('#EmailEdit')
			.on('input',function(e) {
				mkwcheck.regEmailCheck();
				$(this).off('keydown');
			})
			.on('keydown blur',function(e) {mkwcheck.wasinteraction.email=true;mkwcheck.regEmailCheck();})
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
					mkwcheck.regEmailCheck();
				});
			})
			.each(function(i,ez) {mkwcheck.regEmailCheck();});
		mkw.overrideFormSubmit($fiokadataimform,'Adatait módosítjuk...');

	}
	var $fiokszamlaadatok=$('#FiokSzamlaAdatok');
	if ($fiokszamlaadatok.length>0) {
		mkw.irszamTypeahead('input[name="szamlairszam"]', 'input[name="szamlavaros"]');
		mkw.varosTypeahead('input[name="szamlairszam"]', 'input[name="szamlavaros"]');
		mkw.overrideFormSubmit($fiokszamlaadatok,'Adatait módosítjuk...');
	}
	var $fiokszallitasiadatok=$('#FiokSzallitasiAdatok');
	if ($fiokszallitasiadatok.length>0) {
		mkw.irszamTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');
		mkw.varosTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');
		mkw.overrideFormSubmit($fiokszallitasiadatok,'Adatait módosítjuk...');
	}

	$('.js-tooltipbtn').tooltip({
		html: false,
		placement: 'right',
		container: 'body'
	});

	checkout.initUI();
});