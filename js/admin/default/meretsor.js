$(document).ready(function () {
    let dialogcenter = $('#dialogcenter');

    let meretsor = {
        container: '#mattkarb',
        viewUrl: '/admin/meretsor/getkarb',
        newWindowUrl: '/admin/meretsor/viewkarb',
        saveUrl: '/admin/meretsor/save',
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
            name: 'meretsor',
            filter: {
                fields: lfilternames,
            },
            tablebody: {
                url: '/admin/meretsor/getlistbody',
                onStyle: function () {
                },
                onDoEditLink: function () {
                }
            },
            karb: meretsor
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
                dialogcenter.html('Válasszon ki legalább egy méret sort!').dialog({
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
            $('#mattkarb').mattkarb($.extend({}, meretsor, {independent: true}));
        }
    }
});