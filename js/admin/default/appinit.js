function pleaseWait(msg) {
	if (typeof(msg)!=='string') {
		msg='Kérem várjon...';
	}
	$.blockUI({
		message:msg,
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
}

function messagecenterclick(e){
	e.preventDefault();
	$(this)
		.slideToggle('slow',function(){
			$(this).removeClass('matt-messagecenter ui-widget ui-state-highlight');
			$('#termekkarb').hide();
		});
}

function messagecenterclickonerror(e){
	e.preventDefault();
	$(this)
		.slideToggle('slow',function(){
			$(this).removeClass('matt-messagecenter ui-widget ui-state-error');
		});
}

$(document).ready(
	function(){

		var msgcenter = $('#messagecenter').hide(),
            dialogcenter = $('#dialogcenter');

		$('.menupont').button();
		var menu=$('#menu');
		$('#kozep').css('left',menu.outerWidth()+menu.offset().left*2+'px');
		var menubar=$('.menu-titlebar');
		menubar.addClass('mattedit-titlebar ui-widget-header ui-helper-clearfix ui-corner-all');
		menubar.each(function() {
			$this=$(this);
			var ref=$($this.attr('data-refcontrol'));
			if (ref.attr('data-visible')=='hidden') {
				$this.append('<a href="#" class="mattedit-titlebar-close">'+
					'<span class="ui-icon ui-icon-circle-triangle-s"></span></a>'+
					'<span class="ui-jqgrid-title">'+$this.attr('data-caption')+'</span>');
				ref.hide();
			}
			else {
				$this.append('<a href="#" class="mattedit-titlebar-close">'+
					'<span class="ui-icon ui-icon-circle-triangle-n"></span></a>'+
					'<span class="ui-jqgrid-title">'+$this.attr('data-caption')+'</span>');
			}
		});
		menubar.on('click',function(e) {
			e.preventDefault();
			var ref=$($(this).attr('data-refcontrol'));
			if (ref.attr('data-visible')=='hidden') {
				ref.attr('data-visible','visible');
				ref.slideDown(50);
				$('> a > span',this).removeClass('ui-icon-circle-triangle-s').addClass('ui-icon-circle-triangle-n');
			}
			else {
				ref.attr('data-visible','hidden');
				ref.slideUp(50);
				$('> a > span',this).removeClass('ui-icon-circle-triangle-n').addClass('ui-icon-circle-triangle-s');
			}
		});
		$(document)
				.ajaxStart(pleaseWait)
				.ajaxStop($.unblockUI)
				.ajaxError(function(e, xhr, settings, exception) {
					alert('error in: ' + settings.url + ' \n'+'error:\n' + exception);
				});
		$('#ThemeSelect').change(function(e) {
			$.ajax({url:'/admin/setuitheme',
				data:{uitheme:this.options[this.selectedIndex].value},
				success:function(data) {
					window.location.reload();
				}
			});
		});
		$('.js-regeneratekarkod').on('click', function(e) {
            e.preventDefault();
			$.ajax({
                url:'/admin/regeneratekarkod'
			});
		});

        var $arfdatumedit = $('#ArfolyamDatumEdit');
        if ($arfdatumedit) {
            mkwcomp.datumEdit.init($arfdatumedit);
            $('.js-arfolyamdownload').on('click', function(e) {
                e.preventDefault();
                var arfdatum = $arfdatumedit.datepicker('getDate');
                arfdatum = arfdatum.getFullYear() + '.' + (arfdatum.getMonth() + 1) + '.' + arfdatum.getDate();
                $.ajax({
                    url: '/admin/arfolyam/download',
                    type: 'POST',
                    data: {
                        datum: arfdatum
                    },
                    success: function() {
                        dialogcenter.html('Az árfolyamok letöltése sikerült.').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function() {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                })
            });
        }

        var $napijelentesdatumedit = $('#NapijelentesDatumEdit'),
            $napijelentesdatumigedit = $('#NapijelentesDatumigEdit');
        if ($napijelentesdatumedit && $napijelentesdatumigedit) {
            mkwcomp.datumEdit.init($napijelentesdatumedit);
            mkwcomp.datumEdit.init($napijelentesdatumigedit);
            $('.js-napijelentes').on('click', function(e) {
                e.preventDefault();
                var datum = $napijelentesdatumedit.datepicker('getDate'),
                    datumig = $napijelentesdatumigedit.datepicker('getDate');
                datum = datum.getFullYear() + '.' + (datum.getMonth() + 1) + '.' + datum.getDate();
                datumig = datumig.getFullYear() + '.' + (datumig.getMonth() + 1) + '.' + datumig.getDate();
                $.ajax({
                    url: '/admin/napijelentes',
                    type: 'POST',
                    data: {
                        datum: datum,
                        datumig: datumig
                    },
                    success: function(data) {
                        $('.js-napijelentesbody').replaceWith(data);
                    }
                })
            });
        }

        $('.js-nepszerusegclear').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/admin/nepszeruseg/clear',
                type: 'POST',
                success: function(data) {
                    dialogcenter.html('A népszerűség inicializálás sikerült.').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'OK': function() {
                                $(this).dialog('close');
                            }
                        }
                    });
                }
            })
        });

        var $teljesitmenyjelentestoledit = $('#TolEdit'),
            $teljesitmenyjelentesigedit = $('#IgEdit');
        if ($teljesitmenyjelentestoledit && $teljesitmenyjelentesigedit) {
            mkwcomp.datumEdit.init($teljesitmenyjelentestoledit);
            mkwcomp.datumEdit.init($teljesitmenyjelentesigedit);
            $('.js-teljesitmenyjelentes').on('click', function (e) {
                e.preventDefault();
                var tol = $teljesitmenyjelentestoledit.datepicker('getDate'),
                    ig = $teljesitmenyjelentesigedit.datepicker('getDate');
                if (tol) {
                    tol = tol.getFullYear() + '.' + (tol.getMonth() + 1) + '.' + tol.getDate();
                }
                if (ig) {
                    ig = ig.getFullYear() + '.' + (ig.getMonth() + 1) + '.' + ig.getDate();
                }
                $.ajax({
                    url: '/admin/teljesitmenyjelentes',
                    type: 'POST',
                    data: {
                        tol: tol,
                        ig: ig
                    },
                    success: function (data) {
                        $('.js-teljesitmenyjelentesbody').replaceWith(data);
                    }
                })
            });
        }

        $('.js-boltbannincstermekfabutton').on('click', function(e) {
            var edit = $(this),
                input = $('.js-boltbannincstermekfainput');
            e.preventDefault();
            dialogcenter.jstree({
                core: {animation: 100},
                plugins: ['themeroller', 'json_data', 'ui'],
                themeroller: {item: ''},
                json_data: {
                    ajax: {url: '/admin/termekfa/jsonlist'}
                },
                ui: {select_limit: 1}
            })
            .bind('loaded.jstree', function(event, data) {
                dialogcenter.jstree('open_node', $('#termekfa_1', dialogcenter).parent());
            });
            dialogcenter.dialog({
                resizable: true,
                height: 340,
                modal: true,
                buttons: {
                    'Töröl': function() {
                        edit.attr('data-value', 0);
                        $('span', edit).text(edit.attr('data-text'));
                        input.val(0);
                        $(this).dialog('close');
                    },
                    'OK': function() {
                        dialogcenter.jstree('get_selected').each(function() {
                            var treenode = $(this).children('a'),
                                id = treenode.attr('id').split('_')[1];
                            edit.attr('data-value', id);
                            input.val(id);
                            $('span', edit).text(treenode.text());
                        });
                        $(this).dialog('close');
                    },
                    'Bezár': function() {
                        $(this).dialog('close');
                    }
                }
            });
        })
        .button();

        $('.js-backorder').on('click', function(e) {
            e.preventDefault();
            $.ajax({
                url: '/admin/megrendelesfej/backorder',
                type: 'POST',
                data: {
                    id: $(this).data('egyedid')
                },
                success: function(data) {
                    var d = JSON.parse(data);
                    if (d.refresh) {
                        dialogcenter.html('A backorder rendelés elkészült.').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function() {
                                    $('.mattable-tablerefresh').click();
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                    else {
                        dialogcenter.html('A rendelés teljesíthető.').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function() {
                                    $('.mattable-tablerefresh').click();
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                }
            });
        })
        .button();

	}
);