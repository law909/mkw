$(document).ready(function () {
    var dialogcenter = $('#dialogcenter');

    var mnrstatic = {
        container: '#mattkarb',
        viewUrl: '/admin/mnrstatic/getkarb',
        newWindowUrl: '/admin/mnrstatic/viewkarb',
        saveUrl: '/admin/mnrstatic/save',
        beforeShow: function () {
            var translationtab = $('#TranslationTab');
            var mnrstaticpagetab = $('#MNRStaticPageTab');
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
                        url: '/admin/mnrstatictranslation/getemptyrow',
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
                                        url: '/admin/mnrstatictranslation/save',
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
            mnrstaticpagetab.on('click', '.js-mnrstaticpagenewbutton', function (e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/mnrstaticpage/getemptyrow',
                        type: 'GET',
                        data: {
                            mnrstaticid: $this.attr('data-mnrstaticid')
                        },
                        success: function (data) {
                            var tbody = $('#MNRStaticPageTab');
                            tbody.append(data);
                            $('.js-mnrstaticpagenewbutton,.js-mnrstaticpagedelbutton').button();
                            $this.remove();
                        }
                    });
                })
                .on('click', '.js-mnrstaticpagedelbutton', function (e) {
                    e.preventDefault();
                    var gomb = $(this),
                        vid = gomb.attr('data-id');
                    if (gomb.attr('data-source') === 'client') {
                        $('#mnrstatictable_' + vid).remove();
                    }
                    else {
                        dialogcenter.html('Biztos, hogy törli a változatot?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/mnrstaticpage/save',
                                        type: 'POST',
                                        data: {
                                            id: vid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#mnrstaticpagetable_' + data).remove();
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
                .on('click',  '.js-pagetranslationnewbutton', function (e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/mnrstaticpagetranslation/getemptyrow',
                        type: 'GET',
                        success: function (data) {
                            var tbody = mnrstaticpagetab;
                            tbody.append(data);
                            $('.js-pagetranslationnewbutton,.js-pagetranslationdelbutton').button();
                            $this.remove();
                        }
                    });
                })
                .on('click', '.js-pagetranslationdelbutton', function (e) {
                    e.preventDefault();
                    var translationgomb = $(this),
                        translationid = translationgomb.attr('data-id'),
                        egyedid = translationgomb.attr('data-egyedid');
                    if (translationgomb.attr('data-source') === 'client') {
                        $('#pagetranslationtable_' + translationid).remove();
                    }
                    else {
                        dialogcenter.html('Biztos, hogy törli a fordítást?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/mnrstaticpagetranslation/save',
                                        type: 'POST',
                                        data: {
                                            id: translationid,
                                            egyedid: egyedid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#pagetranslationtable_' + data).remove();
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
            $('.js-pagetranslationnewbutton,.js-pagetranslationdelbutton').button();
            $('.js-mnrstaticpagedelallbutton').button().on('click', function (e) {
                var $this = $(this);
                dialogcenter.html('Biztos, hogy törli az összes oldalt?').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'Igen': function () {
//								pleaseWait();
                            $.ajax({
                                url: '/admin/mnrstaticpage/delall',
                                type: 'POST',
                                data: {
                                    mnrstaticid: $this.data('mnrstaticid')
                                },
                                success: function () {
                                    $('.mnrstaticpagetable').remove();
                                }
                            });
                            $(this).dialog('close');
                        },
                        'Nem': function () {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            });
            $('.js-mnrstaticpagenewbutton,.js-mnrstaticpagedelbutton').button();

            if (!$.browser.mobile) {
                CKFinder.setupCKEditor(null, '/ckfinder/');
                $('#LeirasEdit').addClass('js-ckeditor');
                $('#LeirasEdit').ckeditor();
                $('.js-ckeditor').each(function() {
                    $(this).ckeditor();
                });
            }
        },
        beforeSerialize: function (form, opt) {
            return true;
        },
        beforeHide: function () {
            var editor;
            if (!$.browser.mobile) {
                editor = $('#LeirasEdit');
                if (editor && editor.hasClass('js-ckeditor')) {
                    editor = $('#LeirasEdit').ckeditorGet();
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
            name: 'mnrstatic',
            onGetTBody: function () {
                if (!$.browser.mobile) {
                    $('.js-toflyout').flyout();
                }
            },
            tablebody: {
                url: '/admin/mnrstatic/getlistbody',
                onStyle: function() {
                },
                onDoEditLink: function() {
                }
            },
            karb: mnrstatic
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
    }
    else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, mnrstatic, {independent: true}));
        }
    }
});