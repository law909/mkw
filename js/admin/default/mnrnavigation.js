$(document).ready(function () {
    var dialogcenter = $('#dialogcenter');

    var mnrnavigation = {
        container: '#mattkarb',
        viewUrl: '/admin/mnrnavigation/getkarb',
        newWindowUrl: '/admin/mnrnavigation/viewkarb',
        saveUrl: '/admin/mnrnavigation/save',
        beforeShow: function () {
            var translationtab = $('#TranslationTab');
            var mnrstaticpagetab = $('#MNRStaticPageTab');
            var altalanostab = $('#AltalanosTab');
            translationtab.on('click', '.js-translationnewbutton', function (e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/mnrnavigationtranslation/getemptyrow',
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
                                        url: '/admin/mnrnavigationtranslation/save',
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
                        if (x === 'leiras') {
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
        },
        beforeSerialize: function (form, opt) {
            return true;
        },
        beforeHide: function () {
        },
        onSubmit: function () {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click', messagecenterclick)
                .slideToggle('slow');
            //setTimeout('$("#messagecenter").unbind(messagecenterclick).slideToggle("slow");',5000);
        }
    };

    if ($.fn.mattable) {
        $('#mattable-select').mattable({
            name: 'mnrnavigation',
            onGetTBody: function () {
                if (!$.browser.mobile) {
                    $('.js-toflyout').flyout();
                }
            },
            tablebody: {
                url: '/admin/mnrnavigation/getlistbody',
                onStyle: function() {
                },
                onDoEditLink: function() {
                }
            },
            karb: mnrnavigation
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    }
    else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, mnrnavigation, {independent: true}));
        }
    }
});