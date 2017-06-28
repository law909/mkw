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
            dialogcenter = $('#dialogcenter'),
            kipenztarform = $('#KipenztarForm'),
            bepenztarform = $('#BepenztarForm');

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

        mkwcomp.datumEdit.init('#KiKeltEdit');
        mkwcomp.datumEdit.init('#BeKeltEdit');
        $('.js-kihivatkozottbizonylatbutton, #KiOKButton, #KiCancelButton,'+
            '.js-behivatkozottbizonylatbutton, #BeOKButton, #BeCancelButton').button();


        function clearKipenztarform() {
            mkwcomp.datumEdit.clear('#KiKeltEdit');
            $('#KiPenztarEdit')[0].selectedIndex = 0;
            $('#KiPartnerEdit')[0].selectedIndex = 0;
            $('#KiMegjegyzesEdit').val('');
            $('#KiSzovegEdit').val('');
            $('#KiJogcimEdit')[0].selectedIndex = 0;
            $('#KiHivatkozottBizonylatEdit').val('');
            $('#KiEsedekessegEdit').val('');
            $('#KiOsszegEdit').val('');
        }

        kipenztarform.ajaxForm({
            type: 'POST',
            beforeSerialize: function(form, opt) {
                $.blockUI({
                    message: 'Kérem várjon...',
                    css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#fff'
                    }
                });
                return true;
            },
            success: function(data) {
                clearKipenztarform();
                msgcenter
                    .html('A mentés sikerült.')
                    .hide()
                    .addClass('matt-messagecenter ui-widget ui-state-highlight')
                    .one('click', messagecenterclick)
                    .slideToggle('slow');
            }
        });

        kipenztarform
            .on('change', '#KiPenztarEdit', function(e) {
                var v = $('#KiPenztarEdit option:selected').data('valutanem');
                $('input[name="valutanem"]', kipenztarform).val(v);
            })
            .on('click', '.js-kihivatkozottbizonylatbutton', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '/admin/partner/getkiegyenlitetlenbiz',
                    data: {
                        partner: $('select[name="partner"]', kipenztarform).val(),
                        irany: $('input[name="irany"]', kipenztarform).val()
                    },
                    success: function(d) {
                        var data = JSON.parse(d);
                        dialogcenter.html(data.html);
                        dialogcenter.dialog({
                            resizable: true,
                            height: 340,
                            modal: true,
                            buttons: {
                                'OK': function() {
                                    var sor = $('tr.js-selected', dialogcenter);
                                    $('input[name="tetelhivatkozottbizonylat"]', kipenztarform).val(sor.data('bizszam'));
                                    $('input[name="tetelhivatkozottdatum"]', kipenztarform).val(sor.data('datum'));
                                    $('input[name="tetelosszeg"]', kipenztarform).val(sor.data('egyenleg'));
                                    $(this).dialog('close');
                                },
                                'Bezár': function() {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                });
            })
            .on('click', '#KiCancelButton', function(e) {
                e.preventDefault();
                clearKipenztarform();
            });

        function clearBepenztarform() {
            mkwcomp.datumEdit.clear('#BeKeltEdit');
            $('#BePenztarEdit')[0].selectedIndex = 0;
            $('#BePartnerEdit')[0].selectedIndex = 0;
            $('#BeMegjegyzesEdit').val('');
            $('#BeSzovegEdit').val('');
            $('#BeJogcimEdit')[0].selectedIndex = 0;
            $('#BeHivatkozottBizonylatEdit').val('');
            $('#BeEsedekessegEdit').val('');
            $('#BeOsszegEdit').val('');
        }

        bepenztarform.ajaxForm({
            type: 'POST',
            beforeSerialize: function(form, opt) {
                $.blockUI({
                    message: 'Kérem várjon...',
                    css: {
                        border: 'none',
                        padding: '15px',
                        backgroundColor: '#000',
                        '-webkit-border-radius': '10px',
                        '-moz-border-radius': '10px',
                        opacity: .5,
                        color: '#fff'
                    }
                });
                return true;
            },
            success: function(data) {
                clearBepenztarform();
                msgcenter
                    .html('A mentés sikerült.')
                    .hide()
                    .addClass('matt-messagecenter ui-widget ui-state-highlight')
                    .one('click', messagecenterclick)
                    .slideToggle('slow');
            }
        });
        bepenztarform
            .on('change', '#BePenztarEdit', function(e) {
                var v = $('#BePenztarEdit option:selected').data('valutanem');
                $('input[name="valutanem"]', bepenztarform).val(v);
            })
            .on('click', '.js-behivatkozottbizonylatbutton', function(e) {
                e.preventDefault();

                $.ajax({
                    type: 'POST',
                    url: '/admin/partner/getkiegyenlitetlenbiz',
                    data: {
                        partner: $('select[name="partner"]', bepenztarform).val(),
                        irany: $('input[name="irany"]', bepenztarform).val()
                    },
                    success: function(d) {
                        var data = JSON.parse(d);
                        dialogcenter.html(data.html);
                        dialogcenter.dialog({
                            resizable: true,
                            height: 340,
                            modal: true,
                            buttons: {
                                'OK': function() {
                                    var sor = $('tr.js-selected', dialogcenter);
                                    $('input[name="tetelhivatkozottbizonylat"]', bepenztarform).val(sor.data('bizszam'));
                                    $('input[name="tetelhivatkozottdatum"]', bepenztarform).val(sor.data('datum'));
                                    $('input[name="tetelosszeg"]', bepenztarform).val(sor.data('egyenleg'));
                                    $(this).dialog('close');
                                },
                                'Bezár': function() {
                                    $(this).dialog('close');
                                }
                            }
                        });
                    }
                });
            })
            .on('click', '#BeCancelButton', function(e) {
                e.preventDefault();
                clearBepenztarform();
            });

	}
);