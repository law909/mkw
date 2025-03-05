$(document).ready(function () {
    var dialogcenter = $('#dialogcenter');

    function termekAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/termek/getkapcsolodolist',
            select: function (event, ui) {
                var termek = ui.item;
                if (termek) {
                    var $this = $(this);
                    $this.siblings().val(termek.id);
                }
            }
        };
    }

    function createImageSelectable(n, m) {
        $(n).selectable({
            unselected: function () {
                $('.ui-state-highlight', this).each(function () {
                    var $this = $(this);
                    $this.removeClass('ui-state-highlight');
                    $(m + $this.attr('data-valtozatid')).val('');
                });
            },
            selected: function () {
                $('.ui-selected', this).each(function () {
                    var $this = $(this);
                    $this.addClass('ui-state-highlight');
                    $(m + $this.attr('data-valtozatid')).val($this.attr('data-value'));
                });
            }
        });
    }

    function getSorNetto(o, n) {
        var id = $('#mattkarb-form').attr('data-id');
        var sorid = o.attr('name').split('_')[1] || '';
        $.ajax({
            url: '/admin/termek/getnetto',
            type: 'GET',
            data: {
                id: id,
                value: o.val(),
                afakod: $('#AfaEdit').val()
            },
            success: function (data) {
                $('input[name="' + n + sorid + '"]').val(data);
            }
        });
    }

    function getSorBrutto(o, n) {
        var id = $('#mattkarb-form').attr('data-id');
        var sorid = o.attr('name').split('_')[1] || '';
        $.ajax({
            url: '/admin/termek/getbrutto',
            type: 'GET',
            data: {
                id: id,
                value: o.val(),
                afakod: $('#AfaEdit').val()
            },
            success: function (data) {
                $('input[name="' + n + sorid + '"]').val(data);
            }
        });
    }

    function getNetto(o, n) {
        var id = $('#mattkarb-form').attr('data-id');
        $.ajax({
            url: '/admin/termek/getnetto',
            type: 'GET',
            data: {
                id: id,
                value: o.val(),
                afakod: $('#AfaEdit').val()
            },
            success: function (data) {
                $(n).val(data);
            }
        });
    }

    function getBrutto(o, n) {
        var id = $('#mattkarb-form').attr('data-id');
        $.ajax({
            url: '/admin/termek/getbrutto',
            type: 'GET',
            data: {
                id: id,
                value: o.val(),
                afakod: $('#AfaEdit').val()
            },
            success: function (data) {
                $(n).val(data);
            }
        });
    }

    var termek = {
        container: '#mattkarb',
        viewUrl: '/admin/termek/getkarb',
        newWindowUrl: '/admin/termek/viewkarb',
        saveUrl: '/admin/termek/save',
        beforeShow: function () {
            var translationtab = $('#TranslationTab');
            var artab = $('#ArsavTab');
            var keptab = $('#KepTab');
            var recepttab = $('#RecepturaTab');
            var kapcsolodotab = $('#KapcsolodoTab');
            var valtozattab = $('#ValtozatTab');
            var doktab = $('#DokTab');
            $('.js-saveas').on('click', function (e) {
                e.preventDefault();
                $('input[name="oper"]').val('add');
                $('input[name="id"]').val(0);
                $('input[name^="kepoper_"]').val('add');
                $('table[id^="keptable_"]').attr('data-oper', 'add');
                $('input[name^="valtozatoper_"]').val('add');
                $('input[name^="kapcsolodooper_"]').val('add');
                $('input[name^="receptoper_"]').val('add');
                $('input[name^="aroper_"]').val('add');
                $('#mattkarb-okbutton').click();
            });
            $('.js-saveandreopen').on('click', function (e) {
                e.preventDefault();
                $('input[name="oper"]').val('addreopen');
                $('input[name="id"]').val(0);
                $('#mattkarb-okbutton').click();
            });
            doktab
                .on('click', '.js-doknewbutton', function (e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/termekdok/getemptyrow',
                        type: 'GET',
                        success: function (data) {
                            doktab.append(data);
                            $('.js-doknewbutton,.js-dokdelbutton,.js-dokbrowsebutton,.js-dokopenbutton').button();
                            $this.remove();
                        }
                    });
                })
                .on('click', '.js-dokdelbutton', function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    dialogcenter.html('Biztos, hogy törli a dokumentumot?').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                $.ajax({
                                    url: '/admin/termekdok/del',
                                    type: 'POST',
                                    data: {
                                        id: $this.attr('data-id')
                                    },
                                    success: function (data) {
                                        $('#doktable_' + data).remove();
                                    }
                                });
                                $(this).dialog('close');
                            },
                            'Nem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                })
                .on('click', '.js-dokbrowsebutton', function (e) {
                    e.preventDefault();
                    var finder = new CKFinder(),
                        $dokpathedit = $('#DokPathEdit_' + $(this).attr('data-id')),
                        path = $dokpathedit.val();
                    finder.resourceType = 'Images';
                    if (path) {
                        finder.startupPath = path.substring(path.indexOf('/', 1));
                    }
                    finder.selectActionFunction = function (fileUrl, data) {
                        $dokpathedit.val(fileUrl);
                    };
                    finder.popup();
                });
            $('.js-doknewbutton,.js-dokbrowsebutton,.js-dokdelbutton,.js-dokopenbutton').button();
            keptab.on('click', '#FoKepDelButton', function (e) {
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
                })
                .on('click', '.js-kepnewbutton', function (e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/termekkep/getemptyrow',
                        type: 'GET',
                        success: function (data) {
                            keptab.append(data);
                            $('.js-kepnewbutton,.js-kepdelbutton,.js-kepbrowsebutton').button();
                            $this.remove();
                        }
                    });
                })
                .on('click', '.js-kepdelbutton', function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    dialogcenter.html('Biztos, hogy törli a képet?').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                $.ajax({
                                    url: '/admin/termekkep/del',
                                    type: 'POST',
                                    data: {
                                        id: $this.attr('data-id')
                                    },
                                    success: function (data) {
                                        $('#keptable_' + data).remove();
                                    }
                                });
                                $(this).dialog('close');
                            },
                            'Nem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                })
                .on('click', '.js-kepbrowsebutton', function (e) {
                    e.preventDefault();
                    var finder = new CKFinder(),
                        $kepurledit = $('#KepUrlEdit_' + $(this).attr('data-id')),
                        path = $kepurledit.val();
                    if (path) {
                        finder.startupPath = 'Images:' + path.substring(path.indexOf('/', 1));
                    }
                    finder.selectActionFunction = function (fileUrl, data) {
                        $kepurledit.val(fileUrl);
                    };
                    finder.popup();
                });
            $('#FoKepDelButton,#FoKepBrowseButton,.js-kepnewbutton,.js-kepbrowsebutton,.js-kepdelbutton').button();
            if (!$.browser.mobile) {
                $('.js-toflyout').flyout();
            }
            $('#cimkekarbcontainer').mattaccord({
                header: '#cimkekarbcontainerhead',
                page: '.js-cimkekarbpage',
                closeUp: '.js-cimkekarbcloseupbutton',
                collapse: '#cimkekarbcollapse'
            })
                .on('click', '.js-cimkekarb', function (e) {
                    e.preventDefault();
                    $(this).toggleClass('js-selectedcimke ui-state-hover');
                });
            $('.js-cimkeadd').on('click', function (e) {
                e.preventDefault();
                var ref = $(this).attr('data-refcontrol');
                var cimkenev = $(ref).val(),
                    katkod = ref.split('_')[1];
                if (cimkenev.length > 0) {
                    $.ajax({
                        url: '/admin/termekcimke/add',
                        type: 'POST',
                        data: {
                            cimkecsoport: katkod,
                            nev: cimkenev,
                            menu1lathato: true
                        },
                        success: function (data) {
                            $(ref).val('');
                            $(ref).before(data);
                        }
                    });
                }
            });
            recepttab.on('click', '.js-receptnewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/termekrecept/getemptyrow',
                    type: 'GET',
                    success: function (data) {
                        var tbody = $('#RecepturaTab');
                        tbody.append(data);
                        $('.js-receptnewbutton,.js-receptdelbutton').button();
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-receptdelbutton', function (e) {
                    e.preventDefault();
                    var receptgomb = $(this),
                        receptid = receptgomb.attr('data-id');
                    if (receptgomb.attr('data-source') === 'client') {
                        $('#recepttable_' + receptid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli a tételt?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/termekrecept/save',
                                        type: 'POST',
                                        data: {
                                            id: receptid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#recepttable_' + data).remove();
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
            $('.js-receptnewbutton,.js-receptdelbutton').button();
            artab.on('click', '.js-arnewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/termekar/getemptyrow',
                    type: 'GET',
                    success: function (data) {
                        var tbody = $('#ArsavTab');
                        tbody.append(data);
                        $('.js-arnewbutton,.js-ardelbutton').button();
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-ardelbutton', function (e) {
                    e.preventDefault();
                    var argomb = $(this),
                        arid = argomb.attr('data-id');
                    if (argomb.attr('data-source') === 'client') {
                        $('#artable_' + arid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli az ársávot?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/termekar/save',
                                        type: 'POST',
                                        data: {
                                            id: arid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#artable_' + data).remove();
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
            $('.js-arnewbutton,.js-ardelbutton').button();
            translationtab.on('click', '.js-translationnewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/termektranslation/getemptyrow',
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
                                        url: '/admin/termektranslation/save',
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
                .on('change', '.js-fieldselect', function (e) {
                    var $this = $(this),
                        x = $('option:selected', $this).val(),
                        editor;
                    if (!$.browser.mobile) {
                        if (x === 'leiras') {
                            editor = $('.js-contenteditor_' + $this.data('id'));
                            editor.addClass('js-ckeditor');
                            editor.ckeditor();
                        } else {
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
            kapcsolodotab.on('click', '.js-kapcsolodonewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/termekkapcsolodo/getemptyrow',
                    type: 'GET',
                    success: function (data) {
                        var tbody = $('#KapcsolodoTab');
                        tbody.append(data);
                        $('.js-kapcsolodonewbutton,.js-kapcsolododelbutton').button();
                        $('.js-kapcsolodoselect').autocomplete(termekAutocompleteConfig());
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-kapcsolododelbutton', function (e) {
                    e.preventDefault();
                    var kapcsgomb = $(this),
                        kapcsid = kapcsgomb.attr('data-id');
                    if (kapcsgomb.attr('data-source') === 'client') {
                        $('#kapcsolodotable_' + kapcsid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli a kapcsolódó terméket?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/termekkapcsolodo/save',
                                        type: 'POST',
                                        data: {
                                            id: kapcsid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#kapcsolodotable_' + data).remove();
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
            $('.js-kapcsolodonewbutton,.js-kapcsolododelbutton').button();
            valtozattab.on('click', '.js-valtozatnewbutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/termekvaltozat/getemptyrow',
                    type: 'GET',
                    data: {
                        termekid: $this.attr('data-termekid')
                    },
                    success: function (data) {
                        var tbody = $('#ValtozatTab');
                        tbody.append(data);
                        $('.js-valtozatnewbutton,.js-valtozatdelbutton').button();
                        createImageSelectable('.js-valtozatkepedit', '#ValtozatKepId_');
                        $this.remove();
                    }
                });
            })
                .on('click', '.js-valtozatdelbutton', function (e) {
                    e.preventDefault();
                    var gomb = $(this),
                        vid = gomb.attr('data-id');
                    if (gomb.attr('data-source') === 'client') {
                        $('#valtozattable_' + vid).remove();
                    } else {
                        dialogcenter.html('Biztos, hogy törli a változatot?').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Igen': function () {
                                    $.ajax({
                                        url: '/admin/termekvaltozat/save',
                                        type: 'POST',
                                        data: {
                                            id: vid,
                                            oper: 'del'
                                        },
                                        success: function (data) {
                                            $('#valtozattable_' + data).remove();
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
                .on('blur', '.js-valtozatnetto', function (e) {
                    e.preventDefault();
                    getSorBrutto($(this), 'valtozatbrutto_');
                })
                .on('blur', '.js-valtozatbrutto', function (e) {
                    e.preventDefault();
                    getSorNetto($(this), 'valtozatnetto_');
                })
                .on('blur', '.js-valtozatnettogen', function (e) {
                    e.preventDefault();
                    getSorBrutto($(this), 'valtozatbruttogen');
                })
                .on('blur', '.js-valtozatbruttogen', function (e) {
                    e.preventDefault();
                    getSorNetto($(this), 'valtozatnettogen');
                })
            $('#valtozatgeneratorform').ajaxForm({
                type: 'POST',
                beforeSubmit: function (arr, form, opt) {
//						pleaseWait();
                    arr.push({name: 'termekid', value: form.data('id')});
                },
                success: function (data) {
                    $('.valtozattable').remove();
                    $('#valtozatgenerator').after(data);
                    $('.js-valtozatdelbutton').button();
                }
            });
            $('.js-valtozatdelallbutton').button().on('click', function (e) {
                var $this = $(this);
                dialogcenter.html('Biztos, hogy törli az összes változatot?').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'Igen': function () {
//								pleaseWait();
                            $.ajax({
                                url: '/admin/termekvaltozat/delall',
                                type: 'POST',
                                data: {
                                    termekid: $this.data('termekid')
                                },
                                success: function () {
                                    $('.valtozattable').remove();
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
            $('.js-kapcsolodoselect').autocomplete(termekAutocompleteConfig());

            createImageSelectable('.js-valtozatkepedit', '#ValtozatKepId_');
            $('.js-valtozatnewbutton,.js-valtozatdelbutton,#valtozatgeneratorbutton').button();

            $('#NettoEdit').on('blur', function (e) {
                e.preventDefault();
                getBrutto($(this), '#BruttoEdit');
            });
            $('#BruttoEdit').on('blur', function (e) {
                e.preventDefault();
                getNetto($(this), '#NettoEdit');
            });
            $('#AkciosNettoEdit').on('blur', function (e) {
                e.preventDefault();
                getBrutto($(this), '#AkciosBruttoEdit');
            });
            $('#AkciosBruttoEdit').on('blur', function (e) {
                e.preventDefault();
                getNetto($(this), '#AkciosNettoEdit');
            });
            $('#NemkaphatoCheck').on('click', function (e) {
                var $this = $(this);
                if ($this.prop('checked')) {
                    dialogcenter.html('Biztos, hogy nem kaphatóvá teszi a terméket? A változatok automatikusan nem elérhetők lesznek.').dialog({
                        resizable: false,
                        height: 200,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                $('input[name^="valtozatelerheto_"]').prop('checked', false);
                                $('input[name="ajanlott"]').prop('checked', false);
                                $('input[name="kiemelt"]').prop('checked', false);
                                $(this).dialog('close');
                            },
                            'Nem': function () {
                                $this.prop('checked', false);
                                $(this).dialog('close');
                            }
                        }
                    });
                } else {
                    dialogcenter.html('Ne felejtse el beállítani az elérhető változatokat!').dialog({
                        resizable: false,
                        height: 200,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                }
            });
            mkwcomp.datumEdit.init('#AkcioStartEdit');
            mkwcomp.datumEdit.init('#AkcioStopEdit');
            $('.js-valtozatbeerkezesdatumedit').each(function () {
                mkwcomp.datumEdit.init($(this));
            });

            $('.js-termekfabutton').on('click', function (e) {
                var edit = $(this);
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
                    .bind('loaded.jstree', function (event, data) {
                        dialogcenter.jstree('open_node', $('#termekfa_1', dialogcenter).parent());
                    });
                dialogcenter.dialog({
                    resizable: true,
                    height: 340,
                    modal: true,
                    buttons: {
                        'Töröl': function () {
                            edit.attr('data-value', 0);
                            $('span', edit).text(edit.attr('data-text'));
                            $(this).dialog('close');
                        },
                        'OK': function () {
                            dialogcenter.jstree('get_selected').each(function () {
                                var treenode = $(this).children('a');
                                edit.attr('data-value', treenode.attr('id').split('_')[1]);
                                $('span', edit).text(treenode.text());
                            });
                            $(this).dialog('close');
                        },
                        'Bezár': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            })
                .button();
            $('.js-termekmenubutton').on('click', function (e) {
                var edit = $(this);
                e.preventDefault();
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
                        dialogcenter.jstree('open_node', $('#termekfa_1', dialogcenter).parent());
                    });
                dialogcenter.dialog({
                    resizable: true,
                    height: 340,
                    modal: true,
                    buttons: {
                        'Töröl': function () {
                            edit.attr('data-value', 0);
                            $('span', edit).text(edit.attr('data-text'));
                            $(this).dialog('close');
                        },
                        'OK': function () {
                            dialogcenter.jstree('get_selected').each(function () {
                                var treenode = $(this).children('a');
                                edit.attr('data-value', treenode.attr('id').split('_')[1]);
                                $('span', edit).text(treenode.text());
                            });
                            $(this).dialog('close');
                        },
                        'Bezár': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            })
                .button();
            if (!$.browser.mobile) {
                CKFinder.setupCKEditor(null, '/ckfinder/');
                $('#LeirasEdit').ckeditor();
                $('.js-ckeditor').each(function () {
                    $(this).ckeditor();
                });
            }
        },
        beforeSerialize: function (form, opt) {
            var netto = $('#AkciosNettoEdit').val() * 1,
                brutto = $('#AkciosBruttoEdit').val() * 1,
                astart = $('#AkcioStartEdit').val(),
                astop = $('#AkcioStopEdit').val();
            if ((netto || brutto) && (!astart && !astop)) {
                alert('Adja meg az akció kezdetét vagy végét.');
                return false;
            }
            var cimkek = new Array();
            $('.js-cimkekarb').filter('.js-selectedcimke').each(function () {
                cimkek.push($(this).attr('data-id'));
            });
            var x = {};
            x['cimkek[]'] = cimkek;
            $('.js-termekfabutton').each(function () {
                $this = $(this);
                x[$this.attr('data-name')] = $this.attr('data-value');
            });
            $('.js-termekmenubutton').each(function () {
                $this = $(this);
                x[$this.attr('data-name')] = $this.attr('data-value');
            });
            opt['data'] = x;
            return true;
        },
        beforeHide: function () {
            var editor;
            if (!$.browser.mobile) {
                editor = $('#LeirasEdit').ckeditorGet();
                if (editor) {
                    editor.destroy();
                }
                $('.js-ckeditor').each(function () {
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
        var lfilternames = ['#gyartofilter', '#nevfilter', '#kepurlfilter', '#lathatofilter', '#nemkaphatofilter', '#fuggobenfilter', '#inaktivfilter',
            '#ajanlottfilter', '#kiemeltfilter', '#akciosfilter'];
        for (var cikl = 2; cikl <= 15; cikl++) {
            lfilternames.push('#lathato' + cikl + 'filter');
        }
        $('#mattable-select').mattable({
            name: 'termek',
            onGetTBody: function () {
                if (!$.browser.mobile) {
                    $('.js-toflyout').flyout();
                }
            },
            filter: {
                fields: lfilternames,
                onClear: function () {
                    $('.js-cimkefilter').removeClass('ui-state-hover');
                    mkwcomp.termekfaFilter.clearChecks('#termekfa');
                    mkwcomp.termekmenuFilter.clearChecks('#termekmenu');
                },
                onFilter: function (obj) {
                    var cimkek = new Array(), fak;
                    $('.js-cimkefilter').filter('.ui-state-hover').each(function () {
                        cimkek.push($(this).attr('data-id'));
                    });
                    if (cimkek.length > 0) {
                        obj['cimkefilter'] = cimkek;
                    }
                    fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                    if (fak.length > 0) {
                        obj['fafilter'] = fak;
                    }
                    menuk = mkwcomp.termekmenuFilter.getFilter('#termekmenu');
                    if (menuk.length > 0) {
                        obj['menufilter'] = menuk;
                    }
                }
            },
            tablebody: {
                url: '/admin/termek/getlistbody',
                onStyle: function () {
                    $('.js-karton').button();
                },
                onDoEditLink: function () {
                    $('.js-karton').each(function () {
                        var $this = $(this);
                        $this.attr('href', '/admin/termekkarton/view?id=' + $this.data('termekid'));
                    });
                }
            },
            karb: termek
        });

        $('.mattable-batchbtn').on('click', function (e) {
            var cbs,
                tomb = [];
            e.preventDefault();
            cbs = $('.js-egyedcheckbox:checked');
            if (cbs.length) {
                cbs.closest('tr').each(function (index, elem) {
                    tomb.push($(elem).data('egyedid'));
                });
                switch ($('.mattable-batchselect').val()) {
                    case 'arexport':
                        href = '/admin/termek/arexport?ids=' + tomb.join(',');
                        dialogcenter.html('<a href="' + href + '" target="_blank">Letöltés</a>').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Bezár': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                    case 'fcmotoexport':
                        href = '/admin/termek/fcmotoexport?ids=' + tomb.join(',') + '&p=fcmoto';
                        dialogcenter.html('<a href="' + href + '" target="_blank">Letöltés</a>').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Bezár': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                    case 'maximomotoexport':
                        href = '/admin/termek/fcmotoexport?ids=' + tomb.join(',') + '&p=maximomoto';
                        dialogcenter.html('<a href="' + href + '" target="_blank">Letöltés</a>').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Bezár': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                    case 'gs1export':
                        href = '/admin/termek/gs1export?ids=' + tomb.join(',');
                        dialogcenter.html('<a href="' + href + '" target="_blank">Letöltés</a>').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Bezár': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                    case 'cikkszamosexport':
                        href = '/admin/termek/cikkszamosexport?ids=' + tomb.join(',');
                        dialogcenter.html('<a href="' + href + '" target="_blank">Letöltés</a>').dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'Bezár': function () {
                                    $(this).dialog('close');
                                }
                            }
                        });
                        break;
                    case 'tcsset':
                        dialogcenter.html($('#tcsset').show()).dialog({
                            resizable: false,
                            height: 140,
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    var dia = $(this);
                                    href = '/admin/termek/tcsset?ids=' + tomb.join(',');
                                    $.ajax({
                                        url: '/admin/termek/tcsset',
                                        type: 'POST',
                                        data: {
                                            ids: tomb,
                                            tcs: $('.js-tcsset').val()
                                        },
                                        success: function () {
                                            dia.dialog('close');
                                            $('#tcsset').hide();
                                            $('.mattable-tablerefresh').click();
                                        }
                                    });
                                },
                                'Mégsem': function () {
                                    $(this).dialog('close');
                                    $('#tcsset').hide();
                                }
                            }

                        });
                        break;
                }
            } else {
                dialogcenter.html('Válasszon ki legalább egy terméket!').dialog({
                    resizable: false,
                    height: 140,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            }
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });
        $('#mattable-body').on('click', '.js-flagcheckbox', function (e) {
            function doit(succ) {
                var id = $this.attr('data-id'),
                    flag = $this.attr('data-flag'),
                    kibe = !$this.is('.ui-state-hover');
                if (succ) {
                    succ();
                }
                $.ajax({
                    url: '/admin/termek/setflag',
                    type: 'POST',
                    data: {
                        id: id,
                        flag: flag,
                        kibe: kibe
                    },
                    success: function () {
                        $this.toggleClass('ui-state-hover');
                        if (kibe && (flag == 'nemkaphato')) {
                            $('a[data-id="' + id + '"][data-flag="kiemelt"]').removeClass('ui-state-hover');
                            $('a[data-id="' + id + '"][data-flag="ajanlott"]').removeClass('ui-state-hover');
                        }
                    }
                });
            }

            e.preventDefault();
            var $this = $(this);
            if ($this.attr('data-flag') === 'nemkaphato') {
                if (!$this.is('.ui-state-hover')) {
                    dialogcenter.html('Biztos, hogy nem kaphatóvá teszi a terméket? A változatok automatikusan nem elérhetők lesznek.').dialog({
                        resizable: false,
                        height: 200,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                doit(function () {
                                    dialogcenter.dialog('close');
                                });
                            },
                            'Nem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
                } else {
                    dialogcenter.html('Ne felejtse el beállítani az elérhető változatokat!').dialog({
                        resizable: false,
                        height: 200,
                        modal: true,
                        buttons: {
                            'OK': function () {
                                doit(function () {
                                    dialogcenter.dialog('close');
                                });
                            }
                        }
                    });
                }
            } else {
                doit();
            }
        })
            .on('click', '.js-keszletreszletezobutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/termek/getkeszletbyraktar',
                    data: {
                        termekid: $this.data('id')
                    },
                    success: function (data) {
                        dialogcenter.html(data).dialog({
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    dialogcenter.dialog('close');
                                }
                            }
                        });
                    }
                });
            })
            .on('click', '.js-valtozatkeszletreszletezobutton', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: '/admin/termekvaltozat/getkeszletbyraktar',
                    data: {
                        valtozatid: $this.data('id')
                    },
                    success: function (data) {
                        dialogcenter.html(data).dialog({
                            modal: true,
                            buttons: {
                                'OK': function () {
                                    dialogcenter.dialog('close');
                                }
                            }
                        });
                    }
                });
            });

        $('#cimkefiltercontainer').mattaccord({
            header: '#cimkefiltercontainerhead',
            page: '.accordpage',
            closeUp: '.js-cimkefiltercloseupbutton',
            collapse: '#cimkefiltercollapse'
        });
        $('.js-cimkefilter').on('click', function (e) {
            e.preventDefault();
            $(this).toggleClass('ui-state-hover');
        });
        mkwcomp.termekfaFilter.init('#termekfa');
        mkwcomp.termekmenuFilter.init('#termekmenu');
    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, termek, {independent: true}));
        }
    }
});