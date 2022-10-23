var termekertekeles = (function($) {

    function initUI() {
        $('.js-tert-form').on('submit', function (e) {
            let tids = [], error;
            $('.js-errorable').removeClass('checkouterrorblock');
            $('.js-termekids').each(function (i, el) {
                tids.push(el.value);
            });
            tids.forEach((el) => {
                const rating = $('input[name="rating_' + el + '"]:checked'),
                    ertekeles = $('textarea[name="ertekeles_' + el + '"]'),
                    elony = $('textarea[name="elony_' + el + '"]'),
                    hatrany = $('textarea[name="hatrany_' + el + '"]');
                if (hatrany.val() || elony.val()) {
                    if (!rating.val()) {
                        $('.rating').addClass('checkouterrorblock');
                        error = true;
                    }
                    if (!ertekeles.val()) {
                        ertekeles.addClass('checkouterrorblock');
                        error = true;
                    }
                }
            });
            if (error) {
                mkw.showMessage('Kérjük, töltse ki a hiányzó adatokat.');
                e.preventDefault();
            }
            return error;
        });
    }

    return {
        initUI: initUI
    };
})(jQuery);