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