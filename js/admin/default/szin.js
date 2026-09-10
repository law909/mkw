$(document).ready(function () {
    let dialogcenter = $('#dialogcenter');

    let szin = new MattkarbConfig({
        entityName: 'szin',
        beforeShow: function () {
            $('#FoKepDelButton').on('click', function (e) {
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
            });
            $('#FoKepBrowseButton').on('click', function (e) {
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
            if (!window.mkwIsMobile) {
                $('.js-toflyout').flyout();
            }
        },
    });

    if ($.fn.mattable) {
        var lfilternames = ['#nevfilter', '#charkodfilter'];
        $('#mattable-select').mattable({
            name: 'szin',
            onGetTBody: function () {
                if (!window.mkwIsMobile) {
                    $('.js-toflyout').flyout();
                }
                $('.js-termeklistabutton').button();
            },
            filter: {
                fields: lfilternames,
            },
            tablebody: {
                url: '/admin/szin/getlistbody',
                onStyle: function () {
                },
                onDoEditLink: function () {
                }
            },
            karb: szin
        });

        // a sorrend újraképzése az egész törzsre megy, kijelölés nélkül
        $('.mattable-batchbtn').on('click', function (e) {
            e.preventDefault();
            if ($('.mattable-batchselect').val() !== 'sorrendgen') {
                return;
            }
            dialogcenter.html('Újraképzi a sorrendet név szerint az egész színtörzsre? A meglévő sorrend elvész.').dialog({
                resizable: false,
                height: 160,
                modal: true,
                buttons: {
                    'Igen': function () {
                        const dia = $(this);
                        $.post('/admin/szin/sorrendgen', {}, function () {
                            dia.dialog('close');
                            $('.mattable-tablerefresh').click();
                        });
                    },
                    'Nem': function () {
                        $(this).dialog('close');
                    }
                }
            });
        });

        $('.js-maincheckbox').change(function () {
            $('.js-egyedcheckbox').prop('checked', $(this).prop('checked'));
        });

        $('#mattable-table').on('click', '.js-termeklistabutton', (e) => {
            e.preventDefault();
            $.get('/admin/szin/gettermeklista', {id: $(e.currentTarget).data('egyedid')}, (data) => {
                dialogcenter.html(JSON.parse(data).html).dialog({
                    title: 'Termékek',
                    resizable: true,
                    height: 400,
                    width: 600,
                    modal: true,
                    buttons: {
                        'Bezár': function () {
                            $(this).dialog('close');
                        }
                    }
                });
            });
        });

    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb(szin);
        }
    }
});