$(document).ready(function(){
    var dialogcenter = $('#dialogcenter');
	var mattkarbconfig={
			container:'#mattkarb',
			viewUrl:'/admin/szallitasimod/getkarb',
			newWindowUrl:'/admin/szallitasimod/viewkarb',
			saveUrl:'/admin/szallitasimod/save',
			onSubmit:function() {
				$('#messagecenter')
					.html('A mentés sikerült.')
					.hide()
					.addClass('matt-messagecenter ui-widget ui-state-highlight')
					.one('click',messagecenterclick)
					.slideToggle('slow');
			},
			beforeShow: function() {
                var translationtab = $('#TranslationTab');
                translationtab.on('click', '.js-translationnewbutton', function (e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/szallitasimodtranslation/getemptyrow',
                        type: 'GET',
                        success: function (data) {
                            var tbody = $('#TranslationTab');
                            tbody.append(data);
                            $('.js-translationnewbutton,.js-translationdelbutton').button();
                            $this.remove();
                        }
                    });
                })
                    .on('click', '.js-translationdelbutton', function (e) {
                        e.preventDefault();
                        var translationgomb = $(this),
                            translationid = translationgomb.attr('data-id'),
                            egyedid = translationgomb.attr('data-egyedid');
                        if (translationgomb.attr('data-source') === 'client') {
                            $('#translationtable_' + translationid).remove();
                        }
                        else {
                            dialogcenter.html('Biztos, hogy törli a fordítást?').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'Igen': function () {
                                        $.ajax({
                                            url: '/admin/szallitasimodtranslation/save',
                                            type: 'POST',
                                            data: {
                                                id: translationid,
                                                egyedid: egyedid,
                                                oper: 'del'
                                            },
                                            success: function (data) {
                                                $('#translationtable_' + data).remove();
                                            }
                                        });
                                        $(this).dialog('close');
                                    },
                                    'Nem': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                    });
                $('.js-translationnewbutton,.js-translationdelbutton').button();
				$('#HatarTab').on('click', '.js-hatarnewbutton', function(e) {
					var $this = $(this);
					e.preventDefault();
					$.ajax({
						url: '/admin/szallitasimodhatar/getemptyrow',
						type: 'GET',
						success: function(data) {
							var tbody = $('#HatarTab');
							tbody.append(data);
							$('.js-hatarnewbutton,.js-hatardelbutton').button();
							$this.remove();
						}
					});
				})
					.on('click', '.js-hatardelbutton', function(e) {
						e.preventDefault();
						var hatargomb = $(this),
							hatarid = hatargomb.attr('data-id');
						if (hatargomb.attr('data-source') === 'client') {
							$('#hatartable_' + hatarid).remove();
						}
						else {
							dialogcenter.html('Biztos, hogy törli a határértéket?').dialog({
								resizable: false,
								height: 140,
								modal: true,
								buttons: {
									'Igen': function() {
										$.ajax({
											url: '/admin/szallitasimodhatar/save',
											type: 'POST',
											data: {
												id: hatarid,
												oper: 'del'
											},
											success: function(data) {
												$('#hatartable_' + data).remove();
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
					});
				$('.js-hatarnewbutton,.js-hatardelbutton').button();
			}
	};

	if ($.fn.mattable) {
		$('#mattable-select').mattable({
			filter:{
				fields:['#nevfilter']
			},
			tablebody:{
				url:'/admin/szallitasimod/getlistbody'
			},
			karb:mattkarbconfig
		});
		$('#maincheckbox').change(function(){
			$('.egyedcheckbox').attr('checked',$(this).attr('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},mattkarbconfig,{independent:true}));
		}
	}
});