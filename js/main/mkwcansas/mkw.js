var mkw=function($) {

	function showMessage(msg) {
		var msgcenter=$('#messagecenter');
		msgcenter.text(msg);
		$.magnificPopup.open({
			modal: true,
			items: [
				{
					src: msgcenter,
					type: 'inline'
				}
			]
		});
	}

	function closeMessage() {
		$.magnificPopup.close();
	}

	function showDialog(msg,options) {
		var dlgcenter=$('#dialogcenter'),
			dlgheader=$('.modal-header',dlgcenter),
			dlgbody=$('.modal-body',dlgcenter).empty(),
			dlgfooter=$('.modal-footer',dlgcenter).empty(),
			classes='btn';
		$('h4',dlgheader).remove();
		opts=$.extend(null,options,{
			header: 'Értesítés',
			buttons: [{
					caption: 'OK',
					class: 'okbtn',
					click: function(e) {
						e.preventDefault();
						closeDialog();
					}
			}]
		});
		if (opts.header) {
			dlgheader.append('<h4>'+opts.header+'</h4>');
		}
		if (msg) {
			dlgbody.append('<p>'+msg+'</p>');
		}
		for(var i=0;i<opts.buttons.length;i++) {
			if (opts.buttons[i].class) {
				classes=classes+' '+opts.buttons[i].class;
			}
			$('<button class="'+classes+'">'+opts.buttons[i].caption+'</button>')
				.appendTo(dlgfooter)
				.on('click',opts.buttons[i].click);
		}
		dlgcenter.modal();
	}

	function closeDialog() {
		var dlgcenter=$('#dialogcenter');
		dlgcenter.modal('hide');
	}

	function lapozas() {
		var lf=$('.lapozoform'),
			url=lf.data('url')+'?pageno='+lf.data('pageno'),
			filterstr='';
		url=url+'&elemperpage='+$('.elemperpageedit').val()+'&order='+$('.orderedit').val();
		$('#szuroform input:checkbox:checked').each(function(){
			filterstr=filterstr+$(this).prop('name')+',';
		});
		if (filterstr!=='') {
			url=url+'&filter='+filterstr;
		}
		url=url+'&arfilter='+$('#ArSlider').val();
		url=url+'&keresett='+$('.KeresettEdit').val();
		url=url+'&vt='+$('#ListviewEdit').val();
		document.location=url;
	}

	function overrideFormSubmit(form,msg,events) {
		var $form=form;
		if (!events) {
			events={};
		}
		if (typeof form == 'string') {
			$form=$(form);
		}
		$form.on('submit',function(e) {
			e.preventDefault();
			var data={jax: 1};
			$form.find('input').each(function() {
				var $this=$(this);
				switch ($this.attr('type')) {
					case 'checkbox':
						data[$this.attr('name')]=$this.prop('checked');
						break;
					default:
						data[$this.attr('name')]=$this.val();
						break;
				}
			});
			$.ajax({
				url: $form.attr('action'),
				type: 'POST',
				data: data,
				beforeSend:function(x) {
					if (msg) {
						showMessage(msg);
					}
				},
				complete:function(xhr,status) {
					if (msg) {
						closeMessage();
					}
					if (typeof events.complete == 'function') {
						events.complete.apply($form,xhr,status);
					}
				},
				error:function(xhr,status,error) {
					if (typeof events.error == 'function') {
						events.error.apply($form,xhr,status);
					}
				},
				success:function(data,status,xhr) {
					if (typeof events.success == 'function') {
						events.success.apply($form,data,status,xhr);
					}
				}
			});
		});

	}

	function irszamTypeahead(irszaminput,varosinput) {
		if ($.fn.typeahead) {
			var map={};
			$(irszaminput).typeahead({
				source: function(query, process) {
					var	texts=[];
					return $.ajax({
						url: '/irszam',
						type: 'GET',
						data: {
							term: query
						},
						success: function(data) {
							var d=JSON.parse(data);
							$.each(d, function (i, irszam) {
								map[irszam.id] = irszam;
								texts.push(irszam.id);
							});
							return process(texts);
						}
					});
				},
				updater: function(item) {
					var irsz = map[item];
					item = irsz.szam;
					$(varosinput).val(irsz.nev);
					return item;
				},
				items: 999999,
				minLength: 2
			});
		}
	}

	function varosTypeahead(irszaminput,varosinput) {
		if ($.fn.typeahead) {
			var map={};
			$(varosinput).typeahead({
				source: function(query, process) {
					var	texts=[];
					return $.ajax({
						url: '/varos',
						type: 'GET',
						data: {
							term: query
						},
						success: function(data) {
							var d=JSON.parse(data);
							$.each(d, function (i, irszam) {
								map[irszam.id] = irszam;
								texts.push(irszam.id);
							});
							return process(texts);
						}
					});
				},
				updater: function(item) {
					var irsz = map[item];
					item = irsz.nev;
					$(irszaminput).val(irsz.szam);
					return item;
				},
				items: 999999,
				minLength: 4
			});
		}
	}

	return {
		showMessage: showMessage,
		closeMessage: closeMessage,
		showDialog: showDialog,
		closeDialog: closeDialog,
		lapozas: lapozas,
		overrideFormSubmit: overrideFormSubmit,
		irszamTypeahead: irszamTypeahead,
		varosTypeahead: varosTypeahead
	};
}(jQuery);