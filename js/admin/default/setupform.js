$(document).ready(function() {
    var dialogcenter = $('#dialogcenter');

    $('#mattkarb').mattkarb({
        independent: true,
        viewUrl: '/admin/megrendelesfej/getkarb',
        newWindowUrl: '/admin/megrendelesfej/viewkarb',
        saveUrl: '/admin/megrendelesfej/save',
        beforeShow: function() {
            $('#TulajirszamEdit').autocomplete({
                minLength: 2,
                source: function(req, resp) {
                    $.ajax({
                        url: '/admin/irszam',
                        type: 'GET',
                        data: {
                            term: req.term,
                            tip: 1
                        },
                        success: function(data) {
                            var d = JSON.parse(data);
                            resp(d);
                        },
                        error: function() {
                            resp();
                        }
                    });
                },
                select: function(event, ui) {
                    $('input[name="tulajvaros"]').val(ui.item.nev);
                }
            });
            $('input[name="tulajvaros"]').autocomplete({
                minLength: 4,
                source: function(req, resp) {
                    $.ajax({
                        url: '/admin/varos',
                        type: 'GET',
                        data: {
                            term: req.term,
                            tip: 1
                        },
                        success: function(data) {
                            var d = JSON.parse(data);
                            resp(d);
                        },
                        error: function() {
                            resp();
                        }
                    });
                },
                select: function(event, ui) {
                    $('#TulajirszamEdit').val(ui.item.szam);
                }
            });
            $('.js-importnewkatid').on('click', function(e) {
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
                        'OK': function() {
                            var ide = dialogcenter.jstree('get_selected').children('a').attr('id'),
                                    caption = dialogcenter.jstree('get_selected').children('a').text(),
                                    ideid = ide.split('_')[1];
                            $('.js-importnewkatid span').text(caption);
                            $('input[name="importnewkatid"]').val(ideid);
                            $(this).dialog('close');
                        },
                        'Bezár': function() {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            }).button();
            $('.js-mugenracekatid').on('click', function(e) {
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
                        'OK': function() {
                            var ide = dialogcenter.jstree('get_selected').children('a').attr('id'),
                                caption = dialogcenter.jstree('get_selected').children('a').text(),
                                ideid = ide.split('_')[1];
                            $('.js-mugenracekatid span').text(caption);
                            $('input[name="mugenracekatid"]').val(ideid);
                            $(this).dialog('close');
                        },
                        'Bezár': function() {
                            $(this).dialog('close');
                        }
                    }
                });
                return false;
            }).button();
            $('.js-kepbrowsebutton').on('click', function(e) {
                e.preventDefault();
                var finder = new CKFinder(),
                        $kepurledit = $('input[name="' + $(this).data('name') + '"]'),
                        path = $kepurledit.val();
                if (path) {
                    finder.startupPath = 'Images:' + path.substring(path.indexOf('/', 1));
                }
                finder.selectActionFunction = function(fileUrl, data) {
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
                    success: function() {
                        dialogcenter.html('Az importálás megállt.').dialog({
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
            }).button();
            $('.js-repairimport').on('click', function (e) {
                var $this = $(this);
                e.preventDefault();
                $.ajax({
                    url: $this.data('href'),
                    type: 'POST',
                    success: function() {
                        dialogcenter.html('A termékek javítva.').dialog({
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
            }).button();
        },
        onSubmit: function() {
            pleaseWait('A mentés sikerült.');
            setTimeout((function() {
                $.unblockUI;
            }), 8000);
        }
    });
});