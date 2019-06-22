var rendezvenyreg = (function($) {

    var regform;

    function initUI() {

        regform = $('#rendezvenyregform');

        regform.on('submit', function(e) {
            var hibas = false;

            return !hibas;
        })
        .on('change', '#knevedit', function(e) {
            var $edit = $('#nevedit');
            if (!$edit.val()) {
                $edit.val($('#vnevedit').val() + ' ' + $('#knevedit').val());
            }
        });
    }

    return {
        initUI: initUI
    }

})(jQuery);

$(document).ready(function() {
    rendezvenyreg.initUI();
});