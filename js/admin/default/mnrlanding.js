$(document).ready(function () {
    var dialogcenter = $('#dialogcenter');

    var mnrlanding = {
        container: '#mattkarb',
        viewUrl: '/admin/mnrlanding/getkarb',
        newWindowUrl: '/admin/mnrlanding/viewkarb',
        saveUrl: '/admin/mnrlanding/save',
        beforeShow: function () {
            var translationtab = $('#TranslationTab');
            var altalanostab = $('#AltalanosTab');
            altalanostab.on('click', '#FoKepDelButton', function (e) {
                    e.preventDefault();
                    dialogcenter.html('Biztos, hogy törli a képet?').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                $('#KepUrlEdit').val('');
                                $('#KepLeirasEdit').val('');
                                $(this).dialog('close');
                            },
                            'Nem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                })
                .on('click', '#FoKepBrowseButton', function (e) {
                    e.preventDefault();
                    var finder = new CKFinder(),
                        $kepurl = $('#KepUrlEdit'),
                        path = $kepurl.val();
                    if (path) {
                        finder.startupPath = 'Images:' + path.substring(path.indexOf('/', 1));
                    }
                    finder.selectActionFunction = function (fileUrl, data) {
                        $kepurl.val(fileUrl);
                    };
                    finder.popup();
                });
            $('#FoKepDelButton,#FoKepBrowseButton').button();
            translationtab.on('click', '.js-translationnewbutton', function (e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/mnrlandingtranslation/getemptyrow',
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
                                        url: '/admin/mnrlandingtranslation/save',
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
            $('.js-kepbrowsebutton').button();

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
            name: 'mnrlanding',
            onGetTBody: function () {
                if (!$.browser.mobile) {
                    $('.js-toflyout').flyout();
                }
            },
            tablebody: {
                url: '/admin/mnrlanding/getlistbody',
                onStyle: function() {
                },
                onDoEditLink: function() {
                }
            },
            karb: mnrlanding
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    }
    else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, mnrlanding, {independent: true}));
        }
    }
});