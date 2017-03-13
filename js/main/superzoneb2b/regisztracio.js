var regisztracio = (function($) {

    function initUI() {
        var $regform = $('#RegisztracioForm');

        if ($regform.length > 0) {


            $('.js-copyszamlaadat').on('click', function() {
                $('input[name="szallnev"]').val($('input[name="nev"]').val());
                $('input[name="szallirszam"]').val($('input[name="irszam"]').val());
                $('input[name="szallvaros"]').val($('input[name="varos"]').val());
                $('input[name="szallutca"]').val($('input[name="utca"]').val());
                return false;
            });

        }
    }

    return {
        initUI: initUI
    };
})(jQuery);