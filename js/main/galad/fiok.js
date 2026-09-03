let fiok = (function ($) {

    function initUI() {
        let $fiokadataimform = $('#FiokAdataim'),
            $garanciaugyform = $('#GaranciaUgyForm');

        if ($fiokadataimform.length > 0) {

            $('.js-copyszamlaadat').on('click', function () {
                $('input[name="szallnev"]').val($('input[name="nev"]').val());
                $('input[name="szallirszam"]').val($('input[name="irszam"]').val());
                $('input[name="szallvaros"]').val($('input[name="varos"]').val());
                $('input[name="szallutca"]').val($('input[name="utca"]').val());
                return false;
            });

            $('.js-accmegrendelesopen').on('click', function () {
                $(this).next('tr').toggleClass('notvisible');
                return false;
            });
        }

        if ($garanciaugyform.length > 0) {

            $('.js-newgaranciaugy').on('click', function () {
                $garanciaugyform.show();
            });

            $('#GaranciaUgyForm').on('submit', function (e) {
                e.preventDefault();
                $garanciaugyform.hide();
            });
        }

    }

    return {
        initUI: initUI
    };
})(jQuery);