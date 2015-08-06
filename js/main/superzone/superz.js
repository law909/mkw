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

    return {
        showMessage: showMessage,
        closeMessage: closeMessage
    };

})(jQuery);