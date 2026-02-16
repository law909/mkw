var mkwmsg = {
	TermekErtesitoKoszonjuk: 'Thank you for your subscription!<br>We will inform you inmediately when the product will be available.',
	TermekErtesitoLeiratkozas: 'Your subscription is ended.',
	TermekKosarba: 'This product is in your cart...',
	TermekValtozatotValassz: 'Please choose the color and size!',
	KosarMennyisegModositas: 'We have modified quantity.',
	KosarMennyisegNulla: 'Please remove this product from your cart with <b>Del</b> button!',
	ChkRegLogin: 'Please choose if you want to register or log in!',
	ChkASZF: 'Please agree with the terms of agreement.',
	ChkKosarValtozott: 'Your cart has changed, please check.',
	ChkKosarUres: 'Your cart is empty.',
    ChkHiba: 'Please fill out missing data.',
    ChkSzallmodHiba: 'Please choose your preferred delivery method.',
    ChkSave: 'Saving your order. Please wait.',
	FiokAdataitModositjuk: 'Saving changes...',
	DialogFejlec: 'Information',
	DialogOk: 'OK',
    PassChange: ['Password change is ready.', 'The two passwords are not the same.', 'Wrong password.','You did not give new password.'],
    PassReminderCreated: 'We have sent a link to your email.',
    PassReminderRequiredEmail: 'Please give us your email address.'
};
var mkwmsghun = {
    TermekErtesitoKoszonjuk: 'Köszönjük feliratkozását!<br>Azonnal értesíteni fogjuk, amint a termék kapható lesz.',
    TermekErtesitoLeiratkozas: 'A leiratkozás folyamatban van.',
    TermekKosarba: 'A terméket a kosarába tettük...',
    TermekValtozatotValassz: 'Válassza ki a lenyíló listából, hogy pontosan milyen termékváltozatot szeretne!',
    KosarMennyisegModositas: 'Módosítottuk a mennyiséget.',
    KosarMennyisegNulla: 'Ha nem kíván vásárolni a termékből, a <b>"Töröl"</b> gombbal veheti ki a kosárból.',
    ChkRegLogin: 'Válassza ki, hogy szeretne-e regisztrálni a vásárláshoz, vagy jelentkezzen be, ha már van nálunk fiókja.',
    ChkASZF: 'Megrendelés előtt kérjük, fogadja el az <b>ÁSZF</b>-et.<br>Ehhez kattintson az <b>"Elolvastam és elfogadom az ÁSZF-et"</b> sor előtti négyzetre.',
    ChkKosarValtozott: 'A kosár tartalma időközben megváltozott, kérem ellenőrizze.',
    ChkKosarUres: 'Az Ön kosara üres.',
    ChkHiba: 'Kérjük, adja meg a hiányzó adatokat. Ezeket pirossal megjelöltük.',
    ChkSzallmodHiba: 'Kérjük adja meg a kívánt szállítási módot.',
    ChkSave: 'Megrendelésének mentése folyamatban. Kérem várjon.',
    FiokAdataitModositjuk: 'Adatait módosítjuk...',
    DialogFejlec: 'Értesítés',
    DialogOk: 'OK',
    PassChange: ['A jelszómódosítás sikerült.', 'A két jelszó nem egyezik.', 'Hibás jelszót adott meg.','Nem adott meg új jelszót.'],
    PassReminderCreated: 'Emailcímére elküldtünk egy jelszóemlékeztető linket.',
    PassReminderRequiredEmail: 'Adja meg azt az emailcímet, amellyel regisztrált.'
};
var mkw = (function($) {

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

    function showDialog(msg, options) {
        console.log('show dialog', msg, options);
        var dlgcenter = $('#dialogcenter'),
                dlgheader = $('.modal-header', dlgcenter),
                dlgbody = $('.modal-body', dlgcenter).empty(),
                dlgfooter = $('.modal-footer', dlgcenter).empty(),
                classes = 'btn';
        $('h4', dlgheader).remove();
        opts = {
            header: mkwmsg.DialogFejlec,
            buttons: [{
                    caption: mkwmsg.DialogOk,
                    _class: 'okbtn',
                    click: function(e) {
                        e.preventDefault();
                        closeDialog();
                        if (options && options.onOk) {
                            options.onOk.apply(this);
                        }
                    }
                }]
        };
        if (opts.header) {
            dlgheader.append('<h4>' + opts.header + '</h4>');
        }
        if (msg) {
            dlgbody.append('<p>' + msg + '</p>');
        }
        for (var i = 0; i < opts.buttons.length; i++) {
            if (opts.buttons[i]._class) {
                classes = classes + ' ' + opts.buttons[i]._class;
            }
            $('<button class="' + classes + '">' + opts.buttons[i].caption + '</button>')
                    .appendTo(dlgfooter)
                    .on('click', opts.buttons[i].click);
        }
        return dlgcenter.modal();
    }

    function closeDialog() {
        var dlgcenter = $('#dialogcenter');
        dlgcenter.modal('hide');
    }

    function lapozas(page) {
        var lf = $('.lapozoform'), url,
                filterstr = '';
        if (!page) {
            page = lf.data('pageno');
        }
        url = lf.data('url') + '?pageno=' + page,
                url = url + '&elemperpage=' + $('.elemperpageedit').val() + '&order=' + $('.orderedit').val();
        $('#szuroform input:checkbox:checked').each(function() {
            filterstr = filterstr + $(this).prop('name') + ',';
        });
        if (filterstr !== '') {
            url = url + '&filter=' + filterstr;
        }
        url = url + '&arfilter=' + $('#ArSlider').val();
        url = url + '&keresett=' + $('.KeresettEdit').val();
        url = url + '&vt=' + $('#ListviewEdit').val();
        if ($('#CsakakciosEdit').val()) {
            url = url + '&csakakcios=1';
        }
        document.location = url;
    }

    function overrideFormSubmit(form, msg, events) {
        var $form = form;
        if (!events) {
            events = {};
        }
        if (typeof form == 'string') {
            $form = $(form);
        }
        $form.on('submit', function(e) {
            e.preventDefault();
            var data = {jax: 1};
            $form.find('input').each(function() {
                var $this = $(this);
                switch ($this.attr('type')) {
                    case 'checkbox':
                        data[$this.attr('name')] = $this.prop('checked');
                        break;
                    default:
                        data[$this.attr('name')] = $this.val();
                        break;
                }
            });
            $.ajax({
                url: $form.attr('action'),
                type: 'POST',
                data: data,
                beforeSend: function(xhr, settings) {
                    var ret = true;
                    if (typeof events.beforeSend == 'function') {
                        ret = events.beforeSend.call($form, xhr, settings, data);
                    }
                    if (msg) {
                        showMessage(msg);
                    }
                    return ret;
                },
                complete: function(xhr, status) {
                    if (msg) {
                        closeMessage();
                    }
                    if (typeof events.complete == 'function') {
                        events.complete.call($form, xhr, status);
                    }
                },
                error: function(xhr, status, error) {
                    if (typeof events.error == 'function') {
                        events.error.call($form, xhr, status);
                    }
                },
                success: function(data, status, xhr) {
                    if (typeof events.success == 'function') {
                        events.success.call($form, data, status, xhr);
                    }
                }
            });
        });

    }

    function irszamTypeahead(irszaminput, varosinput) {
        if ($.fn.typeahead) {
            var map = {};
            $(irszaminput).typeahead({
                source: function(query, process) {
                    var texts = [];
                    return $.ajax({
                        url: '/irszam',
                        type: 'GET',
                        data: {
                            term: query
                        },
                        success: function(data) {
                            var d = JSON.parse(data);
                            $.each(d, function(i, irszam) {
                                map[irszam.id] = irszam;
                                texts.push(irszam.id);
                            });
                            return process(texts);
                        }
                    });
                },
                updater: function(item) {
                    var irsz = map[item];
                    item = irsz.szam;
                    $(varosinput).val(irsz.nev);
                    return item;
                },
                items: 999999,
                minLength: 2
            });
        }
    }

    function varosTypeahead(irszaminput, varosinput) {
        if ($.fn.typeahead) {
            var map = {};
            $(varosinput).typeahead({
                source: function(query, process) {
                    var texts = [];
                    return $.ajax({
                        url: '/varos',
                        type: 'GET',
                        data: {
                            term: query
                        },
                        success: function(data) {
                            var d = JSON.parse(data);
                            $.each(d, function(i, irszam) {
                                map[irszam.id] = irszam;
                                texts.push(irszam.id);
                            });
                            return process(texts);
                        }
                    });
                },
                updater: function(item) {
                    var irsz = map[item];
                    item = irsz.nev;
                    $(irszaminput).val(irsz.szam);
                    return item;
                },
                items: 999999,
                minLength: 4
            });
        }
    }

    function initTooltips() {
        $('.js-tooltipbtn').tooltip({
            html: false,
            placement: 'right',
            container: 'body'
        });
    }

    function showhideFilterClear() {
        var $arslider = $('#ArSlider'),
                arfi = $arslider.val(), arfiarr,
                maxar = $arslider.data('maxar');

        if (arfi) {
            arfiarr = arfi.split(';');
            if ((arfiarr[0] * 1 !== 0) || (arfiarr[1] * 1 <= maxar) || $('#szuroform input[type="checkbox"]:checked').length) {
                $('.js-filterclear').show();
            }
            else {
                $('.js-filterclear').hide();
            }
        }
        else {
            $('.js-filterclear').hide();
        }
    }

    return {
        showMessage: showMessage,
        closeMessage: closeMessage,
        showDialog: showDialog,
        closeDialog: closeDialog,
        lapozas: lapozas,
        overrideFormSubmit: overrideFormSubmit,
        irszamTypeahead: irszamTypeahead,
        varosTypeahead: varosTypeahead,
        initTooltips: initTooltips,
        showhideFilterClear: showhideFilterClear
    };
})(jQuery);
var mkwcheck = {
    configs: {
        kapcsolatNev: {
            nev: '#NevEdit',
            msg: '#NevMsg'
        },
        kapcsolatEmail: {
            email1: '#Email1Edit',
            msg1: '#Email1Msg',
            email2: '#Email2Edit',
            msg2: '#Email2Msg'
        },
        kapcsolatTema: {
            tema: '#TemaEdit',
            msg: '#TemaMsg'
        },
        regNev: {
            nev1: '#VezeteknevEdit',
            nev2: '#KeresztnevEdit',
            msg1: ''
        },
        regEmail: {
            email: '#EmailEdit',
            msg: ''
        },
        regJelszo: {
            jelszo1: '#Jelszo1Edit',
            jelszo2: '#Jelszo2Edit',
            msg: ''
        },
        loginEmail: {
            email: 'input[name="email"]',
            msg: ''
        },
        checkoutJelszo: {
            jelszo1: 'input[name="jelszo1"]',
            jelszo2: 'input[name="jelszo2"]',
            msg: ''
        },
        checkoutTelefon: {
            nev: 'input[name="telefon"]',
            msg: ''
        }
    },
    kapcsolatTemaCheck: function () {
        this.temacheck(this.configs.kapcsolatTema);
    },
    kapcsolatEmailCheck: function () {
        this.doubleemailcheck(this.configs.kapcsolatEmail);
    },
    kapcsolatNevCheck: function () {
        this.nevcheck(this.configs.kapcsolatNev);
    },
    regNevCheck: function () {
        this.doublenevcheck(this.configs.regNev);
    },
    regEmailCheck: function () {
        this.emailcheck(this.configs.regEmail);
    },
    regJelszoCheck: function () {
        this.pwcheck(this.configs.regJelszo);
    },
    loginEmailCheck: function () {
        this.emailcheck(this.configs.loginEmail);
    },
    checkoutJelszoCheck: function () {
        this.pwcheck(this.configs.checkoutJelszo);
    },
    checkoutTelefonCheck: function () {
        this.nevcheck(this.configs.checkoutTelefon);
    },
    wasinteraction: {
        nev: false,
        doublenev: false,
        email: false,
        doubleemail: false,
        pw: false,
        tema: false
    },
    nevcheck: function (opt) {
        var vnev = $(opt.nev),
                msg = vnev.data('errormsg'),
                nevmsg = $(opt.msg);
        vnev[0].setCustomValidity('');
        nevmsg.empty();
        if (this.wasinteraction.nev) {
            vnev.removeClass('error').addClass('valid');
        }
        if (vnev[0].validity.valueMissing) {
            if (this.wasinteraction.nev) {
                nevmsg.append(msg);
            }
            if (vnev[0].validity.valueMissing) {
                vnev[0].setCustomValidity(msg);
            }
        }
    },
    doublenevcheck: function (opt) {
        var vnev = $(opt.nev1),
                msg = vnev.data('errormsg'),
                knev = $(opt.nev2),
                nevmsg = $(opt.msg);
        vnev[0].setCustomValidity('');
        knev[0].setCustomValidity('');
        nevmsg.empty();
        if (vnev[0].validity.valueMissing || knev[0].validity.valueMissing) {
            if (this.wasinteraction.doublenev) {
                nevmsg.append(msg);
            }
            if (vnev[0].validity.valueMissing) {
                vnev[0].setCustomValidity(msg);
            }
            if (knev[0].validity.valueMissing) {
                knev[0].setCustomValidity(msg);
            }
        }
    },
    emailcheck: function (opt) {
        var email = $(opt.email),
                msg1 = email.data('errormsg1'),
                msg2 = email.data('errormsg2'),
                emailmsg = $(opt.msg),
                srvhiba = email.data('hiba') || {hibas: false};
        email[0].setCustomValidity('');
        emailmsg.empty();
        if (srvhiba.hibas) {
            emailmsg.append(srvhiba.uzenet);
            email[0].setCustomValidity(srvhiba.uzenet);
            email[0].checkValidity();
        }
        else {
            if (this.wasinteraction.email) {
                email.removeClass('error').addClass('valid');
            }
            if (email[0].validity.valueMissing) {
                if (this.wasinteraction.email) {
                    emailmsg.append(msg1);
                }
                email[0].setCustomValidity(msg1);
            }
            else {
                if (email[0].validity.typeMismatch) {
                    if (this.wasinteraction.email) {
                        emailmsg.append(msg2);
                    }
                    email[0].setCustomValidity(msg2);
                }
            }
        }
    },
    doubleemailcheck: function (opt) {
        var email1 = $(opt.email1),
                email1msg = $(opt.msg1),
                msg1 = email1.data('errormsg1'),
                msg2 = email1.data('errormsg2'),
                msg3 = email1.data('errormsg3'),
                email2 = $(opt.email2),
                email2msg = $(opt.msg2),
                srvhiba1 = email1.data('hiba') || {hibas: false},
        srvhiba2 = email2.data('hiba') || {hibas: false};
        email1[0].setCustomValidity('');
        email2[0].setCustomValidity('');
        email1msg.empty();
        email2msg.empty();
        if (srvhiba1.hibas || srvhiba2.hibas) {
            if (srvhiba1.hibas) {
                email1msg.append(srvhiba1.uzenet);
                email1[0].setCustomValidity(srvhiba1.uzenet);
                email1[0].checkValidity();
            }
            if (srvhiba2.hibas) {
                email2msg.append(srvhiba2.uzenet);
                email2[0].setCustomValidity(srvhiba2.uzenet);
                email2[0].checkValidity();
            }
        }
        else {
            if (this.wasinteraction.doubleemail) {
                email1.removeClass('error').addClass('valid');
                email2.removeClass('error').addClass('valid');
            }
            if (email1.val() != email2.val()) {
                if (this.wasinteraction.doubleemail) {
                    email2msg.append(msg3);
                }
                email1[0].setCustomValidity(msg3);
                email2[0].setCustomValidity(msg3);
            }
            else {
                if (email1[0].validity.valueMissing) {
                    email1[0].setCustomValidity(msg1);
                    if (this.wasinteraction.doubleemail) {
                        email1msg.append(msg1);
                    }
                }
                else {
                    if (email1[0].validity.typeMismatch) {
                        email1[0].setCustomValidity(msg2);
                        if (this.wasinteraction.doubleemail) {
                            email1msg.append(msg2);
                        }
                    }
                }
                if (email2[0].validity.valueMissing) {
                    email2[0].setCustomValidity(msg1);
                    if (this.wasinteraction.doubleemail) {
                        email2msg.append(msg1);
                    }
                }
                else {
                    if (email2[0].validity.typeMismatch) {
                        email2[0].setCustomValidity(msg2);
                        if (this.wasinteraction.doubleemail) {
                            email2msg.append(msg2);
                        }
                    }
                }
            }
        }
    },
    pwcheck: function (opt) {
        var pw1 = $(opt.jelszo1),
                msg1 = pw1.data('errormsg1'),
                msg2 = pw1.data('errormsg2'),
                pw2 = $(opt.jelszo2),
                pwmsg = $(opt.msg);
        pw1[0].setCustomValidity('');
        pw2[0].setCustomValidity('');
        pwmsg.empty();
        if (pw1.val() !== pw2.val()) {
            if (this.wasinteraction.pw) {
                pwmsg.append(msg2);
            }
            pw2[0].setCustomValidity(msg2);
        }
        else {
            if (pw1[0].validity.valueMissing || pw2[0].validity.valueMissing) {
                if (this.wasinteraction.pw) {
                    pwmsg.append(msg1);
                }
                if (pw1[0].validity.valueMissing) {
                    pw1[0].setCustomValidity(msg1);
                }
                if (pw2[0].validity.valueMissing) {
                    pw2[0].setCustomValidity(msg1);
                }
            }
        }
    },
    temacheck: function (opt) {
        var tema = $(opt.tema),
                msg = tema.data('errormsg'),
                temamsg = $(opt.msg);
        tema[0].setCustomValidity('');
        temamsg.empty();
        if (this.wasinteraction.tema) {
            tema.removeClass('error').addClass('valid');
        }
        if (tema[0].validity.valueMissing) {
            tema[0].setCustomValidity(msg);
            if (this.wasinteraction.tema) {
                temamsg.append(msg);
            }
        }
    }
};
var guid = (function() {
  function s4() {
    return Math.floor((1 + Math.random()) * 0x10000)
               .toString(16)
               .substring(1);
  }
  return function() {
    return s4() + s4() + '-' + s4() + '-' + s4() + '-' +
           s4() + '-' + s4() + s4() + s4();
  };
})();

var checkout = (function($, guid) {

	var checkoutpasswordrow,
			checkoutpasswordcontainer,
			vezeteknevinput, keresztnevinput, telefoninput, kapcsemailinput,
			szamlanevinput, szamlairszaminput, szamlavarosinput, szamlautcainput, adoszaminput,
			szallnevinput, szallirszaminput, szallvarosinput, szallutcainput, orszagselect,
			checkoutform,
			webshopmessageinput, couriermessageinput,
			szamlaeqszall,
			kosarhash,
            egyediid = guid();

    function getSessid() {
        var x = document.cookie.match(/PHPSESSID=[^;]+/);
        if (!x) {
            return egyediid;
        }
        if (typeof(x) == 'object') {
            x = x[0];
        }
        if (typeof(x) == 'string') {
            return x.substring(10);
        }
        return egyediid;
    }

	function loadFizmodList() {
		$.ajax({
			url: '/checkout/getfizmodlist',
			data: {
				szallitasimod: $('input[name="szallitasimod"]:checked').val()
			},
			success: function(data) {
                var d = JSON.parse(data);
				$('.js-chkfizmodlist').html(d.html);
				refreshAttekintes();
			}
		});
        loadTetelList();
	}

    function loadFoxpostCsoportData(termis) {
        if ($('input[name="szallitasimod"]:checked').hasClass('js-foxpostchk')) {
            $.ajax({
                url: '/checkout/getfoxpostcsoportlist',
                success: function(data) {
                    var d = JSON.parse(data);
                    $('.js-foxpostterminalcontainer').html(d.html).show();
                    if (termis) {
                        loadFoxpostTerminalData();
                    }
                    else {
                        refreshAttekintes();
                    }
                }
            })
        }
        else {
            $('.js-foxpostterminalcontainer').empty().hide();
            refreshAttekintes();
        }
    }

    function loadFoxpostTerminalData() {
        var cs = $('select[name="foxpostcsoport"]').val();
        $.ajax({
            url: '/checkout/getfoxpostterminallist',
            data: {
                cs: cs
            },
            success: function(data) {
                var d = JSON.parse(data);
                $('select[name="foxpostterminal"]').remove();
                $('.js-foxpostterminalcontainer').append(d.html);
                refreshAttekintes();
            }
        })
    }

    function loadKosarHash() {
        $.ajax({
            url: '/kosar/gethash',
            success: function(data) {
                var d = JSON.parse(data);
                kosarhash = d.value;
            }
        });
    }

    function loadTetelList() {
        $.ajax({
            url: '/checkout/gettetellist',
			data: {
				szallitasimod: $('input[name="szallitasimod"]:checked').val(),
                kupon: $('input[name="kupon"]').val()
			},
            success: function(data) {
                var d = JSON.parse(data);
                $('.js-chktetellist').html(d.html);
                kosarhash = d.hash.value;
                if (d.kuponszoveg) {
                    $('.js-kuponszoveg').text(d.kuponszoveg);
                }
            }
        });
    }

	function refreshAttekintes() {
		$('.js-chkvezeteknev').text(vezeteknevinput.val());
		$('.js-chkkeresztnev').text(keresztnevinput.val());
		$('.js-chktelefon').text(telefoninput.val());
		$('.js-chkkapcsemail').text(kapcsemailinput.val());
		$('.js-chkorszag').text(orszagselect.text());
        $('.js-chkszallnev').text(szallnevinput.val());
        $('.js-chkszallirszam').text(szallirszaminput.val());
        $('.js-chkszallvaros').text(szallvarosinput.val());
        $('.js-chkszallutca').text(szallutcainput.val());

		$('.js-chkadoszam').text(adoszaminput.val());
		if (szamlaeqszall.prop('checked')) {
			$('.js-chkszamlanev').text(szallnevinput.val());
			$('.js-chkszamlairszam').text(szallirszaminput.val());
			$('.js-chkszamlavaros').text(szallvarosinput.val());
			$('.js-chkszamlautca').text(szallutcainput.val());
		}
		else {
            $('.js-chkszamlanev').text(szamlanevinput.val());
            $('.js-chkszamlairszam').text(szamlairszaminput.val());
            $('.js-chkszamlavaros').text(szamlavarosinput.val());
            $('.js-chkszamlautca').text(szamlautcainput.val());
		}
		$('.js-chkszallitasimod').text($('input[name="szallitasimod"]:checked').data('caption'));
        $('.js-chkfoxpostterminal').text($('select[name="foxpostterminal"] option:selected').text());
		$('.js-chkfizmod').text($('input[name="fizetesimod"]:checked').data('caption'));
		$('.js-chkwebshopmessage').text(webshopmessageinput.val());
		$('.js-chkcouriermessage').text(couriermessageinput.val());
	}

	function openDataContainer(obj) {
/*		var $this = $(obj),
				mycontainer = $($this.data('container'));
		if (mycontainer.hasClass('js-chkclosed')) {
			$('.js-chkdatacontainer').slideUp(0).addClass('js-chkclosed');
			mycontainer.slideDown(0).removeClass('js-chkclosed');
		}
*/
	}

    function checkEmail(email) {
        var re = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;
        return re.test(email);
    }

	function initUI() {
		var $checkout = $('.js-checkout');

		if ($checkout.length) {

			$('.js-chktooltipbtn').tooltip({
				html: false,
				placement: 'right',
				container: 'body'
			});

			checkoutform = $('#CheckoutForm');
			checkoutpasswordcontainer = $('.js-checkoutpasswordcontainer');
            checkoutpasswordrow = $('.js-checkoutpasswordrow').remove();

			vezeteknevinput = $('input[name="vezeteknev"]');
			keresztnevinput = $('input[name="keresztnev"]');
			telefoninput = $('input[name="telefon"]');
			kapcsemailinput = $('input[name="kapcsemail"]');
			szamlanevinput = $('input[name="szamlanev"]');
			szamlairszaminput = $('input[name="szamlairszam"]');
			szamlavarosinput = $('input[name="szamlavaros"]');
			szamlautcainput = $('input[name="szamlautca"]');
			adoszaminput = $('input[name="adoszam"]');
			orszagselect = $('select[name="orszag"] option:selected');
			szallnevinput = $('input[name="szallnev"]');
			szallirszaminput = $('input[name="szallirszam"]');
			szallvarosinput = $('input[name="szallvaros"]');
			szallutcainput = $('input[name="szallutca"]');
			szamlaeqszall = $('input[name="szamlaeqszall"]');
			webshopmessageinput = $('textarea[name="webshopmessage"]');
			couriermessageinput = $('textarea[name="couriermessage"]');

			loadFizmodList();
            loadFoxpostCsoportData(true);

			$checkout
            .on('change', 'select[name="foxpostcsoport"]', function() {
                loadFoxpostTerminalData();
            })
			.on('change', 'input[name="szallitasimod"]', function() {
				loadFizmodList();
                loadFoxpostCsoportData(true);
			})
            .on('blur', 'input[name="kupon"]', function() {
                loadTetelList();
            })
			.on('change', 'input[name="regkell"]', function() {
				checkoutpasswordcontainer.empty();
				if ($('input[name="regkell"]:checked').val() * 1 === 2) {
					checkoutpasswordrow.appendTo(checkoutpasswordcontainer);
					$('.js-chktooltipbtn').tooltip({
						html: false,
						placement: 'right',
						container: 'body'
					});
				}
			})
			.on('change', '.js-chkrefresh', function() {
				refreshAttekintes();
			})
			.on('blur', 'input[name="vezeteknev"],input[name="keresztnev"]', function() {
				if (!szallnevinput.val() && vezeteknevinput.val() && keresztnevinput.val()) {
					szallnevinput.val(vezeteknevinput.val() + ' ' + keresztnevinput.val());
				}
			})
            .on('change', 'select[name="orszag"]', function() {
                $.ajax({
                    url: '/setorszag',
                    type: 'POST',
                    data: {
                        orszag: $('select[name="orszag"] option:selected').val()
                    },
                    success: function() {
                        loadTetelList();
                    }
                });
            });

            $('input[name="regkell"]').change();

			szamlaeqszall.on('change', function(e) {
				var obj = $('.js-chkszamlaadatok');
				obj.toggleClass('notvisible');
				if (obj.hasClass('notvisible')) {
					$('input', obj).attr('disabled', 'disabled');
				}
				else {
					$('input', obj).attr('disabled', null);
				}
				refreshAttekintes();
			});

			mkw.irszamTypeahead('input[name="szamlairszam"]', 'input[name="szamlavaros"]');
			mkw.varosTypeahead('input[name="szamlairszam"]', 'input[name="szamlavaros"]');
			mkw.irszamTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');
			mkw.varosTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');

			$('.js-chkaszf, .js-chkhelp').magnificPopup({
				type: 'ajax',
                closeBtnInside: false
			});

            checkoutform.on('submit', function(e) {
                var hibas = false, tofocus = false, hibauzenet;

                hibauzenet = mkwmsg.ChkHiba;

                $('.chk-sendorderbtn').removeClass('cartbtn').addClass('okbtn').val('Feldolgozás alatt');

                if (!$('input[name="szallitasimod"]:checked').val()) {
                    tofocus = $('input[name="szallitasimod"]');
                    hibas = true;
                    hibauzenet = mkwmsg.ChkSzallmodHiba;
                }
                if (!vezeteknevinput.val()) {
                    vezeteknevinput.addClass('hibas');
                    if (!hibas) {
                        openDataContainer(vezeteknevinput);
                        tofocus = vezeteknevinput;
                    }
                    hibas = true;
                }
                else {
                    vezeteknevinput.removeClass('hibas');
                }

                if (!keresztnevinput.val()) {
                    keresztnevinput.addClass('hibas');
                    if (!hibas) {
                        openDataContainer(keresztnevinput);
                        tofocus = keresztnevinput;
                    }
                    hibas = true;
                }
                else {
                    keresztnevinput.removeClass('hibas');
                }

                if (!telefoninput.val()) {
                    telefoninput.addClass('hibas');
                    if (!hibas) {
                        openDataContainer(telefoninput);
                        tofocus = telefoninput;
                    }
                    hibas = true;
                }
                else {
                    telefoninput.removeClass('hibas');
                }

                if (!kapcsemailinput.val() || !checkEmail(kapcsemailinput.val())) {
                    kapcsemailinput.addClass('hibas');
                    if (!hibas) {
                        openDataContainer(kapcsemailinput);
                        tofocus = kapcsemailinput;
                    }
                    hibas = true;
                }
                else {
                    kapcsemailinput.removeClass('hibas');
                }

                var jelszo1input = $('input[name="jelszo1"]'),
                    jelszo2input = $('input[name="jelszo2"]');
                if (jelszo1input.length && jelszo2input.length) {
                    if ((!jelszo1input.val() || !jelszo2input.val()) || (jelszo1input.val() != jelszo2input.val())) {
                        jelszo1input.addClass('hibas');
                        jelszo2input.addClass('hibas');
                        if (!hibas) {
                            openDataContainer(jelszo1input);
                            tofocus = jelszo1input;
                        }
                        hibas = true;
                    }
                    else {
                        jelszo1input.removeClass('hibas');
                        jelszo2input.removeClass('hibas');
                    }
                }

                if (!szallnevinput.val()) {
                    szallnevinput.addClass('hibas');
                    if (!hibas) {
                        openDataContainer(szallnevinput);
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
                        openDataContainer(szallirszaminput);
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
                        openDataContainer(szallvarosinput);
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
                        openDataContainer(szallutcainput);
                        tofocus = szallutcainput;
                    }
                    hibas = true;
                }
                else {
                    szallutcainput.removeClass('hibas');
                }

                if (!szamlaeqszall.prop('checked')) {
                    if (!szamlanevinput.val()) {
                        szamlanevinput.addClass('hibas');
                        if (!hibas) {
                            openDataContainer(szamlanevinput);
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
                            openDataContainer(szamlairszaminput);
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
                            openDataContainer(szamlavarosinput);
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
                            openDataContainer(szamlautcainput);
                            tofocus = szamlautcainput;
                        }
                        hibas = true;
                    }
                    else {
                        szamlautcainput.removeClass('hibas');
                    }
                }

                if (hibas) {
                    $('.chk-sendorderbtn').removeClass('okbtn').addClass('cartbtn').val('Megrendelés elküldése');
                    $('#dialogcenter').on('hidden', function() {
                        $('#dialogcenter').off('hidden');
                        if (tofocus) {
                            tofocus.focus();
                        }
                    });
                    mkw.showDialog(hibauzenet);
                    e.preventDefault();
                    return false;
                }
                else {
                    if (!$('input[name="aszfready"]').prop('checked')) {
                        $('.chk-sendorderbtn').removeClass('okbtn').addClass('cartbtn').val('Megrendelés elküldése');
                        e.preventDefault();
                        mkw.showDialog(mkwmsg.ChkASZF);
                        return false;
                    }
                    else {
                        mkw.showMessage(mkwmsg.ChkSave);
                        return true;
                    }
                }
            });
		}
	}

	return {
		initUI: initUI
	};

})(jQuery, guid);

var cart = (function($) {

	function submitMennyEdit(f) {
		var db = $('input[name="mennyiseg"]', f).val(),
            menny = Math.round(db);
        $.ajax({
            url: f.attr('action'),
            type: 'POST',
            data: {
                id: $('input[name="id"]', f).val(),
                mennyiseg: menny
            },
            beforeSend: function() {
                //mkw.showMessage(mkwmsg.KosarMennyisegModositas);
            },
            success: function(data) {
                var d = JSON.parse(data);
                $('#minikosar').html(d.minikosar);
                $('#minikosaringyenes').html(d.minikosaringyenes);
                //$('table').html(d.tetellist);
                //mkw.initTooltips();
                $('#ertek_' + $('input[name="id"]', f).val()).text(d.tetelertek);
                $('#kosarsum').text(d.kosarertek);
            },
            complete: function() {
                //mkw.closeMessage();
            }
        });
	}

	function initUI() {
		// Event delegation: document-re kötjük, .js-cart a szűrő
		$(document)
			.on('input', '.js-cart input[name="mennyiseg"]', $.debounce(300, function() {
                console.log('cart input 1');
				var $this = $(this);
				if ((Math.round($this.val()) != 0)) {
					submitMennyEdit($this.parents('form.kosarform'));
				}
			}))
            .on('blur', '.js-cart input[name="mennyiseg"]', function() {
				var $this = $(this);
                console.log('cart input blur 1');
                if (Math.round($this.val()) == 0) {
                    $this.val($this.data('org'));
                    submitMennyEdit($this.parents('form.kosarform'));
                    mkw.showDialog(mkwmsg.KosarMennyisegNulla);
                }
                else {
					submitMennyEdit($this.parents('form.kosarform'));
                }
            })
			.on('submit', '.js-cart .kosarform', function() {
                console.log('cart input submit 1');
				submitMennyEdit($(this));
				return false;
			});
	}

	return {
		initUI: initUI
	};

})(jQuery);

var fiok = (function($) {

	function initUI() {
		var $fiokadataimform = $('#FiokAdataim');

		if ($fiokadataimform.length > 0) {

			H5F.setup($fiokadataimform);

			$('#VezeteknevEdit,#KeresztnevEdit')
			.on('input', function(e) {
				mkwcheck.regNevCheck();
				$(this).off('keydown');
			})
			.on('keydown blur', function(e) {
				mkwcheck.wasinteraction.doublenev = true;
				mkwcheck.regNevCheck();
			})
			.each(function(i, ez) {
				mkwcheck.regNevCheck();
			});

			$('#EmailEdit')
			.on('input', function(e) {
				mkwcheck.regEmailCheck();
				$(this).off('keydown');
			})
			.on('keydown blur', function(e) {
				mkwcheck.wasinteraction.email = true;
				mkwcheck.regEmailCheck();
			})
			.on('change', function(e) {
				var $this = $(this);
				$.ajax({
					type: 'POST',
					url: '/checkemail',
					data: {email: $this.val()}
				}).done(function(data) {
					var d = JSON.parse(data);
					$this.data('hiba', d);
					mkwcheck.regEmailCheck();
				});
			})
			.each(function(i, ez) {
				mkwcheck.regEmailCheck();
			});

			$('.js-copyszamlaadat').on('click', function() {
				$('input[name="szallnev"]').val($('input[name="nev"]').val());
				$('input[name="szallirszam"]').val($('input[name="irszam"]').val());
				$('input[name="szallvaros"]').val($('input[name="varos"]').val());
				$('input[name="szallutca"]').val($('input[name="utca"]').val());
				return false;
			});

			$('.js-tooltipbtn').tooltip({
				html: false,
				placement: 'right',
				container: 'body'
			});

			mkw.overrideFormSubmit($fiokadataimform, mkwmsg.FiokAdataitModositjuk);

			$('.js-accmegrendelesopen').on('click', function() {
				$(this).next('tr').toggleClass('notvisible');
				return false;
			});
		}

		var $fiokszamlaadatok = $('#FiokSzamlaAdatok');
		if ($fiokszamlaadatok.length > 0) {
			mkw.irszamTypeahead('input[name="irszam"]', 'input[name="varos"]');
			mkw.varosTypeahead('input[name="irszam"]', 'input[name="varos"]');
			mkw.overrideFormSubmit($fiokszamlaadatok, mkwmsg.FiokAdataitModositjuk);
		}

		var $fiokszallitasiadatok = $('#FiokSzallitasiAdatok');
		if ($fiokszallitasiadatok.length > 0) {
			mkw.irszamTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');
			mkw.varosTypeahead('input[name="szallirszam"]', 'input[name="szallvaros"]');
			mkw.overrideFormSubmit($fiokszallitasiadatok, mkwmsg.FiokAdataitModositjuk);
		}

	}

	return {
		initUI: initUI
	};
})(jQuery);

$(document).ready(function() {

    var $termekertesitomodal = $('#termekertesitoModal'),
            $termekertesitoform = $('#termekertesitoform'),
        $zoom;

    function changeTermekAdat(id, d) {
        var imgpath = '';
        if ('imagepath' in d) {
            imgpath = d['imagepath'];
        }

        $zoom.destroy();

        $('#termekprice' + id).text(d['price']);
        $('#termekszallitasiido' + id).text(d['szallitasiido']);
        if ('kepurllarge' in d) {
            $('#termekkeplink' + id).attr('href', imgpath + d['kepurllarge']);
            $('#termekkep' + id).attr('src', imgpath + d['kepurllarge']);
        }
        if ('kepurlorig' in d) {
            $('#termekkep' + id).attr('data-magnify-src', imgpath + d['kepurlorig']);
        }
        if ('kepurlsmall' in d) {
            $('#termekkiskep' + id).attr('src', imgpath + d['kepurlsmall']);
        }
        if ('kepek' in d) {
            $('.js-termekimageslider .js-lightbox').each(function(index, elem) {
                if (index in d['kepek']) {
                    var $this = $(elem),
                        $img = $('img', $this);
                    $this.attr('href', imgpath + d['kepek'][index]['kepurl']);
                    $this.attr('title', d['kepek'][index]['leiras']);
                    $img.attr('src', imgpath + d['kepek'][index]['minikepurl']);
                    $img.attr('alt', d['kepek'][index]['leiras']);
                    $img.attr('title', d['kepek'][index]['leiras']);
                }
            });
        }

        $zoom = $('.zoom').magnify();
    }

    if ($.fn.mattaccord) {
        $(document).mattaccord();
    }
    if ($.fn.tab) {
        $('#termekTabbable').tab('show');
        $('#adamodositasTabbable').tab('show');
    }
    if ($.fn.slider) {
        var $arslider = $('#ArSlider');
        if ($arslider.length > 0) {
            var maxar = $arslider.data('maxar') * 1,
                    ti = $arslider.data('value'),
                    step = $arslider.data('step') * 1;
            $arslider.slider({
                from: 0,
                to: maxar,
                step: step,
                dimension: '&nbsp;Ft',
                skin: 'mkwcansas',
                callback: function(value) {
                    mkw.lapozas();
                }
            });
/*            var from = ti.split(';')[0] * 1,
            to = ti.split(';')[1] * 1;
            $arslider.slider('value', 1000, 3000);
*/
        }
    }
    if ($.fn.typeahead) {
        $('#searchinput').typeahead({
            source: function(query, process) {
                return $.ajax({
                    url: '/search',
                    type: 'GET',
                    data: {
                        term: query
                    },
                    success: function(data) {
                        var d = JSON.parse(data);
                        return process(d);
                    }
                });
            },
            onselect: function() {
                $('#searchform').submit();
            },
            items: 999999,
            minLength: 4
        });
    }
    if ($.fn.royalSlider) {
        $('#korhinta').royalSlider({
            autoScaleSlider: true,
            loopRewind: true,
            keyboardNavEnabled: true,
            controlNavigation: 'bullets',
            imageScalePadding: 0,
            autoPlay: {
                enabled: true,
                delay: 4000
            }
        });
        $('#akciostermekslider').royalSlider({
            loopRewind: true,
            keyboardNavEnabled: true,
            autoHeight: true,
            controlNavigation: 'bullets'
        });
        $('#legnepszerubbtermekslider').royalSlider({
            loopRewind: true,
            keyboardNavEnabled: true,
            autoHeight: true,
            controlNavigation: 'bullets'
        });
        $('#ajanlotttermekslider').royalSlider({
            autoHeight: true,
            loopRewind: true,
            keyboardNavEnabled: true,
            controlNavigation: 'bullets'
        });
        $('.js-termekimageslider').royalSlider({
            loopRewind: true,
            keyboardNavEnabled: true,
            controlNavigation: 'bullets',
            autoHeight: true
        });
        $('#hozzavasarolttermekslider').royalSlider({
            autoHeight: true,
            loopRewind: true,
            keyboardNavEnabled: true,
            controlNavigation: 'bullets'
        });
    }
    $termekertesitomodal.modal({
        show: false
    });
    $('.js-termekertesitobtn').on('click', function() {
        $termekertesitoform.find('input[name="termekid"]').val($(this).data('termek'));
        $termekertesitomodal.modal('show');
        return false;
    });
    mkw.overrideFormSubmit($termekertesitoform, false, {
        beforeSend: function(xhr, settings, data) {
            if (!data['email']) {
                alert('Adjon meg email címet.');
                return false;
            }
            return true;
        },
        success: function() {
            mkw.showMessage(mkwmsg.TermekErtesitoKoszonjuk);
            window.setTimeout(function() {
                mkw.closeMessage();
            }, 2500);
        },
        complete: function() {
            $termekertesitomodal.modal('hide');
            $termekertesitoform.find('input[name="termekid"]').val('');
        }
    });
    $('.js-termekertesitomodalok').on('click', function(e) {
        e.preventDefault();
        $termekertesitoform.submit();
    });
    $('.js-termekertesitodel').on('click', function(e) {
        var $this = $(this);
        e.preventDefault();
        $.ajax({
            url: '/termekertesito/save',
            type: 'POST',
            data: {
                oper: 'del',
                id: $this.data('id')
            },
            beforeSend: function() {
                mkw.showMessage(mkwmsg.TermekErtesitoLeiratkozas);
            },
            success: function() {
                $this.parents('div.js-termekertesito').remove();
            },
            complete: function() {
                mkw.closeMessage();
            }
        });
    });
    if ($.fn.magnificPopup) {
        $('.js-lightbox').magnificPopup({
            gallery: {
                enabled: true
            },
            image: {
                cursor: null
            },
            type: 'image'
        });
    }
    // nincs valtozat
    $('.js-kosarba').on('click', function(e) {
        var $this = $(this);
        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $this.attr('href'),
            data: {
                jax: 1
            },
            beforeSend: function(x) {
                mkw.showMessage(mkwmsg.TermekKosarba);
            }
        })
                .done(function(data) {
                    var d = JSON.parse(data);
                    $('#minikosar').html(d.minikosar);
                    $('#minikosaringyenes').html(d.minikosaringyenes);
                })
                .always(function() {
                    mkw.closeMessage();
                });
    });
    // lathato valtozat van
    $('.js-kosarbavaltozat').on('click', function(e) {
        var $this = $(this),
                id = $this.attr('data-id');

        e.preventDefault();
        $.ajax({
            type: 'POST',
            url: $this.attr('href'),
            data: {
                jax: 2,
                vid: $this.attr('data-vid')
            },
            beforeSend: function() {
                mkw.showMessage(mkwmsg.TermekKosarba);
            }
        })
                .done(function(data) {
                    $('.js-valtozatedit[data-id="' + id + '"]').selectedIndex = 0;
                    var d = JSON.parse(data);
                    $('#minikosar').html(d.minikosar);
                    $('#minikosaringyenes').html(d.minikosaringyenes);
                })
                .always(function() {
                    mkw.closeMessage();
                });
    });
    // valaszthato valtozat van
    $('.js-kosarbamindenvaltozat').on('click', function(e) {
        var $this = $(this),
                termekid = $this.attr('data-termek'),
                tipusok = new Array(), ertekek = new Array(),
                valtozatselect = $('.js-mindenvaltozatedit[data-termek="' + termekid + '"]');

        e.preventDefault();

        valtozatselect.each(function() {
            var $this = $(this);
            if ($this.val()) {
                tipusok.push($this.data('tipusid'));
                ertekek.push($this.val());
            }
        });

        if (valtozatselect.length !== ertekek.length) {

            console.log('nincs valtozat1');
            mkw.showDialog(mkwmsg.TermekValtozatotValassz);
        }
        else {
            $.ajax({
                type: 'POST',
                url: $this.attr('href'),
                data: {
                    jax: 3,
                    tip: tipusok,
                    val: ertekek
                },
                beforeSend: function(x) {
                    mkw.showMessage(mkwmsg.TermekKosarba);
                }
            })
                    .done(function(data) {
                        $('.js-mindenvaltozatedit[data-termek="' + termekid + '"]').selectedIndex = 0;
                        var d = JSON.parse(data);
                        $('#minikosar').html(d.minikosar);
                        $('#minikosaringyenes').html(d.minikosaringyenes);
                    })
                    .always(function() {
                        mkw.closeMessage();
                    });
        }
    });
    // valaszthato valtozat van
    $('.js-kosarbaszinvaltozat').on('click', function(e) {
        var $this = $(this),
            termekid = $this.attr('data-termek'),
            price = $this.attr('data-price'),
            caption = $this.attr('data-caption'),
            currency = $this.attr('data-currency'),
            valtozatid = $('.js-meretvaltozatedit[data-termek="' + termekid + '"] option:selected').val();

        e.preventDefault();

        if (!valtozatid) {
            console.log('nincs valtozat2');
            mkw.showDialog(mkwmsg.TermekValtozatotValassz);
        }
        else {
            $.ajax({
                type: 'POST',
                url: $this.attr('href'),
                data: {
                    jax: 2,
                    vid: valtozatid
                },
                beforeSend: function(x) {
                    mkw.showMessage(mkwmsg.TermekKosarba);
                }
            })
                .done(function(data) {
                    $('.js-meretvaltozatedit[data-termek="' + termekid + '"]').selectedIndex = 0;
                    var d = JSON.parse(data);
                    $('#minikosar').html(d.minikosar);
                    $('#minikosaringyenes').html(d.minikosaringyenes);

                    fbq('track', 'AddToCart', {
                        content_ids: [termekid],
                        content_name: caption,
                        content_type: 'product',
                        value: price,
                        currency: currency
                    });
                })
                .always(function() {
                    mkw.closeMessage();
                });
        }
    });

    // valtozat
    $('.js-valtozatedit').on('change', function() {
        var $this = $(this),
                termek = $this.data('termek'),
                id = $this.data('id');

        $.ajax({
            url: '/valtozatar',
            data: {
                t: termek,
                vid: $this.val()
            }
        })
                .done(function(data) {
                    var d = JSON.parse(data);

                    changeTermekAdat(id, d);

                })
                .always(function() {
                    $('.js-kosarbavaltozat[data-id="' + id + '"]').attr('data-vid', $this.val());
                });
    });
    $('.js-szinvaltozatedit').on('change', function() {
        var $szinedit = $(this),
                termek = $szinedit.data('termek');
        $('#meret' + termek).remove();
        $.ajax({
            url: '/getmeretszinhez',
            data: {
                t: termek,
                sz: $szinedit.val(),
            }
        })
        .done(function(data) {
            if (data) {
                var box = $szinedit.parents('.js-valtozatbox');
                box.append(data);
            }
        });
    });

    $('.color-selector .select-option').on('click', function() {
        var $option = $(this);
        var value = $option.data('value');
        var termek = $option.parent('.color-selector').data('termek');

        // 1) vizuális kijelölés
        $option
            .addClass('active')
            .siblings().removeClass('active');

        // 2) háttérben a rejtett select értékének beállítása
        var $select = $('.js-szinvaltozatedit[data-termek="' + termek + '"]');
        $select.val(value);

        // 3) manuális change trigger → lefut a meglévő AJAX /getmeretszinhez
        $select.trigger('change');
    });

    $(document).on('click', '.size-selector .select-option', function() {
        var $option = $(this);
        var value = $option.data('value');
        var termek = $option.parent('.size-selector').data('termek');
        // 1) vizuális kijelölés
        $option
            .addClass('active')
            .siblings().removeClass('active');

        // 2) háttérben a rejtett select értékének beállítása
        var $select = $('.js-meretvaltozatedit[data-termek="' + termek + '"]');
        $select.val(value);

        // 3) manuális change trigger → lefut a meglévő AJAX /getmeretszinhez
        $select.trigger('change');
    });


    var $regform = $('#Regform');
    if ($regform.length > 0) {
        H5F.setup($regform);
        $('#VezeteknevEdit,#KeresztnevEdit')
                .on('input', function(e) {
                    mkwcheck.regNevCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.doublenev = true;
                    mkwcheck.regNevCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.regNevCheck();
                });
        $('#EmailEdit')
                .on('input', function(e) {
                    mkwcheck.regEmailCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.email = true;
                    mkwcheck.regEmailCheck();
                })
                .on('change', function(e) {
                    var $this = $(this);
                    $.ajax({
                        type: 'POST',
                        url: '/checkemail',
                        data: {email: $this.val()}
                    })
                            .done(function(data) {
                                var d = JSON.parse(data);
                                $this.data('hiba', d);
                                mkwcheck.regEmailCheck();
                            });
                })
                .each(function(i, ez) {
                    mkwcheck.regEmailCheck();
                });
        $('#Jelszo1Edit,#Jelszo2Edit')
                .on('input', function(e) {
                    mkwcheck.regJelszoCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.pw = true;
                    mkwcheck.regJelszoCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.regJelszoCheck();
                });
    }
    var $passreminderform = $('#passreminderform');
    if ($passreminderform.length >0) {
        $('#Jelszo1Edit,#Jelszo2Edit')
                .on('input', function(e) {
                    mkwcheck.regJelszoCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.pw = true;
                    mkwcheck.regJelszoCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.regJelszoCheck();
                });
    }
    var $kapcsolatform = $('#Kapcsolatform');
    if ($kapcsolatform.length > 0) {
        H5F.setup($kapcsolatform);
        $('#NevEdit')
                .on('input', function(e) {
                    mkwcheck.kapcsolatNevCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.nev = true;
                    mkwcheck.kapcsolatNevCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.kapcsolatNevCheck();
                });
        $('#Email1Edit,#Email2Edit')
                .on('input', function(e) {
                    mkwcheck.kapcsolatEmailCheck();
                    $(this).off('keydown');
                })
                .on('change', function(e) {
                    var $this = $(this);
                    $.ajax({
                        type: 'POST',
                        url: '/checkemail',
                        data: {
                            email: $this.val(),
                            dce: 1
                        }
                    })
                            .done(function(data) {
                                var d = JSON.parse(data);
                                $this.data('hiba', d);
                                mkwcheck.kapcsolatEmailCheck();
                            });
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.email = true;
                    mkwcheck.kapcsolatEmailCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.kapcsolatEmailCheck();
                });
        $('#TemaEdit')
                .on('input', function(e) {
                    mkwcheck.kapcsolatTemaCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.tema = true;
                    mkwcheck.kapcsolatTemaCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.kapcsolatTemaCheck();
                });
    }
    var $loginform = $('#Loginform');
    if ($loginform.length > 0) {
        H5F.setup($loginform);
        $('#EmailEdit')
                .on('input', function(e) {
                    mkwcheck.loginEmailCheck();
                    $(this).off('keydown');
                })
                .on('keydown blur', function(e) {
                    mkwcheck.wasinteraction.email = true;
                    mkwcheck.loginEmailCheck();
                })
                .each(function(i, ez) {
                    mkwcheck.loginEmailCheck();
                });
        $('.js-passreminder').on('click', function() {
            var email = $('input[name="email"]', $loginform).val();
            if (!email) {
                mkw.showDialog(mkwmsg.PassReminderRequiredEmail);
                return false;
            }
            $.ajax({
                type: 'POST',
                url: '/getpassreminder',
                data: {
                    email: email
                },
                success: function() {
                    mkw.showDialog(mkwmsg.PassReminderCreated);
                }
            })
            return false;
        })
    }
    var $passwordchangeform = $('#JelszoChangeForm');
    if ($passwordchangeform.length > 0) {
        mkw.overrideFormSubmit($passwordchangeform, false, {
           beforeSend: function(xhr, settings, data) {
               if (!data['jelszo1'] || !data['jelszo2']) {
                   mkw.showDialog(mkwmsg.PassChange[3]);
                   return false;
               }
               if (data['jelszo1'] !== data['jelszo2']) {
                   mkw.showDialog(mkwmsg.PassChange[1]);
                   return false;
               }
               return true;
           },
           success: function(data) {
               var d = JSON.parse(data);
               if (!d.hibas) {
                   mkw.showMessage(mkwmsg.PassChange[d.hibas]);
                   window.setTimeout(function() {
                       mkw.closeMessage();
                   }, 2500);
               }
               else {
                   mkw.showDialog(mkwmsg.PassChange[d.hibas]);
               }
           }
        });
    }
    // kategoria navigalas
    var a = $('#navmain li a'),
            b = $('#navmain li .sub');
    $('#navmain li').on('click', function(e) {
        var $this = $(this),
            gy = $this.children('a'),
            v = gy.hasClass('active');
        e.preventDefault();
        if (gy.attr('data-cnt') > 0) {
            a.removeClass('active');
            b.hide();
            if (!v) {
                gy.addClass('active');
                $this.children('.sub').toggle();
            }
        }
        else {
            if (gy.length > 0) {
                document.location = gy.attr('href');
            }
        }
    });
    b.mouseup(function() {
        return false;
    });
    $(document).on('mouseup', function(c) {
        if ($(c.target).parent("#navmain li").length == 0) {
            a.removeClass("active");
            b.hide();
        }
    });
    $('div.kat').on('click', function(e) {
        e.preventDefault();
        document.location = $(this).attr('data-href');
    });
    // lapozo es szuroform
    $('.elemperpageedit').on('change', function() {
        $('.elemperpageedit').val($(this).val());
        mkw.lapozas();
    });
    $('.orderedit').on('change', function() {
        $('.orderedit').val($(this).val());
        mkw.lapozas();
    });
    $('.pageedit').on('click', function() {
        $('.lapozoform').attr('data-pageno', $(this).attr('data-pageno'));
        mkw.lapozas();
    });
    $('.termeklistview').on('click', function() {
        $('#ListviewEdit').val($(this).attr('data-vt'));
        mkw.lapozas();
    });
    $('#szuroform input').on('change', function() {
        $('.lapozoform input[name="cimkekatid"]').val($(this).attr('name').split('_')[1]);
        mkw.lapozas();
    });
    $('.js-filterclear').on('click', function(e) {
        e.preventDefault();
        $('#szuroform input[type="checkbox"]').prop('checked',false);
        $('#ArSlider').val('0;0');
        mkw.lapozas(1);
    });
    $('select[name="headerorszag"]').on('change', function(e) {
        $.ajax({
            url: '/setorszag',
            type: 'POST',
            data: {
                orszag: $('select[name="headerorszag"] option:selected').val()
            },
            success: function() {
                window.location.reload();
            }
        });

    });

    if ($.fn.magnify) {
        $zoom = $('.zoom').magnify();
    }

    mkw.initTooltips();
    mkw.showhideFilterClear();

    cart.initUI();
    checkout.initUI();
    fiok.initUI();

    $('.header .icon.search').on('click', function(e) {
        e.preventDefault();
        $('#searchform').toggleClass('header__searchform__open');
    });

    $('.header__searchform-close').on('click', function(e) {
        e.preventDefault();
        $('#searchform').toggleClass('header__searchform__open');
    });

    $('.menu-toggle').on('click', function(e) {
        e.preventDefault();
        $('.main-menu').toggleClass('main-menu__open');
    });

    function isMobileMenu() {
        return window.matchMedia('(max-width: 1024px)').matches;
    }

    $('.main-menu-item>.main-menu__arrow, .main-menu-item>a').on('click', function (e) {
        if (!isMobileMenu()) return;

        e.preventDefault();

        const $item = $(this).parent();
        const isOpen = $item.hasClass('main-menu__menu-item-open');

        $('.main-menu-item').removeClass('main-menu__menu-item-open');

        if (!isOpen) {
            $item.addClass('main-menu__menu-item-open');
        }
    });

    

    

    $('.main-menu__close').on('click', function(e) {
        e.preventDefault();
        $('.main-menu').toggleClass('main-menu__open');
    });
    
    $('.product-filter__toggle').on('click', function(e) {
        e.preventDefault();
        $('.product-filter').toggleClass('product-filter__open');
    });

    $('.product-filter__close').on('click', function(e) {
        e.preventDefault();
        $('.product-filter').removeClass('product-filter__open');
    });

    // Accordion
    
    $(".accordion .accordion-item:first .accordion-content").show();

    // --- Betöltéskor az első sor legyen nyitva ---
  $(".accordion .accordion-item:first .accordion-content").show();
  $(".accordion .accordion-item:first .accordion-header").addClass("active");

  $(".accordion-header").click(function() {
    const content = $(this).next(".accordion-content");

    // Csak egy legyen nyitva
    // $(".accordion-content").not(content).slideUp();
    // $(".accordion-header").not(this).removeClass("active");

    // Nyit/zár + aktív osztály
    content.slideToggle();
    $(this).toggleClass("active");
  });
});



class Carousel {
    constructor(sectionEl, options = {}) {
        this.section = sectionEl;
        this.wrapper = this.section.querySelector('.carousel-wrapper');
        this.items = this.wrapper.querySelectorAll('.carousel-item');

        this.prevBtn = this.section.querySelector('.carousel-prev');
        this.nextBtn = this.section.querySelector('.carousel-next');

        this.currentIndex = 0;
        this.totalItems = this.items.length;
        this.itemsPerView = 1;

        this.autoplayInterval = options.autoplayInterval || 5000;
        this.autoplayTimer = null;

        this.init();
    }

    init() {
        this.updateItemsPerView();
        this.attachEventListeners();
        this.updateCarousel();
        this.startAutoplay();
    }

    updateItemsPerView() {
        const width = window.innerWidth;

        if (width >= 1024) {
            this.itemsPerView = 5;
        } else if (width >= 768) {
            this.itemsPerView = 3;
        } else {
            this.itemsPerView = 2;
        }

        this.items.forEach(item => {
            item.style.minWidth = `${100 / this.itemsPerView}%`;
        });
    }

    getMaxIndex() {
        return Math.max(0, this.totalItems - this.itemsPerView);
    }

    attachEventListeners() {
        if (this.prevBtn) {
            this.prevBtn.addEventListener('click', () => this.prev());
        }

        if (this.nextBtn) {
            this.nextBtn.addEventListener('click', () => this.next());
        }

        // resize (globális, de instance-enként frissít)
        let resizeTimer;
        window.addEventListener('resize', () => {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                const old = this.itemsPerView;
                this.updateItemsPerView();

                if (old !== this.itemsPerView) {
                    this.currentIndex = 0;
                    this.updateCarousel();
                }
            }, 250);
        });

        // hover pause
        const container = this.section.querySelector('.carousel-container');
        if (container) {
            container.addEventListener('mouseenter', () => this.stopAutoplay());
            container.addEventListener('mouseleave', () => this.startAutoplay());
        }

        // touch
        let startX = 0;
        this.wrapper.addEventListener('touchstart', e => {
            startX = e.changedTouches[0].screenX;
        });

        this.wrapper.addEventListener('touchend', e => {
            const endX = e.changedTouches[0].screenX;
            if (endX < startX - 50) this.next();
            if (endX > startX + 50) this.prev();
        });
    }

    next() {
        const max = this.getMaxIndex();
        this.currentIndex = this.currentIndex < max ? this.currentIndex + 1 : 0;
        this.updateCarousel();
        this.resetAutoplay();
    }

    prev() {
        const max = this.getMaxIndex();
        this.currentIndex = this.currentIndex > 0 ? this.currentIndex - 1 : max;
        this.updateCarousel();
        this.resetAutoplay();
    }

    updateCarousel() {
        const offset = -(this.currentIndex * (100 / this.itemsPerView));
        this.wrapper.style.transform = `translateX(${offset}%)`;
    }

    startAutoplay() {
        if (!this.autoplayInterval) return;
        this.autoplayTimer = setInterval(() => this.next(), this.autoplayInterval);
    }

    stopAutoplay() {
        clearInterval(this.autoplayTimer);
        this.autoplayTimer = null;
    }

    resetAutoplay() {
        this.stopAutoplay();
        this.startAutoplay();
    }
}

document.addEventListener('DOMContentLoaded', () => {
    document.querySelectorAll('.carousel-section').forEach(section => {
        new Carousel(section, {
            autoplayInterval: 5000
        });
    });
});


// Header Country selector
document.addEventListener('click', function (e) {

  // Header szöveg kattintás → modal nyitás
  if (e.target.closest('#countryTrigger')) {
    const modal = document.getElementById('countryModal');
    if (modal) modal.classList.add('active');
  }

  // Bezárás
  if (e.target.closest('.country-modal__close')) {
    const modal = document.getElementById('countryModal');
    if (modal) modal.classList.remove('active');
  }

  // Ország kiválasztása
  const countryBtn = e.target.closest('.country-list button');
  if (countryBtn) {
    const value = countryBtn.dataset.value;
    const select = document.querySelector('.headerorszag');

    if (!select) return;

    select.value = value;
    select.dispatchEvent(new Event('change', { bubbles: true }));

    // Szöveg frissítése
    // azonnal (ha már jó)
    syncCountryTrigger();


    const modal = document.getElementById('countryModal');
    if (modal) modal.classList.remove('active');
  }

});

syncCountryTrigger();
setTimeout(syncCountryTrigger, 10);

// ÉS minden későbbi változásra is
document.addEventListener('change', function (e) {
if (e.target.matches('.headerorszag')) {
    syncCountryTrigger();
}
});

function syncCountryTrigger() {
  const select = document.querySelector('.headerorszag');
  const trigger = document.querySelector('.country-trigger');

  if (!select || !trigger) return;

  const selectedOption = select.options[select.selectedIndex];
  if (!selectedOption) return;

  trigger.textContent = selectedOption.text;
}

// Header Country selector

$( document ).ready(function() {

  $(document).on("click", ".side-cart__open", function() {
    const content = $(".side-cart");
    console.log('side-cart open');
    content.toggleClass("active");
  });

//   $(".side-cart__open").click(function() {
//     const content = $(".side-cart");
//     console.log('side-cart open');
//     content.toggleClass("active");
//   });

  $(document).on("click", ".side-cart__close", function() {
    const content = $(".side-cart");
    content.toggleClass("active");
  });
});





// #################
// Product datasheet
// #################


// ########################
// Product profile carousel
// ########################       


$( document ).ready(function() {
    
    const thumbsContainer = document.getElementById("thumbs");
    const mainImage = document.getElementById("mainImage");
    const carouselImages = Array.from(document.querySelectorAll("#thumbs img")).map(img => img.src);
    let currentIndex = 0;
    if (mainImage) {
       mainImage.addEventListener("click", () => {
            openLightboxByIndex(getCarouselImages(), currentIndex);
        });
    }

    function getCarouselImages() {
    return Array.from(document.querySelectorAll("#thumbs img")).map(img => img.src);
}

    document.querySelectorAll("#thumbs img").forEach((thumb, i) => {
        thumb.addEventListener("click", () => {
            openLightboxByIndex(carouselImages, i);
        });
    });

    

    if (typeof images !== "undefined" && images) {

        images.forEach((src, index) => {
            const img = document.createElement("img");
            img.src = src;
            img.dataset.index = index;
            if (index === 0) img.classList.add("active");
            img.onclick = () => changeImage(index, true);
            thumbsContainer.appendChild(img);
        });
    
        const preloaded = images.map(src => {
            const img = new Image();
            img.src = src;
            return img;
        });
    }

    function setActiveThumb(index) {
        const all = thumbsContainer.querySelectorAll("img");
        all.forEach(t => t.classList.remove("active"));

        const activeThumb = all[index];
        activeThumb.classList.add("active");

        const containerTop = thumbsContainer.scrollTop;
        const containerBottom = containerTop + thumbsContainer.clientHeight;

        const thumbTop = activeThumb.offsetTop;
        const thumbBottom = thumbTop + activeThumb.offsetHeight;

        if (thumbTop < containerTop) {
            // kilóg felül
            thumbsContainer.scrollTo({
            top: thumbTop - 20, //  
            behavior: "smooth"
            });
        } else if (thumbBottom > containerBottom) {
            // kilóg alul
            thumbsContainer.scrollTo({
            top: thumbBottom - thumbsContainer.clientHeight - 10, //  
            behavior: "smooth"
            });
        }
    }

    let isAnimating = false;

    function changeImage(newIndex) {
        if (newIndex === currentIndex) return;
        if (isAnimating) return;

        isAnimating = true;

        const wrapper = document.querySelector(".main-image-wrapper");
        const oldImage = wrapper.querySelector(".main-image");

        const direction = newIndex > currentIndex ? 1 : -1;
        const wrapperWidth = wrapper.clientWidth;

        const newImg = document.createElement("img");
        newImg.src = images[newIndex];
        newImg.className = "main-image";
        newImg.style.position = "absolute";
        newImg.style.transform =
            "translateX(" + (direction > 0 ? wrapperWidth : -wrapperWidth) + "px)";
        newImg.style.transition = "transform 0.15s ease";

        wrapper.appendChild(newImg);

        // 👉 lightbox click
        newImg.addEventListener("click", () => {
            openLightboxByIndex(getCarouselImages(), newIndex);
        });

        newImg.onload = () => {
            newImg.getBoundingClientRect();

            oldImage.style.transition = "transform 0.15s ease";
            oldImage.style.transform =
            "translateX(" + (direction > 0 ? -wrapperWidth : wrapperWidth) + "px)";

            newImg.style.transform = "translateX(0)";

            setTimeout(() => {
                oldImage.remove();
                newImg.style.position = "";
                newImg.style.transform = "";
                newImg.style.transition = "";
                newImg.style.top = "";

                isAnimating = false; // 🔓 unlock
            }, 170);
        };

        currentIndex = newIndex;
        setActiveThumb(newIndex);
    }


    const prevBtn = document.getElementById("prevBtn");
    if(prevBtn) {
        prevBtn.onclick = () => {
            const newIndex = (currentIndex - 1 + images.length) % images.length;
            changeImage(newIndex);
        };
    }


    const nextBtn = document.getElementById("nextBtn");
    if(nextBtn) {
        nextBtn.onclick = () => {
            const newIndex = (currentIndex + 1) % images.length;
            changeImage(newIndex);
        };
    }

    // Init
    if (typeof images !== "undefined" && images) {
        mainImage.src = images[0];
    }
    
    // ########
    // Lightbox
    // ########
    const lightbox = document.getElementById("lightbox");
    const lightboxImage = document.getElementById("lightboxImage");
    const lightboxClose = document.querySelector(".lightbox-close");
    const lightboxBackdrop = document.querySelector(".lightbox-backdrop");
    const prevBtnLightbox = document.querySelector(".lightbox-prev");
    const nextBtnLightbox = document.querySelector(".lightbox-next");
    let activeLightboxImages = [];
    let currentIndexLightbox = 0;

    // galéria képek tömbje
    const galleryImages = $('.gallery-image').map(function () {
        return $(this).data('image-large');
    }).get();

    $('.gallery-image').on('click', function () {
        const index = $('.gallery-image').index(this);
        openLightboxByIndex(galleryImages, index);
    });

    function openLightboxByIndex(galleryImages, index) {
        if (!galleryImages || !galleryImages.length) return;

        activeLightboxImages = galleryImages;
        currentIndexLightbox = index;

        lightboxImage.classList.add("is-fading");
        lightboxImage.src = activeLightboxImages[currentIndexLightbox];

        lightbox.classList.remove("hidden");
        document.body.style.overflow = "hidden";

        requestAnimationFrame(() => {
            lightboxImage.classList.remove("is-fading");
        });
    }

    function changeLightboxImage(newIndex) {
        lightboxImage.classList.add("is-fading");

        setTimeout(() => {
            currentIndexLightbox = newIndex;
            lightboxImage.src = activeLightboxImages[currentIndexLightbox];
        }, 150);

        setTimeout(() => {
            lightboxImage.classList.remove("is-fading");
        }, 300);
    }

    function showPrev() {
        const newIndex =
            (currentIndexLightbox - 1 + activeLightboxImages.length) %
            activeLightboxImages.length;
        changeLightboxImage(newIndex);
    }

    function showNext() {
        const newIndex =
            (currentIndexLightbox + 1) % activeLightboxImages.length;
        changeLightboxImage(newIndex);
    }

    function closeLightbox() {
        lightbox.classList.add("hidden");
        lightboxImage.src = "";
        document.body.style.overflow = "";
    }

    // function changeImageWithFade(newIndex) {
    //     lightboxImage.classList.add('is-fading');

    //     setTimeout(() => {
    //         currentIndexLightbox = newIndex;
    //         lightboxImage.src = lightboxImages[currentIndexLightbox];
    //     }, 150); // félidőn váltjuk a képet

    //     setTimeout(() => {
    //         lightboxImage.classList.remove('is-fading');
    //     }, 300); // teljes fade idő
    // }

    // function showPrev() {
    //     const newIndex = (currentIndexLightbox - 1 + lightboxImages.length) % lightboxImages.length;
    //     changeImageWithFade(newIndex);
    // }

    // function showNext() {
    //     const newIndex = (currentIndexLightbox + 1) % lightboxImages.length;
    //     changeImageWithFade(newIndex);
    // }

    

    if (lightboxClose) lightboxClose.addEventListener("click", closeLightbox);
    if (lightboxBackdrop) lightboxBackdrop.addEventListener("click", closeLightbox);
    // if (prevBtnLightbox) prevBtnLightbox.addEventListener("click", showPrev);
    // if (nextBtnLightbox) nextBtnLightbox.addEventListener("click", showNext);
    if (prevBtnLightbox) prevBtnLightbox.onclick = showPrev;
    if (nextBtnLightbox) nextBtnLightbox.onclick = showNext;
  
    document.addEventListener("keydown", (e) => {
        if (!lightbox) {
            return;
        }

        if (lightbox.classList.contains("hidden")) {
            return;
        }

        if (e.key === "Escape") closeLightbox();
        if (e.key === "ArrowLeft") showPrev();
        if (e.key === "ArrowRight") showNext();
    });

    $('.gallery-image').on('click', function() {
        const index = $('.gallery-image').index(this);
        openLightboxByIndex(index);
    });

});


