$(document).ready(function(){
    var dialogcenter = $('#dialogcenter');
	var statlap={
			container:'#mattkarb',
			viewUrl:'/admin/statlap/getkarb',
			newWindowUrl:'/admin/statlap/viewkarb',
			saveUrl:'/admin/statlap/save',
			beforeShow:function() {
                var translationtab = $('#TranslationTab');
                translationtab.on('click', '.js-translationnewbutton', function (e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/statlaptranslation/getemptyrow',
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
                                            url: '/admin/statlaptranslation/save',
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
                    })
                    .on('change', '.js-fieldselect', function(e) {
                        var $this = $(this),
                            x = $('option:selected', $this).val(),
                            editor;
                        if (!$.browser.mobile) {
                            if (x === 'szoveg') {
                                editor = $('.js-contenteditor_' + $this.data('id'));
                                editor.addClass('js-ckeditor');
                                editor.ckeditor();
                            }
                            else {
                                editor = $('.js-contenteditor_' + $this.data('id'));
                                if (editor && editor.hasClass('js-ckeditor')) {
                                    editor.removeClass('js-ckeditor');
                                    editor = editor.ckeditorGet();
                                    editor.destroy();
                                }
                            }
                        }
                    });
                $('.js-translationnewbutton,.js-translationdelbutton').button();
				if (!$.browser.mobile) {
					CKFinder.setupCKEditor( null, '/ckfinder/' );
					$('#LeirasEdit').ckeditor();
                    $('.js-ckeditor').each(function() {
                        $(this).ckeditor();
                    });
				}
			},
			beforeHide:function() {
                var editor;
				if (!$.browser.mobile) {
					editor=$('#LeirasEdit').ckeditorGet();
					if (editor) {
						editor.destroy();
					}
                    $('.js-ckeditor').each(function() {
                        editor = $(this).ckeditorGet();
                        if (editor) {
                            editor.destroy();
                        }
                    });
				}
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
				fields:['#nevfilter']
			},
			tablebody:{
				url:'/admin/statlap/getlistbody'
			},
			karb:statlap
		});

		$('.js-maincheckbox').change(function(){
			$('.js-egyedcheckbox').prop('checked',$(this).prop('checked'));
		});
	}
	else {
		if ($.fn.mattkarb) {
			$('#mattkarb').mattkarb($.extend({},statlap,{independent:true}));
		}
	}
});