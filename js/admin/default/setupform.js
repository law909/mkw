$(document).ready(function () {
    const dialogcenter = $('#dialogcenter');

    $('#mattkarb').mattkarb(new MattkarbConfig({
        beforeShow: function () {
            $('#TulajirszamEdit').autocomplete({
                minLength: 2,
                source: function (req, resp) {
                    $.ajax({
                        url: '/admin/irszam',
                        type: 'GET',
                        data: {
                            term: req.term,
                            tip: 1
                        },
                        success: function (data) {
                            var d = JSON.parse(data);
                            resp(d);
                        },
                        error: function () {
                            resp();
                        }
                    });
                },
                select: function (event, ui) {
                    $('input[name="tulajvaros"]').val(ui.item.nev);
                }
            });
            $('input[name="tulajvaros"]').autocomplete({
                minLength: 4,
                source: function (req, resp) {
                    $.ajax({
                        url: '/admin/varos',
                        type: 'GET',
                        data: {
                            term: req.term,
                            tip: 1
                        },
                        success: function (data) {
                            var d = JSON.parse(data);
                            resp(d);
                        },
                        error: function () {
                            resp();
                        }
                    });
                },
                select: function (event, ui) {
                    $('#TulajirszamEdit').val(ui.item.szam);
                }
            });
            // Termék / partner autocomplete a setup mezőkhöz (select helyett).
            // A kiválasztott elem neve a szövegmezőbe, id-je a data-target nevű rejtett inputba kerül.
            $('.js-setuptermekselect').autocomplete({
                minLength: 4,
                autoFocus: true,
                source: '/admin/bizonylattetel/gettermeklist',
                select: function (event, ui) {
                    if (ui.item) {
                        $(this).val(ui.item.value);
                        $('input[name="' + $(this).data('target') + '"]').val(ui.item.id);
                    }
                    return false;
                }
            });
            $('.js-setuppartnerselect').autocomplete({
                minLength: 4,
                autoFocus: true,
                source: '/admin/bizonylatfej/getpartnerlist',
                select: function (event, ui) {
                    if (ui.item) {
                        $(this).val(ui.item.value);
                        $('input[name="' + $(this).data('target') + '"]').val(ui.item.id);
                    }
                    return false;
                }
            });
            // A szövegmező kiürítésekor a rejtett id is törlődjön (a mező üresre állítható).
            $('.js-setuptermekselect, .js-setuppartnerselect').on('input', function () {
                if ($(this).val() === '') {
                    $('input[name="' + $(this).data('target') + '"]').val('');
                }
            });
            $('.js-importnewkatid').on('click', function (e) {
                dialogcenter.jstree({
                    core: {animation: 100},
                    plugins: ['themeroller', 'json_data', 'ui'],
                    themeroller: {item: ''},
                    json_data: {
                        ajax: {url: '/admin/termekfa/jsonlist'}
                    },
                    ui: {select_limit: 1}
                })
                    .on('loaded.jstree', function (event, data) {
                        dialogcenter.jstree('open_node', $('#termekfa_1', dialogcenter).parent());
                    });
                dialogcenter.dialog({
                    resizable: true,
                    height: 340,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            var ide = dialogcenter.jstree('get_selected').children('a').attr('id'),
                                caption = dialogcenter.jstree('get_selected').children('a').text(),
                                ideid = ide.split('_')[1];
                            $('.js-importnewkatid span').text(caption);
                            $('input[name="importnewkatid"]').val(ideid);
                            $(this).dialog('close');
                        },
                        'Bezár': function () {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            }).button();
            $('.js-nominkeszlettermekkat').on('click', function (e) {
                dialogcenter.jstree({
                    core: {animation: 100},
                    plugins: ['themeroller', 'json_data', 'ui'],
                    themeroller: {item: ''},
                    json_data: {
                        ajax: {url: '/admin/termekfa/jsonlist'}
                    },
                    ui: {select_limit: 1}
                })
                    .on('loaded.jstree', function (event, data) {
                        dialogcenter.jstree('open_node', $('#termekfa_1', dialogcenter).parent());
                    });
                dialogcenter.dialog({
                    resizable: true,
                    height: 340,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            var ide = dialogcenter.jstree('get_selected').children('a').attr('id'),
                                caption = dialogcenter.jstree('get_selected').children('a').text(),
                                ideid = ide.split('_')[1];
                            $('.js-nominkeszlettermekkat span').text(caption);
                            $('input[name="nominkeszlettermekkat"]').val(ideid);
                            $(this).dialog('close');
                        },
                        'Bezár': function () {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            }).button();
            $('.js-web4defakatid').on('click', function (e) {
                dialogcenter.jstree({
                    core: {animation: 100},
                    plugins: ['themeroller', 'json_data', 'ui'],
                    themeroller: {item: ''},
                    json_data: {
                        ajax: {url: '/admin/termekfa/jsonlist'}
                    },
                    ui: {select_limit: 1}
                })
                    .on('loaded.jstree', function (event, data) {
                        dialogcenter.jstree('open_node', $('#termekfa_1', dialogcenter).parent());
                    });
                dialogcenter.dialog({
                    resizable: true,
                    height: 340,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            var ide = dialogcenter.jstree('get_selected').children('a').attr('id'),
                                caption = dialogcenter.jstree('get_selected').children('a').text(),
                                ideid = ide.split('_')[1];
                            $('.js-web4defakatid span').text(caption);
                            $('input[name="web4defakatid"]').val(ideid);
                            $(this).dialog('close');
                        },
                        'Bezár': function () {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            }).button();
            $('.js-mugenracekatid').on('click', function (e) {
                dialogcenter.jstree({
                    core: {animation: 100},
                    plugins: ['themeroller', 'json_data', 'ui'],
                    themeroller: {item: ''},
                    json_data: {
                        ajax: {url: '/admin/termekfa/jsonlist'}
                    },
                    ui: {select_limit: 1}
                })
                    .on('loaded.jstree', function (event, data) {
                        dialogcenter.jstree('open_node', $('#termekfa_1', dialogcenter).parent());
                    });
                dialogcenter.dialog({
                    resizable: true,
                    height: 340,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            var ide = dialogcenter.jstree('get_selected').children('a').attr('id'),
                                caption = dialogcenter.jstree('get_selected').children('a').text(),
                                ideid = ide.split('_')[1];
                            $('.js-mugenracekatid span').text(caption);
                            $('input[name="mugenracekatid"]').val(ideid);
                            $(this).dialog('close');
                        },
                        'Bezár': function () {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            }).button();
            // Kezdő termék kategória gombok (webshoponként) - a js-importnewkatid mintájára.
            $('.js-kezdokatbutton').on('click', function (e) {
                e.preventDefault();
                var $button = $(this),
                    target = $button.data('target');
                dialogcenter.jstree({
                    core: {animation: 100},
                    plugins: ['themeroller', 'json_data', 'ui'],
                    themeroller: {item: ''},
                    json_data: {
                        ajax: {url: '/admin/termekfa/jsonlist'}
                    },
                    ui: {select_limit: 1}
                })
                    .on('loaded.jstree', function (event, data) {
                        dialogcenter.jstree('open_node', $('#termekfa_1', dialogcenter).parent());
                    });
                dialogcenter.dialog({
                    resizable: true,
                    height: 340,
                    modal: true,
                    buttons: {
                        'OK': function () {
                            var sel = dialogcenter.jstree('get_selected').children('a');
                            if (sel.length) {
                                var caption = sel.text(),
                                    ideid = sel.attr('id').split('_')[1],
                                    $txt = $button.find('span');
                                ($txt.length ? $txt : $button).text(caption);
                                $('input[name="' + target + '"]').val(ideid);
                            }
                            $(this).dialog('close');
                        },
                        'Bezár': function () {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            }).button();
            $('.js-kepbrowsebutton').on('click', function (e) {
                e.preventDefault();
                var finder = new CKFinder(),
                    $kepurledit = $('input[name="' + $(this).data('name') + '"]'),
                    path = $kepurledit.val();
                if (path) {
                    finder.startupPath = 'Images:' + path.substring(path.indexOf('/', 1));
                }
                finder.selectActionFunction = function (fileUrl, data) {
                    $kepurledit.val(fileUrl);
                };
                finder.popup();
                return false;
            }).button();
            $('.js-stopimport').on('click', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: $this.data('href'),
                    type: 'POST',
                    success: function () {
                        dialogcenter.html('Az importálás megállt.').dialog({
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
                })
            }).button();
            $('.js-repairimport').on('click', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: $this.data('href'),
                    type: 'POST',
                    success: function () {
                        dialogcenter.html('A termékek javítva.').dialog({
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
                })
            }).button();
            // a MOST beírt kulcsot küldjük: az UNAS 5 sikertelen logint enged óránként
            $('.js-unasteszt').on('click', function (e) {
                var $this = $(this),
                    // redtext, nem ui-state-error-text: az utóbbi a hot-sneaks UI témában fehér,
                    // ez a válasz pedig sima háttéren áll
                    $valasz = $('#unastesztvalasz').removeClass('redtext').text('...');
                e.preventDefault();
                $.ajax({
                    url: $this.data('href'),
                    type: 'POST',
                    dataType: 'json',
                    data: {
                        unasapiurl: $('input[name="unasapiurl"]').val(),
                        unasapikey: $('input[name="unasapikey"]').val()
                    }
                })
                    .done(function (data) {
                        if (!data.ok) {
                            $valasz.addClass('redtext').text(data.hiba);
                            return;
                        }
                        var szoveg = 'ShopId: ' + data.shopid + ', csomag: ' + data.subscription
                            + ', jogosultságok: ' + (data.permissions || []).join(', ');
                        if (data.hianyzo && data.hianyzo.length) {
                            $valasz.addClass('redtext')
                                .text(szoveg + ' — HIÁNYZIK: ' + data.hianyzo.join(', '));
                        } else {
                            $valasz.text(szoveg);
                        }
                    })
                    .fail(function () {
                        $valasz.addClass('redtext').text('A teszt nem futott le.');
                    });
            }).button();
            mkwcomp.datumEdit.init('#mptngydatum1edit');
            mkwcomp.datumEdit.init('#mptngydatum2edit');
            mkwcomp.datumEdit.init('#mptngydatum3edit');
        },
        onSubmit: function () {
            pleaseWait('A mentés sikerült.');
            setTimeout((function () {
                $.unblockUI;
            }), 8000);
        }
    }));
});