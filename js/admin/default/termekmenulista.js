$(document).ready(function () {
    var dialogcenter = $('#dialogcenter');

    function _new_edit(uj) {
        var valasztottid = $('#termekmenu').jstree('get_selected').children('a').attr('id'),
            scrollPosition;
        if (!valasztottid) {
            if (uj) {
                dialogcenter.html('Válasszon szülő menüt').dialog({
                    resizable: false, modal: true, buttons: {
                        'OK': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            } else {
                dialogcenter.html('Válasszon menüt').dialog({
                    resizable: false, modal: true, buttons: {
                        'OK': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            }
            return false;
        }
        if (uj) {
            adat = {
                parentid: valasztottid.split('_')[1],
                oper: 'add'
            };
        } else {
            adat = {
                id: valasztottid.split('_')[1],
                oper: 'edit'
            };
        }
        $.ajax({
            url: '/admin/termekmenu/getkarb',
            data: adat,
            success: function (data) {
                scrollPosition = $(document).scrollTop();
                $(document).scrollTop(0);
                $('#termekmenu').hide();
                $('#termekmenukarb').append(data);
                var karbsetup = {
                    name: '',
                    independent: false,
                    header: '#menukarb-header',
                    form: '#menukarb-form',
                    tab: '#menukarb-tabs',
                    cancel: '#menukarb-cancelbutton',
                    ok: '#menukarb-okbutton',
                    viewUrl: '/admin/termekmenu/getkarb',
                    saveUrl: '/admin/termekmenu/save',
                    beforeShow: function () {
                        if (!$.browser.mobile) {
                            CKFinder.setupCKEditor(null, '/ckfinder/');
                            $('#LeirasEdit').ckeditor();
                            $('#Leiras2Edit').ckeditor();
                            $('#Leiras3Edit').ckeditor();
                        }
                        $('#AltalanosTab').on('click', '#KepDelButton', function (e) {
                            e.preventDefault();
                            dialogcenter.html('Biztos, hogy törli a képet?').dialog({
                                resizable: false,
                                height: 140,
                                modal: true,
                                buttons: {
                                    'Igen': function () {
                                        var kep = $('.js-termekmenukep');
                                        $('#KepUrlEdit').val('');
                                        $('#KepLeirasEdit').val('');
                                        kep.attr('src', '/');
                                        kep.parent().attr('href', '');
                                        $(this).dialog('close');
                                    },
                                    'Nem': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        })
                            .on('click', '#KepBrowseButton', function (e) {
                                e.preventDefault();
                                var finder = new CKFinder(),
                                    $kepurl = $('#KepUrlEdit'),
                                    path = $kepurl.val();
                                if (path) {
                                    finder.startupPath = 'Images:' + path.substring(path.indexOf('/', 1));
                                }
                                finder.selectActionFunction = function (fileUrl, data) {
                                    var kep = $('.js-termekmenukep');
                                    $.ajax({
                                        url: '/admin/getsmallurl',
                                        type: 'GET',
                                        data: {
                                            url: fileUrl
                                        },
                                        success: function (data) {
                                            $kepurl.val(fileUrl);
                                            kep.attr('src', data);
                                            kep.parent().attr('href', fileUrl);
                                        }
                                    });
                                };
                                finder.popup();
                            });
                        $('#KepDelButton,#KepBrowseButton').button();
                        $('#TranslationTab').on('click', '.js-translationnewbutton', function (e) {
                            var $this = $(this);
                            e.preventDefault();
                            $.ajax({
                                url: '/admin/termekmenutranslation/getemptyrow',
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
                                } else {
                                    dialogcenter.html('Biztos, hogy törli a fordítást?').dialog({
                                        resizable: false,
                                        height: 140,
                                        modal: true,
                                        buttons: {
                                            'Igen': function () {
                                                $.ajax({
                                                    url: '/admin/termekmenutranslation/save',
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
                        if (!$.browser.mobile) {
                            $('.js-toflyout').flyout();
                        }
                    },
                    beforeHide: function () {
                        if (!$.browser.mobile) {
                            editor = $('#LeirasEdit').ckeditorGet();
                            if (editor) {
                                editor.destroy();
                            }
                            editor = $('#Leiras2Edit').ckeditorGet();
                            if (editor) {
                                editor.destroy();
                            }
                            editor = $('#Leiras3Edit').ckeditorGet();
                            if (editor) {
                                editor.destroy();
                            }
                        }
                    },
                    onSubmit: function (data) {
                        $('#termekmenukarb').empty().hide();
                        $('#termekmenu').jstree('refresh');
                        $('#termekmenu').show();
                        $(document).scrollTop(scrollPosition);
                        alert('Ne felejtse el a termék kategóriák rendezését!');
                    },
                    onCancel: function () {
                        $('#termekmenukarb').empty().hide();
                        $('#termekmenu').show();
                        $(document).scrollTop(scrollPosition);
                    }
                };
                $('#termekmenukarb').mattkarb(karbsetup);
            }
        });
    };

    $('#termekmenu')
        .bind('loaded.jstree refresh.jstree', function (e, d) {
            d.inst.open_all();
        })
        .jstree({
            core: {animation: 100},
            plugins: ['themeroller', 'json_data', 'contextmenu', 'ui', 'checkbox'],
            themeroller: {item: ''},
            json_data: {
                ajax: {url: '/admin/termekmenu/jsonlist'}
            },
            ui: {select_limit: 1},
            contextmenu: {
                select_node: true,
                items: {
                    create: false, rename: false, remove: false, ccp: false,
                    _new: {
                        label: 'Új',
                        action: function (obj) {
                            _new_edit(true);
                        }
                    },
                    _edit: {
                        label: 'Szerkeszt',
                        action: function (obj) {
                            _new_edit(false);
                        }
                    },
                    _del: {
                        label: 'Töröl',
                        action: function (obj) {
                            var valasztottid = $('#termekmenu').jstree('get_selected').children('a').attr('id');
                            if (!valasztottid) {
                                dialogcenter.html('Válasszon kategóriát').dialog({
                                    resizable: false, modal: true, buttons: {
                                        'OK': function () {
                                            $(this).dialog('close');
                                        }
                                    }
                                });
                                return false;
                            }
                            $.ajax({
                                url: '/admin/termekmenu/isdeletable',
                                data: {
                                    id: valasztottid.split('_')[1]
                                },
                                success: function (data) {
                                    if (data === '1') {
                                        dialogcenter.html('Biztosan törli a kategóriát?').dialog({
                                            modal: true,
                                            buttons: {
                                                'Igen': function () {
                                                    $(this).dialog('close');
                                                    $.ajax({
                                                        url: '/admin/termekmenu/save',
                                                        type: 'POST',
                                                        data: {
                                                            id: valasztottid.split('_')[1],
                                                            oper: 'del'
                                                        },
                                                        success: function (data) {
                                                            $('#termekmenu').jstree('refresh');
                                                            alert('Ne felejtse el a termék kategóriák rendezését!');
                                                        }
                                                    });
                                                },
                                                'Nem': function () {
                                                    $(this).dialog('close');
                                                }
                                            }
                                        });
                                    } else {
                                        dialogcenter.html('A kategória nem törölhető.').dialog({
                                            modal: true, buttons: {
                                                'OK': function () {
                                                    $(this).dialog('close');
                                                }
                                            }
                                        });
                                    }
                                }
                            });
                        }
                    },
                    _move: {
                        label: 'Áthelyez',
                        action: function (obj) {
                            var valasztottid = $('#termekmenu').jstree('get_selected').children('a').attr('id');
                            if (!valasztottid) {
                                dialogcenter.html('Válasszon kategóriát').dialog({
                                    resizable: false, modal: true, buttons: {
                                        'OK': function () {
                                            $(this).dialog('close');
                                        }
                                    }
                                });
                                return false;
                            }
                            dialogcenter.jstree({
                                core: {animation: 100},
                                plugins: ['themeroller', 'json_data', 'ui'],
                                themeroller: {item: ''},
                                json_data: {
                                    ajax: {url: '/admin/termekmenu/jsonlist'}
                                },
                                ui: {select_limit: 1}
                            })
                                .bind('loaded.jstree', function (event, data) {
                                    dialogcenter.jstree('open_node', $('#termekmenu_1', dialogcenter).parent());
                                });
                            dialogcenter.dialog({
                                resizable: true,
                                height: 340,
                                modal: true,
                                buttons: {
                                    'OK': function () {
                                        var ideid = dialogcenter.jstree('get_selected').children('a').attr('id'),
                                            $thisdialog = $(this);
                                        $.ajax({
                                            url: '/admin/termekmenu/move',
                                            type: 'POST',
                                            data: {
                                                eztid: valasztottid.split('_')[1],
                                                ideid: ideid.split('_')[1]
                                            },
                                            success: function (data) {
                                                $('#termekmenu').jstree('refresh');
                                                $thisdialog.dialog('close');
                                                alert('Ne felejtse el a termék kategóriák rendezését!');
                                            }
                                        });
                                    },
                                    'Bezár': function () {
                                        $(this).dialog('close');
                                    }
                                }
                            });
                        }
                    }
                }
            }
        })
        .bind('change_state.jstree', function (e, data) {
            $termekmenu = $(this);
            $('li', $termekmenu).each(function (i) {
                $this = $(this);
                if ($this.hasClass('jstree-unchecked')) {
                    $('ins.jstree-checkbox', $this).removeClass('ui-icon ui-icon-circle-check ui-icon-check');
                } else if ($this.hasClass('jstree-checked')) {
                    $('ins.jstree-checkbox', $this).removeClass('ui-icon ui-icon-circle-check ui-icon-check').addClass('ui-icon ui-icon-circle-check');
                } else if ($this.hasClass('jstree-undetermined')) {
                    $('ins.jstree-checkbox', $this).removeClass('ui-icon ui-icon-circle-check ui-icon-check').addClass('ui-icon ui-icon-check');
                }
            });
        });
});