$(document).ready(function () {
    let dialogcenter = $('#dialogcenter');

    let szin = {
        container: '#mattkarb',
        viewUrl: '/admin/szin/getkarb',
        newWindowUrl: '/admin/szin/viewkarb',
        saveUrl: '/admin/szin/save',
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
            if (!$.browser.mobile) {
                $('.js-toflyout').flyout();
            }
        },
        onSubmit: function () {
            $('#messagecenter')
                .html('A mentés sikerült.')
                .hide()
                .addClass('matt-messagecenter ui-widget ui-state-highlight')
                .one('click', messagecenterclick)
                .slideToggle('slow');
        }
    };

    if ($.fn.mattable) {
        var lfilternames = ['#nevfilter'];
        $('#mattable-select').mattable({
            name: 'szin',
            onGetTBody: function () {
                if (!$.browser.mobile) {
                    $('.js-toflyout').flyout();
                }
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

    } else {
        if ($.fn.mattkarb) {
            $('#mattkarb').mattkarb($.extend({}, szin, {independent: true}));
        }
    }
});