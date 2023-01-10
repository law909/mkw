var rendezvenyreg = (function($) {

    let regform;

    function initUI() {

        regform = $('#rendezvenyregform');

        regform.on('submit', function(e) {
            const hibas = false;

            return !hibas;
        })
        .on('change', '#knevedit', function(e) {
            const $edit = $('#nevedit');
            if (!$edit.val()) {
                $edit.val($('#vnevedit').val() + ' ' + $('#knevedit').val());
            }
        })
        .on('click', '.js-lemond', function(e) {
            const email = $('#emailedit').val();
            if (email) {
                $.ajax({
                    url: ''
                })
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