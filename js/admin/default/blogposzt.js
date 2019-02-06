$(document).ready(function () {
    var dialogcenter = $('#dialogcenter');

    function termekAutocompleteRenderer(ul, item) {
        if (item.nemlathato) {
            return $('<li>')
                .append('<a class="nemelerhetovaltozat">' + item.label + '</a>')
                .appendTo( ul );
        }
        else {
            return $('<li>')
                .append('<a>' + item.label + '</a>')
                .appendTo( ul );
        }
    }

    function termekAutocompleteConfig() {
        return {
            minLength: 4,
            autoFocus: true,
            source: '/admin/bizonylattetel/gettermeklist',
            select: function(event, ui) {
                var termek = ui.item;
                if (termek) {
                    var $this = $(this),
                        sorid = $this.attr('name').split('_')[1];
                    $this.siblings().val(termek.id);
                }
            }
        };
    }

    var blogposzt = {
        container: '#mattkarb',
        viewUrl: '/admin/blogposzt/getkarb',
        newWindowUrl: '/admin/blogposzt/viewkarb',
        saveUrl: '/admin/blogposzt/save',
        beforeShow: function () {
            var alttab = $('#AltalanosTab'),
                termektab = $('#TermekTab');
            $('#FoKepDelButton,#FoKepBrowseButton,.js-termeknewbutton,.js-termekdelbutton').button();
            if (!$.browser.mobile) {
                $('.js-toflyout').flyout();
            }

            mkwcomp.datumEdit.init('#MegjelenesDatumEdit');

            alttab.on('click', '#FoKepDelButton', function (e) {
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

            termektab
                .on('click', '.js-termeknewbutton', function (e) {
                    var $this = $(this);
                    e.preventDefault();
                    $.ajax({
                        url: '/admin/blogposzttermek/getemptyrow',
                        type: 'GET',
                        success: function (data) {
                            termektab.append(data);
                            $('.js-termeknewbutton,.js-termekdelbutton').button();
                            $('.js-termekselect').autocomplete(termekAutocompleteConfig())
                                .autocomplete( "instance" )._renderItem = termekAutocompleteRenderer;
                            $this.remove();
                        }
                    });
                })
                .on('click', '.js-termekdelbutton', function (e) {
                    e.preventDefault();
                    var $this = $(this);
                    dialogcenter.html('Biztos, hogy törli a terméket?').dialog({
                        resizable: false,
                        height: 140,
                        modal: true,
                        buttons: {
                            'Igen': function () {
                                $.ajax({
                                    url: '/admin/blogposzttermek/del',
                                    type: 'POST',
                                    data: {
                                        tid: $this.data('tid'),
                                        bid: $this.data('bid')
                                    },
                                    success: function (data) {
                                        $('#termektable_' + data).remove();
                                    }
                                });
                                $(this).dialog('close');
                            },
                            'Nem': function () {
                                $(this).dialog('close');
                            }
                        }
                    });
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
            if (!$.browser.mobile) {
                CKFinder.setupCKEditor(null, '/ckfinder/');
                $('#LeirasEdit').ckeditor();
            }
        },
        beforeSerialize: function (form, opt) {
            var x = {};
            $('.js-termekfabutton').each(function () {
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
            name: 'blogposzt',
            onGetTBody: function () {
                if (!$.browser.mobile) {
                    $('.js-toflyout').flyout();
                }
            },
            filter: {
                fields: ['#lathatofilter', '#cimfilter'],
                onClear: function () {
                    mkwcomp.termekfaFilter.clearChecks('#termekfa');
                },
                onFilter: function (obj) {
                    fak = mkwcomp.termekfaFilter.getFilter('#termekfa');
                    if (fak.length > 0) {
                        obj['fafilter'] = fak;
                    }
                }
            },
            tablebody: {
                url: '/admin/blogposzt/getlistbody'
            },
            karb: blogposzt
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
            }
            else {
                dialogcenter.html('Válasszon ki legalább egy blogposztot!').dialog({
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
                    url: '/admin/blogposzt/setflag',
                    type: 'POST',
                    data: {
                        id: id,
                        flag: flag,
                        kibe: kibe
                    },
                    success: function () {
                        $this.toggleClass('ui-state-hover');
                    }
                });
            }
            var $this = $(this);
            e.preventDefault();
            doit();
        });

        mkwcomp.termekfaFilter.init('#termekfa');
    }
    else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, termek, {independent: true}));
        }
    }
});