var superz = (function($) {

    function showMessage(msg) {
        var msgcenter = $('#messagecenter');
        msgcenter.html(msg);
        $.magnificPopup.open({
            modal: true,
            items: [
                {
                    src: msgcenter,
                    type: 'inline'
                }
            ]
        });
    }

    function closeMessage() {
        $.magnificPopup.close();
    }

    function showDialog(msg, options) {
        var dlgcenter = $('#dialogcenter'),
                dlgheader = $('.modal-header', dlgcenter),
                dlgbody = $('.modal-body', dlgcenter).empty(),
                dlgfooter = $('.modal-footer', dlgcenter).empty(),
                classes = 'btn';
        $('h4', dlgheader).remove();
        opts = {
            header: superzmsg.DialogFejlec,
            buttons: [{
                    caption: superzmsg.DialogOk,
                    _class: 'btn btn-primary',
                    click: function(e) {
                        e.preventDefault();
                        closeDialog();
                        if (options && options.onOk) {
                            options.onOk.apply(this);
                        }
                    }
                }]
        };
        if (opts.header) {
            dlgheader.append('<h4>' + opts.header + '</h4>');
        }
        if (msg) {
            dlgbody.append('<p>' + msg + '</p>');
        }
        for (var i = 0; i < opts.buttons.length; i++) {
            if (opts.buttons[i]._class) {
                classes = classes + ' ' + opts.buttons[i]._class;
            }
            $('<button class="' + classes + '">' + opts.buttons[i].caption + '</button>')
                    .appendTo(dlgfooter)
                    .on('click', opts.buttons[i].click);
        }
        return dlgcenter.modal();
    }

    function closeDialog() {
        var dlgcenter = $('#dialogcenter');
        dlgcenter.modal('hide');
    }

    return {
        showMessage: showMessage,
        closeMessage: closeMessage,
        showDialog: showDialog,
        closeDialog: closeDialog
    };

})(jQuery);