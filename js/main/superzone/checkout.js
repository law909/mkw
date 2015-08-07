var checkout = (function($) {

    function loadTetelList() {
        $.ajax({
            url: '/checkout/gettetellist',
			data: {
				szallitasimod: $('#SzallitasiMod').data('id')
			},
            success: function(data) {
                var d = JSON.parse(data);
                $('.js-chktetellist').html(d.html);
                kosarhash = d.hash.value;
            }
        });
    }

    function initUI() {
        var checkoutform = $('#CheckoutForm'),
			szamlanevinput = $('input[name="szamlanev"]'),
			szamlairszaminput = $('input[name="szamlairszam"]'),
			szamlavarosinput = $('input[name="szamlavaros"]'),
			szamlautcainput = $('input[name="szamlautca"]'),
			szallnevinput = $('input[name="szallnev"]'),
			szallirszaminput = $('input[name="szallirszam"]'),
			szallvarosinput = $('input[name="szallvaros"]'),
			szallutcainput = $('input[name="szallutca"]');

        loadTetelList();

        $('.js-chkaszf, .js-chkhelp').magnificPopup({
            type: 'ajax',
            closeBtnInside: false
        });

        checkoutform.on('submit', function(e) {
            var hibas = false, tofocus = false;

            $('.chk-sendorderbtn').removeClass('cartbtn').addClass('okbtn').val('Feldolgozás alatt');

            if (!szallnevinput.val()) {
                szallnevinput.addClass('hibas');
                if (!hibas) {
                    tofocus = szallnevinput;
                }
                hibas = true;
            }
            else {
                szallnevinput.removeClass('hibas');
            }

            if (!szallirszaminput.val()) {
                szallirszaminput.addClass('hibas');
                if (!hibas) {
                    tofocus = szallirszaminput;
                }
                hibas = true;
            }
            else {
                szallirszaminput.removeClass('hibas');
            }

            if (!szallvarosinput.val()) {
                szallvarosinput.addClass('hibas');
                if (!hibas) {
                    tofocus = szallvarosinput;
                }
                hibas = true;
            }
            else {
                szallvarosinput.removeClass('hibas');
            }

            if (!szallutcainput.val()) {
                szallutcainput.addClass('hibas');
                if (!hibas) {
                    tofocus = szallutcainput;
                }
                hibas = true;
            }
            else {
                szallutcainput.removeClass('hibas');
            }

            if (!szamlanevinput.val()) {
                szamlanevinput.addClass('hibas');
                if (!hibas) {
                    tofocus = szamlanevinput;
                }
                hibas = true;
            }
            else {
                szamlanevinput.removeClass('hibas');
            }

            if (!szamlairszaminput.val()) {
                szamlairszaminput.addClass('hibas');
                if (!hibas) {
                    tofocus = szamlairszaminput;
                }
                hibas = true;
            }
            else {
                szamlairszaminput.removeClass('hibas');
            }

            if (!szamlavarosinput.val()) {
                szamlavarosinput.addClass('hibas');
                if (!hibas) {
                    tofocus = szamlavarosinput;
                }
                hibas = true;
            }
            else {
                szamlavarosinput.removeClass('hibas');
            }

            if (!szamlautcainput.val()) {
                szamlautcainput.addClass('hibas');
                if (!hibas) {
                    tofocus = szamlautcainput;
                }
                hibas = true;
            }
            else {
                szamlautcainput.removeClass('hibas');
            }

            if (hibas) {
                $('.chk-sendorderbtn').removeClass('okbtn').addClass('cartbtn').val('Megrendelés elküldése');
                $('#dialogcenter').on('hidden', function() {
                    $('#dialogcenter').off('hidden');
                    if (tofocus) {
                        tofocus.focus();
                    }
                });
                superz.showDialog(superzmsg.ChkHiba);
                e.preventDefault();
                return false;
            }
            else {
                if (!$('input[name="aszfready"]').prop('checked')) {
                    $('.chk-sendorderbtn').removeClass('okbtn').addClass('cartbtn').val('Megrendelés elküldése');
                    e.preventDefault();
                    superz.showDialog(superzmsg.ChkASZF);
                    return false;
                }
                else {
                    superz.showMessage(superzmsg.ChkSave);
                    return true;
                }
            }
        });
    }

	return {
		initUI: initUI
	};

})(jQuery);