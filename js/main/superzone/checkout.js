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
            szamlanevgr = szamlanevinput.closest('.form-group'),
			szamlairszaminput = $('input[name="szamlairszam"]'),
            szamlairszamgr = szamlairszaminput.closest('.form-group'),
			szamlavarosinput = $('input[name="szamlavaros"]'),
            szamlavarosgr = szamlavarosinput.closest('.form-group'),
			szamlautcainput = $('input[name="szamlautca"]'),
            szamlautcagr = szamlautcainput.closest('.form-group'),
			szallnevinput = $('input[name="szallnev"]'),
			szallnevgr = szallnevinput.closest('.form-group'),
			szallirszaminput = $('input[name="szallirszam"]'),
			szallirszamgr = szallirszaminput.closest('.form-group'),
			szallvarosinput = $('input[name="szallvaros"]'),
			szallvarosgr = szallvarosinput.closest('.form-group'),
			szallutcainput = $('input[name="szallutca"]'),
			szallutcagr = szallutcainput.closest('.form-group'),
            hataridoinput = $('input[name="hatarido"]'),
            hataridogr = hataridoinput.closest('.form-group');

        loadTetelList();

        $('.js-chkaszf, .js-chkhelp').magnificPopup({
            type: 'ajax',
            closeBtnInside: false
        });

        $('.js-checkoutsendorder').on('click', function(e) {
            e.preventDefault();
            checkoutform.submit();
        });

        $('#Hatarido').datetimepicker({
            format: 'L'
        });

        checkoutform.on('submit', function(e) {
            var hibas = false, tofocus = false,
                szalleqszamla = $('input[name="szalleqszamla"]').prop('checked');

            if (!szalleqszamla && !szallnevinput.val()) {
                szallnevgr.addClass('has-error');
                if (!hibas) {
                    tofocus = szallnevinput;
                }
                hibas = true;
            }
            else {
                szallnevgr.removeClass('has-error');
            }

            if (!szalleqszamla && !szallirszaminput.val()) {
                szallirszamgr.addClass('has-error');
                if (!hibas) {
                    tofocus = szallirszaminput;
                }
                hibas = true;
            }
            else {
                szallirszamgr.removeClass('has-error');
            }

            if (!szalleqszamla && !szallvarosinput.val()) {
                szallvarosgr.addClass('has-error');
                if (!hibas) {
                    tofocus = szallvarosinput;
                }
                hibas = true;
            }
            else {
                szallvarosgr.removeClass('has-error');
            }

            if (!szalleqszamla && !szallutcainput.val()) {
                szallutcagr.addClass('has-error');
                if (!hibas) {
                    tofocus = szallutcainput;
                }
                hibas = true;
            }
            else {
                szallutcagr.removeClass('has-error');
            }

            if (!szamlanevinput.val()) {
                szamlanevgr.addClass('has-error');
                if (!hibas) {
                    tofocus = szamlanevinput;
                }
                hibas = true;
            }
            else {
                szamlanevgr.removeClass('has-error');
            }

            if (!szamlairszaminput.val()) {
                szamlairszamgr.addClass('has-error');
                if (!hibas) {
                    tofocus = szamlairszaminput;
                }
                hibas = true;
            }
            else {
                szamlairszamgr.removeClass('has-error');
            }

            if (!szamlavarosinput.val()) {
                szamlavarosgr.addClass('has-error');
                if (!hibas) {
                    tofocus = szamlavarosinput;
                }
                hibas = true;
            }
            else {
                szamlavarosgr.removeClass('has-error');
            }

            if (!szamlautcainput.val()) {
                szamlautcagr.addClass('has-error');
                if (!hibas) {
                    tofocus = szamlautcainput;
                }
                hibas = true;
            }
            else {
                szamlautcagr.removeClass('has-error');
            }

            if (!hataridoinput.val()) {
                hataridogr.addClass('has-error');
                if (!hibas) {
                    tofocus = hataridoinput;
                }
                hibas = true;
            }
            else {
                hataridogr.removeClass('has-error');
            }

            if (hibas) {
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
                superz.showMessage(superzmsg.ChkSave);
                return true;
            }
        });
    }

	return {
		initUI: initUI
	};

})(jQuery);