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
            if ($('#emailedit').val()) {
                const url = new URL('/rendezveny/lemond');
                url.searchParams.append('email', $('#emailedit').val());
                url.searchParams.append('rid', $('input[name="r"]').val());
                location.href = url.href;
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