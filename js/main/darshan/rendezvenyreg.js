var rendezvenyreg = (function($) {

    var regform;

    function initUI() {

        regform = $('#rendezvenyregform');

        regform.on('submit', function(e) {
            var hibas = false;

            if (!hibas) {
                window.location = 'http://jogadarshan.hu/sikeres-jelentkezes/';
            }
        });
    }

    return {
        initUI: initUI
    }

})(jQuery);