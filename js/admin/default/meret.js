$(document).ready(function () {
    let dialogcenter = $('#dialogcenter');

    let meret = new MattkarbConfig({
        entityName: 'meret',
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
            name: 'meret',
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
                url: '/admin/meret/getlistbody',
                onStyle: function () {
                },
                onDoEditLink: function () {
                }
            },
            karb: meret
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

        $('#mattable-table').on('click', '.js-termeklistabutton', (e) => {
            e.preventDefault();
            $.get('/admin/meret/gettermeklista', {id: $(e.currentTarget).data('egyedid')}, (data) => {
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
            $('#mattkarb').mattkarb(meret);
        }
    }
});